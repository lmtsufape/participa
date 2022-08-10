<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Mail\EmailConviteRevisor;
use App\Mail\EmailLembrete;
use App\Mail\EmailLembreteUsuarioNaoCadastrado;
use App\Mail\EmailNotificacaoTrabalhoAvaliado;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Models\Submissao\Area;
use App\Models\Submissao\ArquivoAvaliacao;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Form;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Opcao;
use App\Models\Submissao\Paragrafo;
use App\Models\Submissao\Pergunta;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RevisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //eventos em que sou revisor
        $idsEventos = Revisor::where('user_id', auth()->user()->id)->groupBy('evento_id')->select('evento_id')->get();
        $eventosComoRevisor = Evento::whereIn('id', $idsEventos)->get()->sortBy('nome');
        //return view('revisor.index')->with(['eventos' => $eventosComoRevisor]);
        //areas em que sou revirsor
        $revisores = collect();
        foreach ($eventosComoRevisor as $evento) {
            $revisores->push(Revisor::where([['user_id', auth()->user()->id], ['evento_id', $evento->id]])->get());
        }

        //dd($revisores);
        $trabalhosPorEvento = collect();
        foreach ($revisores as $revisorEvento) {
            $trabalhos = collect();
            foreach ($revisorEvento as $revisor) {
                $trabalhosAtribuidos = $revisor->trabalhosAtribuidos()->orderBy('titulo')->get();
                if (count($trabalhosAtribuidos) > 0) {
                    $trabalhos->push($trabalhosAtribuidos);
                }
            }
            $trabalhosPorEvento->push($trabalhos);
        }
        //dd($trabalhosPorEvento);
        return view('revisor.index')->with(['eventos' => $eventosComoRevisor, 'trabalhosPorEvento' => $trabalhosPorEvento]);
    }

    public function indexListarTrabalhos()
    {
        $revisor = Revisor::where('user_id', Auth::user()->id)->first();
        $trabalhos = $revisor->trabalhosAtribuidos;

        return view('revisor.listarTrabalhos', [
            'trabalhos' => $trabalhos, ]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'emailRevisor' => ['required', 'string', 'email', 'max:255'],
            'areas'        => ['required'],
            'modalidades'  => ['required'],
        ]);

        $usuario = User::where('email', $request->emailRevisor)->first();
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        // dd(count($usuario->revisor()->where('evento_id', $evento->id)->get()));
        if ($usuario == null) {
            $passwordTemporario = Str::random(8);
            $coord = User::find($evento->coordenadorId);
            Mail::to($request->emailRevisor)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Revisor', $evento->nome, $passwordTemporario, $request->emailRevisor, $coord));

            $usuario = new User();
            $usuario->email = $request->emailRevisor;
            $usuario->password = bcrypt($passwordTemporario);
            $usuario->usuarioTemp = true;
            $usuario->save();

            foreach ($request->areas as $area) {
                foreach ($request->modalidades as $modalidade) {
                    $revisor = new Revisor();
                    $revisor->trabalhosCorrigidos = 0;
                    $revisor->correcoesEmAndamento = 0;
                    $revisor->user_id = $usuario->id;
                    $revisor->areaId = $area;
                    $revisor->modalidadeId = $modalidade;
                    $revisor->evento_id = $evento->id;
                    $revisor->save();
                }
            }
        } elseif (count($usuario->revisor()->where('evento_id', $evento->id)->get()) <= 0) {
            foreach ($request->areas as $area) {
                foreach ($request->modalidades as $modalidade) {
                    $revisor = new Revisor();
                    $revisor->trabalhosCorrigidos = 0;
                    $revisor->correcoesEmAndamento = 0;
                    $revisor->user_id = $usuario->id;
                    $revisor->areaId = $area;
                    $revisor->modalidadeId = $modalidade;
                    $revisor->evento_id = $evento->id;
                    $revisor->save();
                }
            }
        } else {
            return redirect()->back()->withErrors(['errorRevisor' => 'Esse revisor já está cadastrado para o evento.'])->withInput($validatedData);
        }

        return redirect()->back()->with(['mensagem' => 'Revisor cadastrado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function show(Revisor $revisor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function edit(Revisor $revisor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find($request->editarRevisor);
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validatedData = $request->validate([
            'editarRevisor'   => 'required',
            'areasEditadas_'.$user->id => 'required',
            'modalidadesEditadas_'.$user->id => 'required',
        ]);

        $revisores = $user->revisor()->where('evento_id', '=', $evento->id)->get();
        $revisoresRetirados = collect();

        // Checando se o alguma área e modalidade foiram retiradas
        foreach ($revisores as $revisor) {
            foreach ($request->input('areasEditadas_'.$user->id) as $area) {
                foreach ($request->input('modalidadesEditadas_'.$user->id) as $modalidade) {
                    if ($revisor->areaId == $area && $revisor->modalidadeId == $modalidade) {
                        $revisoresRetirados->push($revisor);
                    }
                }
            }
        }

        $revisoresRetirados = $revisores->diff($revisoresRetirados);
        if (count($revisoresRetirados) > 0) {
            foreach ($revisoresRetirados as $revisor) {
                if (count($revisor->trabalhosAtribuidos) > 0) {
                    return redirect()->back()->withErrors(['errorRevisor' => 'Existem trabalhos atribuidos para esse revisor na área de '.$revisor->area->nome.' na modalidade de '.$revisor->modalidade->nome.'.']);
                }
            }
        }

        // Deletando os revisores que foram retirados
        if (count($revisoresRetirados) > 0) {
            foreach ($revisoresRetirados as $revisor) {
                $revisor->delete();
            }
        }

        // Adicionando os novos revisores
        foreach ($request->input('areasEditadas_'.$user->id) as $area) {
            foreach ($request->input('modalidadesEditadas_'.$user->id) as $modalidade) {
                $encontrado = false;
                foreach ($revisores as $revisor) {
                    if ($revisor->areaId == $area && $revisor->modalidadeId == $modalidade) {
                        $encontrado = true;
                    }
                }
                if ($encontrado == false) {
                    $revisor = new Revisor();
                    $revisor->trabalhosCorrigidos = 0;
                    $revisor->correcoesEmAndamento = 0;
                    $revisor->user_id = $user->id;
                    $revisor->areaId = $area;
                    $revisor->modalidadeId = $modalidade;
                    $revisor->evento_id = $evento->id;
                    $revisor->save();
                }
            }
        }

        return redirect()->back()->with(['mensagem' => 'Revisor salvo com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $evento_id)
    {
        $user = User::find($id);
        $evento = Evento::find($evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        foreach ($user->revisor()->where('evento_id', '=', $evento->id)->get() as $revisor) {
            if (count($revisor->trabalhosAtribuidos) > 0) {
                return redirect()->back()->withErrors(['errorRevisor' => 'Não é possível remover o revisor, pois há trabalhos atribuídos para o mesmo.']);
            }
            if (count($revisor->avaliacoes) > 0) {
                return redirect()->back()->withErrors(['errorRevisor' => 'Não é possível remover o revisor, pois há avaliações do mesmo.']);
            }
        }

        foreach ($user->revisor()->where('evento_id', '=', $evento->id)->get() as $revisor) {
            $revisor->delete();
        }

        return redirect()->back()->with(['mensagem' => 'Revisor removido com sucesso!']);
    }

    public function reenviarEmailRevisor($id, $evento_id)
    {
        $user = User::find($id);
        $evento = Evento::find($evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        if ($user->usuarioTemp) {
            $passwordTemporario = Str::random(8);
            $coord = User::find($evento->coordenadorId);
            Mail::to($user->email)->send(new EmailLembreteUsuarioNaoCadastrado($evento->nome, $passwordTemporario, $user->email, $coord));
            $user->password = bcrypt($passwordTemporario);
            $user->save();

            return redirect()->back()->with(['mensagem' => 'E-mail para completar o cadastrado enviado com sucesso!']);
        }

        return redirect()->back()->withErrors(['errorRevisor' => 'Não é possível reenviar um e-mail para o revisor, pois o mesmo já completou o seu cadastro.']);
    }

    public function numeroDeRevisoresAjax(Request $request)
    {
        $validatedData = $request->validate([
            'areaId' => ['required', 'string'],
        ]);

        $numeroRevisores = Revisor::where('areaId', $request->areaId)->count();

        return response()->json($numeroRevisores, 200);
    }

    public function enviarEmailRevisor(Request $request)
    {
        $user = User::find($request->revisor_id);
        $evento = Evento::find($request->evento_id);

        Mail::to($user->email)
          ->send(new EmailLembrete($user, $request->assunto, ' ', ' ', ' ', $evento, $evento->coordenador));

        return redirect()->back()->with(['mensagem' => 'E-mail de lembrete de revisão enviado para '.$user->email.'.']);
    }

    public function enviarEmailTodosRevisores(Request $request)
    {
        $subject = 'Sistema Participa - Lembrete  de trabalho';

        $revisores = json_decode($request->input('revisores'));
        foreach ($revisores as $revisor) {
            $user = User::find($revisor->id);
            //dd($user->revisor[0]->correcoesEmAndamento);
            $revisorTemp = $user->revisor[0];
            if (isset($revisorTemp->trabalhosAtribuidos)) {
                $trabalhosMail = '';
                $dataLimite = '';
                $evento = '';
                $coord = '';
                $trabalhosAtribuidos = $revisorTemp->trabalhosAtribuidos;
                $flag = false;
                foreach ($trabalhosAtribuidos as $trabalho) {
                    if ($trabalho->avaliado != 'Avaliado') {
                        $flag = true;
                        $evento = Evento::find($trabalho->eventoId);
                        $coord = User::find($evento->coordenadorId);
                        $modalidade = Modalidade::where([['evento_id', $trabalho->eventoId]])->first();
                        $trabalhosMail .= $trabalho->titulo.', ';
                        $dataLimite = $modalidade->fimRevisao;
                    }
                }
                if ($flag) {
                    Mail::to($revisor->email)
                    ->send(new EmailLembrete($user, $subject, ' ', $trabalhosMail, $dataLimite, $evento, $coord));
                }
            }
        }

        return redirect()->back()->with(['mensagem' => 'E-mails de lembrete enviados!']);
    }

    public function listarRevisores($id)
    {
        $evento = Evento::find($id);
        $areas = Area::orderBy('nome')->get();
        $revisores = Revisor::all();

        // dd($revisores[0]);

        return view('coordenador.revisores.revisoresCadastrados')->with(['evento'    => $evento,
            'revisores' => $revisores,
            'areas'     => $areas, ]);
    }

    public function conviteParaEvento(Request $request, $id)
    {
        $subject = 'Sistema Participa - Atribuição como avaliador(a) e/ou parecerista';
        $evento = Evento::find($id);

        $user = User::find($request->id);

        if ($user->revisor->eventosComoRevisor()->where([['evento_id', $id], ['convite_aceito', null]])->first() != null) {
            return redirect()->back()->with(['error' => 'Há um convite pendente para esse usuário']);
        }

        if ($user->revisor->eventosComoRevisor()->where([['evento_id', $id], ['convite_aceito', true]])->first() != null) {
            return redirect()->back()->with(['error' => 'Esse usuário já aceitou o convite!']);
        }

        $evento->revisores()->attach($user->revisor->id, ['convite_aceito'=> null]);

        //Log::debug('Revisores ' . gettype($user));
        //Log::debug('Revisores ' . $request->input('user'));
        return $request->all();
        Mail::to($user->email)
          ->send(new EmailConviteRevisor($user, $evento, $subject, Auth::user()));

        return redirect()->back()->with(['mensagem' => 'Convite enviado']);
    }

    public function revisoresPorAreaAjax($id)
    {
        $revisores = Revisor::where('areaId', $id)->get();

        $revsPorArea = collect();

        foreach ($revisores as $revisor) {
            $revisor = [
                'id'    => $revisor->user->id,
                'email' => $revisor->user->email,
                'area' => $revisor->area->nome,
                'emAndamento' => $revisor->correcoesEmAndamento,
                'concluido'   => $revisor->trabalhosCorrigidos,
            ];

            $revsPorArea->push($revisor);
        }

        return response()->json($revsPorArea);
    }

    public function trabalhosDoEvento($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isRevisor', $evento);
        $revisores = Revisor::where([['user_id', auth()->user()->id], ['evento_id', $id]])->get();
        $trabalhos = collect();
        foreach ($revisores as $revisor) {
            $trabalhos->push($revisor->trabalhosAtribuidos()->orderBy('titulo')->get());
        }
        // dd($trabalhos);
        return view('revisor.listarTrabalhos')->with(['evento' => $evento, 'trabalhosPorRevisor' => $trabalhos]);
        // $trabalhos = Atribuicao::where('eventoId', $id);
    }

    public function responde(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $evento = Evento::find($data['evento_id']);
        $data['revisor'] = Revisor::find($data['revisor_id']);
        $data['modalidade'] = Modalidade::find($data['modalidade_id']);
        $data['trabalho'] = Trabalho::find($data['trabalho_id']);

        $forms = $data['modalidade']->forms;

        return view('revisor.formularioRevisor', compact('evento', 'data', 'forms'));
    }

    public function salvarRespostas(Request $request)
    {
        // dd($request);
        $data = $request->all();
        // $comment = $post->comments()->create([
        //     'message' => 'A new comment.',
        // ]);
        $trabalho = Trabalho::find($data['trabalho_id']);
        $evento_id = $trabalho->eventoId;
        if (isset($request->arquivo)) {
            if ($this->validarTipoDoArquivo($request->arquivo, $trabalho->modalidade)) {
                return redirect()->back()->withErrors(['message' => 'Extensão de arquivo enviado é diferente do permitido.']);
            }

            $validatedData = $request->validate([
                'arquivo' => ['required', 'file', 'max:2048'],
            ]);
        }

        foreach ($data['pergunta_id'] as $key => $value) {
            $pergunta = Pergunta::find($value);
            $resposta = $pergunta->respostas()->create([
                'revisor_id' => $data['revisor_id'],
                'trabalho_id' => $data['trabalho_id'],
            ]);
            if ($pergunta->respostas->first()->paragrafo != null) {
                if ($pergunta->visibilidade == true) {
                    $resposta->paragrafo()->create([
                        'resposta' => $data[$value],
                        'visibilidade' => true,
                    ]);
                } else {
                    $resposta->paragrafo()->create([
                        'resposta' => $data[$value],
                        'visibilidade' => false,
                    ]);
                }
            } elseif ($pergunta->respostas->first()->opcoes->count()) {
                $resposta->opcoes()->create([
                    'titulo' => $data[$value],
                    'check' => true,
                    'tipo' => 'radio',
                ]);
            }
        }
        $trabalho->avaliado = 'Avaliado';
        $trabalho->atribuicoes()->where('revisor_id', $data['revisor_id'])->first()->pivot->update(['parecer' => 'avaliado']);
        $trabalho->save();
        $evento = Evento::find($evento_id);
        $revisor = Revisor::where([['user_id', auth()->user()->id], ['evento_id', $evento_id]])->first();

        if (isset($request->arquivo)) {
            $file = $request->arquivo;
            $path = 'avaliacoes/'.$evento_id.'/'.$trabalho->id.'/';
            $nome = 'avaliacao'.$revisor->id.'.'.$file->getClientOriginalExtension();
            Storage::putFileAs($path, $file, $nome);

            $arquivo = ArquivoAvaliacao::create([
                'nome'  => $path.$nome,
                'revisorId' => $data['revisor_id'],
                'trabalhoId'  => $trabalho->id,
                'versaoFinal' => true,
            ]);
        }

        $coordenador = User::find($evento->coordenadorId);
        Mail::to($coordenador->email)->send(new EmailNotificacaoTrabalhoAvaliado($coordenador, $trabalho->autor, $evento->nome, $trabalho, $revisor));

        return redirect()->route('revisor.index')->with(['message' => 'Avaliação enviada com sucesso.']);
    }

    public function editarRespostasFormulario(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $paragrafo_checkBox = $request->paragrafo_checkBox;
        $trabalho = Trabalho::find($data['trabalho_id']);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $trabalho->evento);
        if ($request->arquivoAvaliacao != null) {
            if ($this->validarTipoDoArquivo($request->arquivoAvaliacao, $trabalho->modalidade)) {
                return redirect()->back()->withErrors(['message' => 'Extensão de arquivo enviado é diferente do permitido.']);
            }

            $validatedData = $request->validate([
                'arquivoAvaliacao' => ['required', 'file', 'max:2048'],
            ]);
        }
        $opcaoCont = 0;
        $paraCont = 0;
        if ($request->pergunta_id != null) {
            foreach ($data['pergunta_id'] as $key => $value) {
                $pergunta = Pergunta::find($value);
                if ($pergunta->respostas->first()->paragrafo != null && $paraCont < count($data['resposta_paragrafo_id'])) {
                    $resposta = Paragrafo::find($data['resposta_paragrafo_id'][$paraCont++]);
                    $resposta->resposta = $data['resposta'.$resposta->id];
                    if ($paragrafo_checkBox != null && in_array($resposta->id, $paragrafo_checkBox)) {
                        $resposta->visibilidade = true;
                    } else {
                        $resposta->visibilidade = false;
                    }
                    $resposta->save();
                } elseif ($pergunta->respostas->first()->opcoes->count() && $opcaoCont < count($data['opcao_id'])) {
                    $opcao = Opcao::find($data['opcao_id'][$opcaoCont++]);
                    $opcao->titulo = $data[$value];
                    $opcao->save();
                }
            }
        }
        if ($request->arquivoAvaliacao != null) {
            $revisor = Revisor::find($data['revisor_id']);
            $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->where('revisorId', $revisor->id)->first();
            if ($arquivoAvaliacao == null) {
                $permissoes_revisao = Revisor::where([['user_id', $revisor->user_id], ['evento_id', $trabalho->evento->id]])->get()->map->only(['id']);
                $arquivoAvaliacao = $trabalho->arquivoAvaliacao()->whereIn('revisorId', $permissoes_revisao)->first();
            }
            if ($arquivoAvaliacao != null) {
                if (Storage::disk()->exists($arquivoAvaliacao->nome)) {
                    Storage::delete($arquivoAvaliacao->nome);
                }
                $arquivoAvaliacao->delete();
            }

            $file = $request->arquivoAvaliacao;
            $path = 'avaliacoes/'.$trabalho->evento->id.'/'.$trabalho->id.'/';
            $nome = 'avaliacao'.$revisor->id.'.'.$file->getClientOriginalExtension();
            Storage::putFileAs($path, $file, $nome);

            ArquivoAvaliacao::create([
                'nome'  => $path.$nome,
                'revisorId' => $revisor->id,
                'trabalhoId'  => $trabalho->id,
                'versaoFinal' => true,
            ]);
        }

        return redirect()->back()->with(['message' => 'Parecer editado com sucesso.']);
    }

    public function validarTipoDoArquivo($arquivo, $tiposExtensao)
    {
        if ($tiposExtensao->arquivo == true) {
            $tiposcadastrados = ['pdf', 'odt', 'docx', 'rtf'];

            $extensao = $arquivo->getClientOriginalExtension();
            if (! in_array($extensao, $tiposcadastrados)) {
                return true;
            }

            return false;
        }
    }
}
