<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrabalhoPostRequest;
use App\Http\Requests\TrabalhoUpdateRequest;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Mail\EmailParecerDisponivel;
use App\Mail\SubmissaoTrabalho;
use App\Models\Submissao\Area;
use App\Models\Submissao\AreaModalidade;
use App\Models\Submissao\Arquivo;
use App\Models\Submissao\ArquivoCorrecao;
use App\Models\Submissao\Arquivoextra;
use App\Models\Submissao\Avaliacao;
use App\Models\Submissao\Evento;
use App\Models\Submissao\FormSubmTraba;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Parecer;
use App\Models\Submissao\RegraSubmis;
use App\Models\Submissao\TemplateSubmis;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use App\Notifications\SubmissaoTrabalhoNotification;
use App\Policies\EventoPolicy;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrabalhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $idModalidade)
    {
        $evento = Evento::find($id);
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $areas = $areas->sortBy('nome', SORT_NATURAL)->values()->all();
        $formSubTraba = FormSubmTraba::where('eventoId', $evento->id)->first();
        $regra = RegraSubmis::where('modalidadeId', $idModalidade)->first();
        $template = TemplateSubmis::where('modalidadeId', $idModalidade)->first();
        $ordemCampos = explode(',', $formSubTraba->ordemCampos);
        array_splice($ordemCampos, 6, 0, 'midiaExtra');
        array_splice($ordemCampos, 5, 0, 'apresentacao');
        $modalidade = Modalidade::find($idModalidade);

        $mytime = Carbon::now('America/Recife');
        if (!$modalidade->estaEmPeriodoDeSubmissao()) {
            $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        }
        // dd($formSubTraba);
        return view('evento.submeterTrabalho', [
            'evento' => $evento,
            'areas' => $areas,
            // 'revisores'              => $revisores,
            // 'modalidades'            => $modalidades,
            // 'areaModalidades'        => $areaModalidades,
            // 'trabalhos'              => $trabalhos,
            // 'areasEnomes'            => $areasEnomes,
            // 'modalidadesIDeNome'     => $modalidadesIDeNome,
            // 'regrasubarq'            => $formtiposubmissao,
            // 'areasEspecificas'       => $areasEspecificas,
            // 'modalidadeEspecifica'   => $idModalidade,
            'formSubTraba' => $formSubTraba,
            'ordemCampos' => $ordemCampos,
            'regras' => $regra,
            'templates' => $template,
            'modalidade' => $modalidade,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function findResumo(Request $request)
    {
        $trabalhoResumo = Trabalho::find($request->trabalhoId);

        return $trabalhoResumo;
    }

    private function salvarArquivoComNomeOriginal($file, $path)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $originalName = str_replace('.' . $extension, '', $originalName);
        $originalName = $this->removerCaracteresEspeciais($originalName);
        $path = $file->storeAs($path, $originalName . '.' . $extension);

        return $path;
    }

    private function removerCaracteresEspeciais($string)
    {
        $string = str_replace(' ', '_', $string);
        $string = str_replace('-', '_', $string);
        $string = preg_replace('/_+/', '_', $string);
        $string = $this->substituirCaracteresEspeciais($string);

        return preg_replace('/[^A-Za-z0-9\_]/', '', $string);
    }

    private function substituirCaracteresEspeciais($text)
    {
        $utf8 = [
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-',
            '/[’‘‹›‚]/u' => ' ',
            '/[“”«»„]/u' => ' ',
            '/ /' => ' ',
        ];

        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrabalhoPostRequest $request, $modalidadeId)
    {
        //Obtendo apenas os tipos de extensões selecionadas
       
        try {
            $validatedData = $request->validated();
            $evento = Evento::find($request->eventoId);
            $modalidade = Modalidade::find($modalidadeId);
            //   dd($request->all());

            if ($this->validarTipoDoArquivo($request->arquivo, $modalidade)) {
                return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
          Verifique no formulário, quais os tipos permitidos.'])->withInput($validatedData);
            }

            if ($modalidade->apresentacao && !$request->tipo_apresentacao) {
                return redirect()->back()->withErrors(['tipo_apresentacao' => 'Selecione a forma de apresentação do trabalho.'])->withInput($validatedData);
            }

            $autor = User::where('email', $request->emailCoautor[0])->first();
            if ($autor == null) {
                $passwordTemporario = Str::random(8);
                $coord = User::find($evento->coordenadorId);
                Mail::to($request->emailCoautor[0])->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Autor', $evento->nome, $passwordTemporario, $request->emailCoautor[0], $coord));
                $autor = User::create([
                    'email' => $request->emailCoautor[0],
                    'password' => bcrypt($passwordTemporario),
                    'usuarioTemp' => true,
                    'name' => $request->nomeCoautor[0],
                ]);
            }
            // $autor = Auth::user();

            $trabalhosDoAutor = Trabalho::where('eventoId', $request->eventoId)->where('autorId', Auth::user()->id)->where('status', '!=', 'arquivado')->count();
            // $areaModalidade = AreaModalidade::where('areaId', $request->araeaId)->where('modalidadeId', $request->modalidadeId)->first();
            Log::debug('Numero de trabalhos' . $evento);
            if ($evento->numMaxTrabalhos != null && $trabalhosDoAutor >= $evento->numMaxTrabalhos) {
                return redirect()->back()->withErrors(['numeroMax' => 'Número máximo de trabalhos permitidos atingido.'])->withInput($validatedData);
            }

            if ($request->emailCoautor != null) {
                foreach ($request->emailCoautor as $key => $value) {
                    if ($value == $autor->email) {
                    } else {
                        $userCoautor = User::where('email', $value)->first();
                        if ($userCoautor == null) {
                            $passwordTemporario = Str::random(8);
                            $coord = User::find($evento->coordenadorId);
                            Mail::to($value)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Coautor', $evento->nome, $passwordTemporario, $value, $coord));
                            $usuario = User::create([
                                'email' => $value,
                                'password' => bcrypt($passwordTemporario),
                                'usuarioTemp' => true,
                                'name' => $request->nomeCoautor[$key],
                            ]);
                        }
                    }
                }
            }

            $trabalho = Trabalho::create([
                'titulo' => $request->nomeTrabalho,
                'resumo' => $request->resumo,
                'modalidadeId' => $request->modalidadeId,
                'areaId' => $request->areaId,
                'autorId' => $autor->id,
                'eventoId' => $evento->id,
                'avaliado' => 'nao',
            ]);

            if (isset($request->campoextra1simples)) {
                $trabalho->campoextra1simples = $request->campoextra1simples;
            }
            if (isset($request->campoextra1grande)) {
                $trabalho->campoextra1grande = $request->campoextra1grande;
            }
            if (isset($request->campoextra2simples)) {
                $trabalho->campoextra2simples = $request->campoextra2simples;
            }
            if (isset($request->campoextra2grande)) {
                $trabalho->campoextra2grande = $request->campoextra2grande;
            }
            if (isset($request->campoextra3simples)) {
                $trabalho->campoextra3simples = $request->campoextra3simples;
            }
            if (isset($request->campoextra3grande)) {
                $trabalho->campoextra3grande = $request->campoextra3grande;
            }
            if (isset($request->campoextra4simples)) {
                $trabalho->campoextra4simples = $request->campoextra4simples;
            }
            if (isset($request->campoextra4grande)) {
                $trabalho->campoextra4grande = $request->campoextra4grande;
            }
            if (isset($request->campoextra5simples)) {
                $trabalho->campoextra5simples = $request->campoextra5simples;
            }
            if (isset($request->campoextra5grande)) {
                $trabalho->campoextra5grande = $request->campoextra5grande;
            }

            if (isset($request->campoextra1simples_en)) {
                $trabalho->campoextra1simples_en = $request->campoextra1simples_en;
            }
            if (isset($request->campoextra1grande_en)) {
                $trabalho->campoextra1grande_en = $request->campoextra1grande_en;
            }
            if (isset($request->campoextra2simples_en)) {
                $trabalho->campoextra2simples_en = $request->campoextra2simples_en;
            }
            if (isset($request->campoextra2grande_en)) {
                $trabalho->campoextra2grande_en = $request->campoextra2grande_en;
            }
            if (isset($request->campoextra3simples_en)) {
                $trabalho->campoextra3simples_en = $request->campoextra3simples_en;
            }
            if (isset($request->campoextra3grande_en)) {
                $trabalho->campoextra3grande_en = $request->campoextra3grande_en;
            }
            if (isset($request->campoextra4simples_en)) {
                $trabalho->campoextra4simples_en = $request->campoextra4simples_en;
            }
            if (isset($request->campoextra4grande_en)) {
                $trabalho->campoextra4grande_en = $request->campoextra4grande_en;
            }
            if (isset($request->campoextra5simples_en)) {
                $trabalho->campoextra5simples_en = $request->campoextra5simples_en;
            }
            if (isset($request->campoextra5grande_en)) {
                $trabalho->campoextra5grande_en = $request->campoextra5grande_en;
            }

            if (isset($request->nomeTrabalho_en)) {
                $trabalho->titulo_en = $request->nomeTrabalho_en;
            }
            if (isset($request->resumo_en)) {
                $trabalho->resumo_en = $request->resumo_en;
            }
            if ($trabalho->modalidade->apresentacao) {
                $trabalho->tipo_apresentacao = $request->tipo_apresentacao;
            }

            $trabalho->save();
            // dd($trabalho->id);

            if ($request->emailCoautor != null) {
                foreach ($request->emailCoautor as $key => $value) {
                    if ($value == $autor->email) {
                    } else {
                        $userCoautor = User::where('email', $value)->first();
                        $coauntor = $userCoautor->coautor;
                        if ($coauntor == null) {
                            $coauntor = Coautor::create([
                                'ordem' => $key,
                                'autorId' => $userCoautor->id,
                                // 'trabalhoId'  => $trabalho->id,
                                'eventos_id' => $evento->id,
                            ]);
                        }
                        $coauntor->trabalhos()->attach($trabalho);
                    }
                }
            }

            if (isset($request->arquivo)) {
                $path = "trabalhos/{$evento->id}/{$trabalho->id}";
                $file = $request->file('arquivo');
                $path = $this->salvarArquivoComNomeOriginal($file, $path);
                Arquivo::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                    'versaoFinal' => true,
                ]);
            }

            if ($modalidade->midiasExtra) {
                foreach ($modalidade->midiasExtra as $midia) {
                    $trabalho->midiasExtra()->attach($midia->id);
                    $documento = $trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first()->pivot;
                    $documento->caminho = $request[$midia->hyphenizeNome()]->store("trabalhos/{$evento->id}/{$trabalho->id}");
                    $documento->update();
                }
            }

            if (isset($request->campoextra1arquivo)) {
                $path = $request->campoextra1arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
                Arquivoextra::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                ]);
            }

            if (isset($request->campoextra2arquivo)) {
                $path = $request->campoextra2arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
                Arquivoextra::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                ]);
            }

            if (isset($request->campoextra3arquivo)) {
                $path = $request->campoextra3arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
                Arquivoextra::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                ]);
            }

            if (isset($request->campoextra4arquivo)) {
                $path = $request->campoextra4arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
                Arquivoextra::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                ]);
            }

            if (isset($request->campoextra5arquivo)) {
                $path = $request->campoextra5arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
                Arquivoextra::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                ]);
            }

            $subject = 'Submissão de Trabalho';
            Notification::send($autor, new SubmissaoTrabalhoNotification($autor, $subject, $trabalho));
            if ($request->emailCoautor != null) {
                foreach ($request->emailCoautor as $key => $value) {
                    if ($value == $autor->email) {
                    } else {
                        $userCoautor = User::where('email', $value)->first();
                        // Mail::to($userCoautor->email)
                        //     ->send(new SubmissaoTrabalho($userCoautor, $subject, $trabalho));
                        Notification::send($userCoautor, new SubmissaoTrabalhoNotification($userCoautor, $subject, $trabalho));
                    }
                }
            }

            return redirect()->route('evento.visualizar', ['id' => $request->eventoId])
                ->with(['message' => 'Submissão concluída com sucesso!', 'class' => 'success']);
        } catch (\Throwable $th) {
            Log::info('message' . $th->getMessage());

            return redirect()->back()->with(['message' => 'Submissão não foi concluída!', 'class' => 'danger']);
        }
    }

    public function statusTrabalho($id, $status)
    {
        $trabalho = Trabalho::find($id);
        $evento = $trabalho->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        if ($trabalho->status == 'avaliado' && $status == 'rascunho') {
            $trabalho->update(['status' => $status]);

            return redirect()->back()->with(['message' => 'Encaminhamento desfeito com sucesso!', 'class' => 'success']);
        }
        $trabalho->update(['status' => $status]);
        if ($status == 'avaliado') {
            Mail::to($trabalho->autor->email)->send(new EmailParecerDisponivel($trabalho->evento, $trabalho));

            return redirect()->back()->with(['message' => 'Trabalho encaminhado ao autor com sucesso!', 'class' => 'success']);
        }

        return back();
    }

    public function encaminharTrabalho($id, Revisor $revisor)
    {
        $trabalho = Trabalho::find($id);
        $evento = $trabalho->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        if ($trabalho->getParecerAtribuicao($revisor->user) == 'encaminhado') {
            $trabalho->atribuicoes()->where('revisor_id', $revisor->id)->first()->pivot->update(['parecer' => 'avaliado']);

            return redirect()->back()->with(['message' => 'Encaminhamento desfeito com sucesso!', 'class' => 'success']);
        } else {
            if ($trabalho->avaliado($revisor->user)) {
                $trabalho->atribuicoes()->where('revisor_id', $revisor->id)->first()->pivot->update(['parecer' => 'encaminhado']);
                Mail::to($trabalho->autor->email)->send(new EmailParecerDisponivel($trabalho->evento, $trabalho));

                return redirect()->back()->with(['message' => 'Trabalho encaminhado ao autor com sucesso!', 'class' => 'success']);
            }

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function show(Trabalho $trabalho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trabalho = Trabalho::find($id);
        $modalidades = Modalidade::where('evento_id', $trabalho->eventoId)->get();
        $evento = Evento::find($trabalho->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        return view('coordenador.trabalhos.trabalho_edit', compact('trabalho', 'modalidades', 'evento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function update(TrabalhoUpdateRequest $request, $id)
    {
        // dd($request->all());
        $validatedData = $request->validated();

        $trabalho = Trabalho::find($id);
        $evento = $trabalho->evento;

        $arquivo = $request->file('arquivo' . $id);

        if ($arquivo != null && $this->validarTipoDoArquivo($arquivo, $trabalho->modalidade)) {
            return redirect()->back()->withErrors(['arquivo' . $id => 'Extensão de arquivo enviado é diferente do permitido.
                                                                  Verifique no formulário, quais os tipos permitidos.'])->withInput($validatedData);
        }

        $trabalho->titulo = $request->input('nomeTrabalho' . $id);
        $trabalho->resumo = $request->input('resumo' . $id);
        $trabalho->areaId = $request->input('area' . $id);
        if ($request->input('modalidade' . $id) != $trabalho->modalidadeId && $trabalho->avaliado == 'Avaliado') {
            return redirect()->back()->withErrors(['modalidadeError' . $id => 'Não é possível alterar a modalidade de um trabalho avaliado.'])->withInput($validatedData);
        } elseif ($request->input('modalidade' . $id) != $trabalho->modalidadeId && $trabalho->atribuicoes->count() > 0) {
            return redirect()->back()->withErrors(['modalidadeError' . $id => 'Não é possível alterar a modalidade de um trabalho com revisores atribuídos.'])->withInput($validatedData);
        } else {
            $trabalho->modalidadeId = $request->input('modalidade' . $id);
        }

        if ($trabalho->modalidade->apresentacao && !$request->tipo_apresentacao) {
            return redirect()->back()->withErrors(['tipo_apresentacao' => 'Selecione a forma de apresentação do trabalho.'])->withInput($validatedData);
        }

        $usuarios_dos_coautores = collect();
        foreach ($trabalho->coautors as $coautor_id) {
            $usuarios_dos_coautores->push(User::find($coautor_id->autorId));
        }

        $coautoresExcluidos = collect();
        // TODO Checando a mudança do autor do trabalho, a inclusão e exclusão de coautores
        foreach ($request->input('emailCoautor_' . $id) as $i => $email) {
            $usuario_do_coautor = User::where('email', $email)->first();
            // Chegando se existe do usuário cadastrado no sistema
            // Se existir é checado se o usario já é coautor do trabalho e adicionado nos coautores
            // excluidos para diff futuro. Se o usuário é existente no sistema mas não é coautor do trabalho
            // é checado se ele é coautor de algum trabalho, se for coautor o trabalho é adicionado no relacionamento,
            // se não é criado um coautor com o id do user e o trabalho é adicionado

            if ($usuario_do_coautor != null) {
                if ($usuarios_dos_coautores->contains($usuario_do_coautor)) {
                    $coautoresExcluidos->contains($usuario_do_coautor);
                } else {
                    $coautorExistente = $usuario_do_coautor->coautor;
                    if ($coautorExistente != null && $i != 0) {
                        $coautorExistente->trabalhos()->attach($trabalho);
                    } elseif ($i != 0) {
                        $coauntorAdicional = Coautor::create([
                            'ordem' => $i,
                            'autorId' => $usuario_do_coautor->id,
                            'eventos_id' => $evento->id,
                        ]);
                        $coauntorAdicional->trabalhos()->attach($trabalho);
                    }
                }
            } elseif ($usuario_do_coautor == null) {
                // Se o usuário não existir eu crio um usuário temporário um coautor para o usuário criado
                // e relaciono o trabalho a ele

                $passwordTemporario = Str::random(8);
                $coord = User::find($evento->coordenadorId);
                Mail::to($email)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Coautor', $evento->nome, $passwordTemporario, $email, $coord));
                $usuario = User::create([
                    'email' => $email,
                    'password' => bcrypt($passwordTemporario),
                    'usuarioTemp' => true,
                    'name' => $request->input('nomeCoautor_' . $id)[$i],
                ]);

                if ($i != 0) {
                    $coauntorAdicional = $usuario->coautor;
                    if ($coauntorAdicional == null) {
                        $coauntorAdicional = Coautor::create([
                            'ordem' => $i,
                            'autorId' => $usuario->id,
                            'eventos_id' => $evento->id,
                        ]);
                    }
                    $coauntorAdicional->trabalhos()->attach($trabalho);
                }
            }

            if ($i == 0) {
                // checando se o autor foi alterado
                if ($trabalho->autor->email != $email) {
                    $autor = User::where('email', $email)->first();
                    $trabalho->autorId = $autor->id;
                    // checa se o usuário passou de coautor para autor
                    if ($autor->coautor != null && $trabalho->coautors->contains($autor->coautor->id)) {
                        $trabalho->coautors()->detach($autor->coautor->id);
                    }
                }
            }
        }

        // TODO comparando os autores existentes com os excluidos
        // os que restarem são os excluidos do trabalho

        $coautoresExcluidos = $usuarios_dos_coautores->diff($coautoresExcluidos);
        foreach ($coautoresExcluidos as $usuario_do_coautor) {
            if (!(in_array($usuario_do_coautor->email, $request->input('emailCoautor_' . $id)))) {
                $usuario_do_coautor->coautor->trabalhos()->detach($id);
            }
        }

        // atualizando a ordem dos coautores
        $email_dos_coautores = $validatedData['emailCoautor_' . $id];
        unset($email_dos_coautores[0]);
        foreach ($email_dos_coautores as $ordem => $email) {
            $id_autor = User::where('email', $email)->get()->first()->id;
            $coautor = $trabalho->coautors()->where('autorId', $id_autor)->get()->first();
            if ($coautor != null) {
                $coautor->ordem = $ordem;
                if ($coautor->isDirty()) {
                    $coautor->save();
                }
            }
        }

        if (isset($request->campoextra1simples)) {
            $trabalho->campoextra1simples = $request->campoextra1simples;
        }
        if (isset($request->campoextra1grande)) {
            $trabalho->campoextra1grande = $request->campoextra1grande;
        }
        if (isset($request->campoextra2simples)) {
            $trabalho->campoextra2simples = $request->campoextra2simples;
        }
        if (isset($request->campoextra2grande)) {
            $trabalho->campoextra2grande = $request->campoextra2grande;
        }
        if (isset($request->campoextra3simples)) {
            $trabalho->campoextra3simples = $request->campoextra3simples;
        }
        if (isset($request->campoextra3grande)) {
            $trabalho->campoextra3grande = $request->campoextra3grande;
        }
        if (isset($request->campoextra4simples)) {
            $trabalho->campoextra4simples = $request->campoextra4simples;
        }
        if (isset($request->campoextra4grande)) {
            $trabalho->campoextra4grande = $request->campoextra4grande;
        }
        if (isset($request->campoextra5simples)) {
            $trabalho->campoextra5simples = $request->campoextra5simples;
        }
        if (isset($request->campoextra5grande)) {
            $trabalho->campoextra5grande = $request->campoextra5grande;
        }
        if ($trabalho->modalidade->apresentacao) {
            $trabalho->tipo_apresentacao = $request->tipo_apresentacao;
        }

        if ($request->file('arquivo' . $id) != null) {
            if ($trabalho->modalidade->submissaoUnica == false) {
                $path = "trabalhos/{$evento->id}/{$trabalho->id}";
                $file = $request->file('arquivo' . $id);
                $path = $this->salvarArquivoComNomeOriginal($file, $path);

                //É necessário excluir o arquivo da tabela de arquivo também ao editar um trabalho
                //Não só fazer o Storage::delete() do arquivo
                $arquivosTrabalho = $trabalho->arquivo()->where('versaoFinal', true)->get();
                foreach ($arquivosTrabalho as $arquivoTrabalho) {
                    if (Storage::disk()->exists($arquivoTrabalho->nome)) {
                        Storage::delete($arquivoTrabalho->nome);
                    }
                    $arquivoTrabalho->delete();
                }

                $arquivo = Arquivo::create([
                    'nome' => $path,
                    'trabalhoId' => $trabalho->id,
                    'versaoFinal' => true,
                ]);

                /*$arquivoAtual = $trabalho->arquivo()->where('versaoFinal', true)->first();
                if (Storage::disk()->exists($arquivoAtual->nome)) {
                  Storage::delete($arquivoAtual->nome);
                  $arquivoAtual->delete();
                }*/
            }
        }

        if ($trabalho->modalidade->midiasExtra) {
            foreach ($trabalho->modalidade->midiasExtra as $midia) {
                if ($request[$midia->hyphenizeNome()]) {
                    $consulta = $trabalho->midiasExtra()->where('midia_extra_id', $midia->id);
                    if (!$consulta->exists()) {
                        $trabalho->midiasExtra()->attach($midia->id);
                    }
                    $documento = $consulta->first()->pivot;
                    $documento->caminho = $request[$midia->hyphenizeNome()]->store("trabalhos/{$evento->id}/{$trabalho->id}");
                    $documento->update();
                }
            }
        }

        if (isset($request->campoextra1arquivo)) {
            $path = $request->campoextra1arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
            $arquivoExtra1 = Arquivoextra::create([
                'nome' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        if (isset($request->campoextra2arquivo)) {
            $path = $request->campoextra2arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
            $arquivoExtra2 = Arquivoextra::create([
                'nome' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        if (isset($request->campoextra3arquivo)) {
            $path = $request->campoextra3arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
            $arquivoExtra3 = Arquivoextra::create([
                'nome' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        if (isset($request->campoextra4arquivo)) {
            $path = $request->campoextra4arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
            $arquivoExtra4 = Arquivoextra::create([
                'nome' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        if (isset($request->campoextra5arquivo)) {
            $path = $request->campoextra5arquivo->store("arquivosextra/{$evento->id}/{$trabalho->id}");
            $arquivoExtra5 = Arquivoextra::create([
                'nome' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        $trabalho->update();

        return redirect()->back()->with(['mensagem' => $trabalho->titulo . ' editado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trabalho = Trabalho::find($id);
        $agora = Carbon::now();
        if (auth()->user()->id != $trabalho->autorId || !$trabalho->modalidade->estaEmPeriodoDeSubmissao()) {
            return abort(403);
        }

        $coautores = $trabalho->coautors;
        foreach ($coautores as $coautor) {
            $coautor->trabalhos()->detach($trabalho->id);

            if (count($coautor->trabalhos) <= 0) {
                $coautor->delete();
            }
        }

        if ($trabalho->arquivo != null) {
            foreach ($trabalho->arquivo as $key => $value) {
                if (Storage::disk()->exists($value->nome)) {
                    Storage::delete($value->nome);
                }
            }
            $trabalho->arquivo()->delete();
        }

        if ($trabalho->atribuicoes != null && $trabalho->atribuicoes->count() > 0) {
            foreach ($trabalho->atribuicoes as $atrib) {
                $trabalho->atribuicoes()->detach($atrib->revisor_id);
            }
        }

        $trabalho->delete();

        return redirect()->back()->with(['mensagem' => 'Trabalho deletado com sucesso!']);
    }

    public function novaVersao(Request $request)
    {
        $mytime = Carbon::now('America/Recife');
        $mytime = $mytime->toDateString();
        $trabalho = Trabalho::find($request->trabalhoId);
        $evento = $trabalho->evento;
        $modalidade = $trabalho->modalidade;

        $validatedData = $request->validate([
            'arquivo' => ['required', 'file', 'max:2000000'],
            'trabalhoId' => ['required', 'integer'],
        ]);

        if (!$modalidade->estaEmPeriodoDeSubmissao()) {
            return redirect()->back()->withErrors(['error' => 'O periodo de submissão para esse trabalho se encerrou.']);
        }

        if ($this->validarTipoDoArquivo($request, $trabalho->modalidade)) {
            return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
          Verifique no formulário, quais os tipos permitidos.', 'trabalhoId' => $trabalho->id]);
        }

        if (Auth::user()->id != $trabalho->autorId) {
            return abort(403);
        }

        $arquivos = $trabalho->arquivo;
        $count = 1;
        foreach ($arquivos as $key) {
            $key->versaoFinal = false;
            $key->save();
            $count++;
        }

        $path = $this->salvarArquivoComNomeOriginal($request->arquivo, "trabalhos/{$evento->id}/{$trabalho->id}/correcoes/{$count}/");
        $arquivo = Arquivo::create([
            'nome' => $path,
            'trabalhoId' => $trabalho->id,
            'versaoFinal' => true,
        ]);

        return redirect()->route('evento.visualizar', ['id' => $request->eventoId]);
    }

    public function detalhesAjax(Request $request)
    {
        $validatedData = $request->validate([
            'trabalhoId' => ['required', 'integer'],
        ]);

        $trabalho = Trabalho::find($request->trabalhoId);
        $revisores = $trabalho->atribuicoes;
        $revisoresAux = [];
        foreach ($revisores as $key) {
            if ($key->user->name != null) {
                array_push($revisoresAux, [
                    'id' => $key->id,
                    'nomeOuEmail' => $key->user->name,
                ]);
            } else {
                array_push($revisoresAux, [
                    'id' => $key->id,
                    'nomeOuEmail' => $key->user->email,
                ]);
            }
        }
        $evento = Evento::find($trabalho->eventoId);
        $revisoresDisponeis = $evento->revisores()->where('areaId', $trabalho->areaId)->get();
        $revisoresAux1 = [];
        foreach ($revisoresDisponeis as $key) {
            //verificar se ja é um revisor deste trabalhos
            $revisorNaoExiste = true;
            foreach ($revisoresAux as $key1) {
                if ($key->id == $key1['id']) {
                    $revisorNaoExiste = false;
                }
            }
            //
            if ($revisorNaoExiste) {
                if ($key->user->name != null) {
                    array_push($revisoresAux1, [
                        'id' => $key->id,
                        'nomeOuEmail' => $key->user->name,
                    ]);
                } else {
                    array_push($revisoresAux1, [
                        'id' => $key->id,
                        'nomeOuEmail' => $key->user->email,
                    ]);
                }
            }
        }

        return response()->json([
            'titulo' => $trabalho->titulo,
            'resumo' => $trabalho->resumo,
            'revisores' => $revisoresAux,
            'revisoresDisponiveis' => $revisoresAux1,
        ], 200);
    }

    public function downloadMidiaExtra($id, $idMidia)
    {
        $trabalho = Trabalho::find($id);
        $revisor = Revisor::where([['evento_id', '=', $trabalho->eventoId], ['user_id', '=', auth()->user()->id]])->first();
        $midia = $trabalho->midiasExtra()->where('midia_extra_id', $idMidia)->first()->pivot;

        /*
          O usuário só tera permissão para baixar o arquivo se for revisor do trabalho
          ou se for coordenador do evento, coordenador da comissão, se pertencer a comissão
          do evento, se for autor do trabalho ou se for coautor.
        */
        $comoCoautor = Coautor::where('autorId', auth()->user()->id)->first();
        $trabalhosCoautor = collect();
        if ($comoCoautor != null) {
            $trabalhosC = $comoCoautor->trabalhos;
            foreach ($trabalhosC as $trab) {
                if ($trab->autorId != auth()->user()->id) {
                    $trabalhosCoautor->push($trab->id);
                }
            }
        }

        $evento = $trabalho->evento;
        $usuariosDaComissaoCientifica = $evento->usuariosDaComissao;
        $usuariosDaComissaoOrganizadora = $evento->usuariosDaComissaoOrganizadora;
        $usuarioLogado = auth()->user();

        if (
            $evento->coordenadorId == $usuarioLogado->id
            || $usuariosDaComissaoCientifica->contains($usuarioLogado)
            || $usuariosDaComissaoOrganizadora->contains($usuarioLogado)
            || $evento->userIsCoordComissaoCientifica($usuarioLogado)
            || $evento->userIsCoordComissaoOrganizadora($usuarioLogado)
            || $trabalho->autorId == $usuarioLogado->id
            || $trabalhosCoautor->contains($trabalho->id)
            || $usuarioLogado->administradors()->exists()
        ) {
            // dd($arquivo);
            if ($midia != null && Storage::disk()->exists($midia->caminho)) {
                return Storage::download($midia->caminho);
            }

            return abort(404);
        } elseif ($revisor != null) {
            if ($revisor->trabalhosAtribuidos->contains($trabalho)) {
                if (Storage::disk()->exists($midia->caminho)) {
                    return Storage::download($midia->caminho);
                }

                return abort(404);
            } else {
                if (Storage::disk()->exists($midia->caminho)) {
                    return Storage::download($midia->caminho);
                }

                return abort(404);
            }
        }

        return abort(403);
    }

    //função para download do arquivo do trabalho
    public function downloadArquivo($id)
    {
        $trabalho = Trabalho::find($id);
        $revisor = Revisor::where([['evento_id', '=', $trabalho->eventoId], ['user_id', '=', auth()->user()->id]])->first();
        $user = User::find(auth()->user()->id);

        /*
          O usuário só tera permissão para baixar o arquivo se for revisor do trabalho
          ou se for coordenador do evento, coordenador da comissão, se pertencer a comissão
          do evento, se for autor do trabalho ou se for coautor.
        */
        $arquivo = $trabalho->arquivo()->where('versaoFinal', true)->first();

        $comoCoautor = Coautor::where('autorId', auth()->user()->id)->first();
        $trabalhosCoautor = collect();
        if ($comoCoautor != null) {
            $trabalhosC = $comoCoautor->trabalhos;
            foreach ($trabalhosC as $trab) {
                if ($trab->autorId != auth()->user()->id) {
                    $trabalhosCoautor->push($trab->id);
                }
            }
        }

        $evento = $trabalho->evento;
        $usuariosDaComissaoCientifica = $evento->usuariosDaComissao;
        $usuariosDaComissaoOrganizadora = $evento->usuariosDaComissaoOrganizadora;
        $usuarioLogado = auth()->user();

        if (
            $evento->coordenadorId == $usuarioLogado->id
            || $usuariosDaComissaoCientifica->contains($usuarioLogado)
            || $usuariosDaComissaoOrganizadora->contains($usuarioLogado)
            || $evento->userIsCoordComissaoCientifica($usuarioLogado)
            || $evento->userIsCoordComissaoOrganizadora($usuarioLogado)
            || $trabalho->autorId == $usuarioLogado->id
            || $trabalhosCoautor->contains($trabalho->id)
            || $usuarioLogado->administradors()->exists()
        ) {
            // dd($arquivo);
            if ($arquivo != null && Storage::disk()->exists($arquivo->nome)) {
                return Storage::download($arquivo->nome);
            }

            return abort(404);
        } elseif ($revisor != null) {
            if ($revisor->trabalhosAtribuidos->contains($trabalho)) {
                if (Storage::disk()->exists($arquivo->nome)) {
                    return Storage::download($arquivo->nome);
                }

                return abort(404);
            } else {
                if (Storage::disk()->exists($arquivo->nome)) {
                    return Storage::download($arquivo->nome);
                }

                return abort(404);
            }
        }

        return abort(403);
    }

    public function downloadArquivoAvaliacao(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalhoId);
        $modalidadesRevisor = Revisor::where([['evento_id', '=', $trabalho->eventoId], ['user_id', '=', $request->revisorUserId]])->get();
        if ($modalidadesRevisor->count() > 0) {
            $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $modalidadesRevisor->first()->id]])->first();
            foreach ($modalidadesRevisor as $revisor) {
                if ($trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first() != null) {
                    $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first();
                    break;
                }
            }
            $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first();
            $eventoPolicy = new EventoPolicy();
            if ($eventoPolicy->isCoordenadorOrCoordenadorDaComissaoCientifica(auth()->user(), $trabalho->evento)) {
                if ($arquivo != null && Storage::disk()->exists($arquivo->nome)) {
                    return Storage::download($arquivo->nome);
                }

                return abort(404);
            } elseif (($revisor != null && $revisor->id == auth()->user()->id) || ($trabalho->status == 'avaliado' && $trabalho->autorId == auth()->user()->id) || ($trabalho->avaliado($revisor->user) && $trabalho->autorId == auth()->user()->id)) {
                if ($revisor->trabalhosAtribuidos->contains($trabalho) || ($trabalho->autorId == auth()->user()->id)) {
                    if (Storage::disk()->exists($arquivo->nome)) {
                        return Storage::download($arquivo->nome);
                    }

                    return abort(404);
                }
            }

            return abort(403);
        }

        return abort(403);
    }

    public function aprovacaoTrabalho(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalho_id);
        $mensagem = '';

        if ($request->aprovacao == '1') {
            $trabalho->aprovado = true;
            $mensagem = 'Trabalho aprovado com sucesso!';
        } elseif ($request->aprovacao == '0') {
            $trabalho->aprovado = false;
            $mensagem = 'Trabalho reprovado com sucesso!';
        }

        $trabalho->update();

        return redirect()->back()->with(['message' => $mensagem, 'class' => 'success']);
    }

    public function correcaoTrabalho(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalhoCorrecaoId);
        $evento = $trabalho->evento;
        $this->authorize('permissaoCorrecao', $trabalho);
        if ($request->arquivoCorrecao != null) {
            if ($this->validarTipoDoArquivo($request->arquivoCorrecao, $trabalho->modalidade)) {
                return redirect()->back()->withErrors(['mensagem' => 'Extensão de arquivo enviado é diferente do permitido.']);
            }

            $validatedData = $request->validate([
                'arquivoCorrecao' => ['required', 'file', 'max:2048'],
            ]);

            $arquivoCorrecao = $trabalho->arquivoCorrecao()->first();
            if ($arquivoCorrecao != null) {
                if (Storage::disk()->exists($arquivoCorrecao->caminho)) {
                    Storage::delete($arquivoCorrecao->caminho);
                }
                $arquivoCorrecao->delete();
            }

            $path = $this->salvarArquivoComNomeOriginal($request->arquivoCorrecao, "correcoes/{$evento->id}/{$trabalho->id}");
            $arquivo = ArquivoCorrecao::create([
                'caminho' => $path,
                'trabalhoId' => $trabalho->id,
            ]);
        }

        return redirect()->back()->with(['mensagem' => 'Correção de ' . $trabalho->titulo . ' enviada com sucesso!']);
    }

    public function downloadArquivoCorrecao(Request $request)
    {
        $trabalho = Trabalho::find($request->id);
        $this->authorize('permissaoCorrecao', $trabalho);
        $arquivo = $trabalho->arquivoCorrecao()->first();
        if ($arquivo != null && Storage::disk()->exists($arquivo->caminho)) {
            return Storage::download($arquivo->caminho);
        } else {
            return abort(404);
        }
    }

    public function resultados($id, $column = 'titulo', $direction = 'asc', $status = 'rascunho')
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $todosTrabalhos = Trabalho::where('eventoId', $id)->orderBy('titulo')->get();
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('nome')->get();
        $direcao = 'desc';

        $trabalhos = null;

        if ($column == 'autor') {
            $trabalhos = collect();
            foreach ($modalidades as $modalidade) {
                $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->autor->name;
                    },
                    SORT_REGULAR,
                    $direcao == $direction
                ));
            }
        } elseif ($column == 'titulo') {
            $trabalhos = collect();
            foreach ($modalidades as $modalidade) {
                $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->titulo;
                    },
                    SORT_REGULAR,
                    $direcao == $direction
                ));
            }
        } elseif ($column == 'areaId') {
            $trabalhos = collect();
            foreach ($modalidades as $modalidade) {
                $trabalhos->push(Trabalho::where([['modalidadeId', $modalidade->id], ['status', '=', $status]])->get()->sortBy(
                    function ($trabalho) {
                        return $trabalho->area->nome;
                    },
                    SORT_REGULAR,
                    $direcao == $direction
                ));
            }
        }

        return view('coordenador.trabalhos.resultados', [
            'evento' => $evento,
            'areas' => $areas,
            'trabalhos' => $todosTrabalhos,
            'trabalhosPorModalidade' => $trabalhos,
            'agora' => now(),
        ]);
    }

    public function parecerFinalTrabalho(Request $request)
    {
        $msg = '';
        $trabalho = Trabalho::find($request->trabalho_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento);
        $parecer = '';
        if ($request->aprovar == '1') {
            $trabalho->parecer_final = true;
            $parecer = 'positivo';
            $msg = 'Parecer final do trabalho aprovado!';
        } elseif ($request->aprovar == '0') {
            $trabalho->parecer_final = false;
            $parecer = 'negativo';
            $msg = 'Parecer final do trabalho reprovado!';
        }
        if (($msgs = $trabalho->evento->mensagensParecer) && $msgs->count() > 1) {
        } elseif (($msgs = $trabalho->area->mensagensParecer) && $msgs->count() > 1) {
        } elseif (($msgs = $trabalho->modalidade->mensagensParecer) && $msgs->count() > 1) {
        } else {
            return redirect()->back()->with('error', 'Cadastre as mensagens do parecer antes de realizar esta ação.');
        }
        $msgParecer = $msgs->where('parecer', $parecer)->first();
        $justificativa = str_replace(
            ['%NOME_AUTOR%', '%TITULO_TRABALHO%', '%NOME_EVENTO%', '%NOME_MODALIDADE%', '%NOME_AREA%'],
            [$trabalho->autor->name, $trabalho->titulo, $trabalho->evento->nome, $trabalho->modalidade->nome, $trabalho->area->nome],
            $msgParecer->texto
        );
        Parecer::updateOrCreate(['parecer_final' => true, 'trabalhoId' => $trabalho->id], ['resultado' => $parecer, 'justificativa' => $justificativa]);

        $trabalho->update();

        return redirect()->back()->with(['success' => $msg]);
    }

    public function infoParecerTrabalho(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalho_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento);

        $trabalhoInfo = [
            'id' => $trabalho->id,
            'titulo' => $trabalho->titulo,
            'parecer' => $trabalho->parecer_final,
        ];

        return response()->json($trabalhoInfo);
    }

    public function pesquisaAjax(Request $request)
    {
        if ($request->areaId != null) {
            $area_id = $request->areaId;
        } else {
            $area_id = 1;
        }

        if ($request->texto != null) {
            $texto = $request->texto;
        } else {
            $texto = '';
        }

        $trabalhos = Trabalho::where('areaId', $area_id)->whereRaw('LOWER(titulo) like ?', ['%' . $texto . '%'])->orderBy('titulo')->get();

        $trabalhoJson = collect();

        foreach ($trabalhos as $i => $trab) {
            if ($i == 0) {
                $evento = $trab->evento;
                $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
            }
            $trabalho = [
                'id' => $trab->id,
                'titulo' => $trab->titulo,
                'nome' => $trab->autor->name,
                'area' => $trab->area->nome,
                'modalidade' => $trab->modalidade->nome,
                'rota_download' => !(empty($trab->arquivo->nome)) ? route('downloadTrabalho', ['id' => $trab->id]) : '#',
            ];
            $trabalhoJson->push($trabalho);
        }

        return response()->json($trabalhoJson);
    }

    public function avaliarTrabalho(Request $request, $trabalho_id)
    {
        // dd($request);
        $exibirValidacao = $request->validate([
            'avaliar_trabalho_id' => 'required',
            'modalidade_id' => 'required',
            'area_id' => 'required',
            'evento_id' => 'required',
        ]);

        $modalidade = Modalidade::find($request->modalidade_id);
        $revisor = Revisor::where([['user_id', auth()->user()->id], ['modalidadeId', $request->modalidade_id], ['areaId', $request->area_id], ['evento_id', $request->evento_id]])->first();
        $trabalho = Trabalho::find($trabalho_id);

        // dd($revisor);
        foreach ($modalidade->criterios as $criterio) {
            $validarCriterio = $request->validate([
                'criterio_' . $criterio->id => 'required',
            ]);
        }

        $validarParecer = $request->validate([
            'parecer_final' => 'required',
            'justificativa' => 'required',
        ]);

        foreach ($modalidade->criterios as $criterio) {
            $avaliacao = new Avaliacao();
            $avaliacao->revisor_id = $revisor->id;
            $avaliacao->opcao_criterio_id = $request->input('criterio_' . $criterio->id);
            $avaliacao->trabalho_id = $trabalho_id;
            $avaliacao->save();
        }

        // Atualizando tabelas
        $atribuicao = $trabalho->atribuicoes()->updateExistingPivot($revisor->id, ['confirmacao' => true, 'parecer' => 'dado']);
        $trabalho->avaliado = 'Avaliado';
        $trabalho->update();

        //Atualizando os status do revisor
        $revisor = $trabalho->atribuicoes()->where('revisor_id', $revisor->id)->first();
        $revisor->trabalhosCorrigidos++;
        $revisor->correcoesEmAndamento--;
        $revisor->update();

        // Salvando parecer final
        $parecer = new Parecer();
        $parecer->resultado = $request->parecer_final;
        $parecer->justificativa = $request->justificativa;
        $parecer->revisorId = $revisor->id;
        $parecer->trabalhoId = $trabalho->id;
        $parecer->save();

        return redirect()->back()->with(['mensagem' => 'Avaliação salva']);
    }

    public function validarTipoDoArquivo($arquivo, $tiposExtensao)
    {
        if ($tiposExtensao->arquivo == true) {
            $tiposcadastrados = [];
            if ($tiposExtensao->pdf == true) {
                array_push($tiposcadastrados, 'pdf');
            }
            if ($tiposExtensao->jpg == true) {
                array_push($tiposcadastrados, 'jpg');
            }
            if ($tiposExtensao->jpeg == true) {
                array_push($tiposcadastrados, 'jpeg');
            }
            if ($tiposExtensao->png == true) {
                array_push($tiposcadastrados, 'png');
            }
            if ($tiposExtensao->docx == true) {
                array_push($tiposcadastrados, 'docx');
            }
            if ($tiposExtensao->odt == true) {
                array_push($tiposcadastrados, 'odt');
            }
            if ($tiposExtensao->zip == true) {
                array_push($tiposcadastrados, 'zip');
            }
            if ($tiposExtensao->svg == true) {
                array_push($tiposcadastrados, 'svg');
            }
            if ($tiposExtensao->mp4 == true) {
                array_push($tiposcadastrados, 'mp4');
            }
            if ($tiposExtensao->mp3 == true) {
                array_push($tiposcadastrados, 'mp3');
            }

            $extensao = $arquivo->getClientOriginalExtension();
            if (!in_array($extensao, $tiposcadastrados)) {
                return true;
            }

            return false;
        }
    }
}
