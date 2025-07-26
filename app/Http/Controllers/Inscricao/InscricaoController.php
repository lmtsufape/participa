<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Submissao\EventoController;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\CupomDeDesconto;
use App\Models\Inscricao\Inscricao;
use Illuminate\Support\Facades\DB;
use App\Models\Inscricao\LinksPagamento;
use App\Models\Inscricao\Promocao;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Evento;
use App\Notifications\InscricaoAprovada;
use App\Notifications\InscricaoEvento;
use App\Notifications\PreInscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Users\User;
use App\Models\Users\Administrador;

class InscricaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $promocoes = Promocao::where('evento_id', $id)->get();
        $atividades = Atividade::where('eventoId', $id)->get();
        $cuponsDeDescontro = CupomDeDesconto::where('evento_id', $id)->get();
        $categoriasParticipante = CategoriaParticipante::where('evento_id', $id)->get();
        $camposDoFormulario = CampoFormulario::where('evento_id', $id)->get();
        $users = $evento->inscritos();

        return view('coordenador.programacao.inscricoes', ['evento' => $evento,
            'promocoes' => $promocoes,
            'atividades' => $atividades,
            'cupons' => $cuponsDeDescontro,
            'categorias' => $categoriasParticipante,
            'users' => $users,
            'campos' => $camposDoFormulario, ]);
    }

    public function inscritos(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $inscricoes = $evento->inscritos()->sortBy('finalizada');

        return view('coordenador.inscricoes.inscritos', compact('inscricoes', 'evento'));
    }

    public function formulario(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $campos = $evento->camposFormulario;
        return view('coordenador.inscricoes.formulario', compact('evento', 'campos'));
    }

    public function categorias(Evento $evento)
    {
        $date = date('Y-m-d');


        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $categorias = $evento->categoriasParticipantes;

        $links = DB::table('links_pagamentos')
        ->join('categoria_participantes', 'links_pagamentos.categoria_id', '=', 'categoria_participantes.id')
        ->select('categoria_participantes.nome', 'links_pagamentos.*')
        ->get();


        $linksAtuais = $links->where('dataInicio','<=', $date)
        ->where('dataFim','>', $date);


        return view('coordenador.inscricoes.categorias', compact('evento', 'categorias', 'links','linksAtuais'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $evento = Evento::find($id);

        return view('evento.nova_inscricao', ['evento' => $evento,
            'eventoVoltar' => null,
            'valorTotalVoltar' => null,
            'promocaoVoltar' => null,
            'atividadesVoltar' => null,
            'cupomVoltar' => null,
            'inscricao' => null, ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);

        if ($inscricao != null) {
            $inscricao->finalizada = true;
            $inscricao->update();

            return response()->json('OK.', 200);
        }

        return response()->json('Não encontrado.', 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inscricao = Inscricao::find($id);

        foreach ($inscricao->camposPreenchidos as $campo) {
            switch ($campo->tipo) {
                case 'file':
                    $campoSalvo = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $campo->id)->first();
                    if ($campoSalvo != null && Storage::disk()->exists($campoSalvo->pivot->valor)) {
                        Storage::delete($campoSalvo->pivot->valor);
                    }
                    break;
                case 'endereco':
                    $endereco = Endereco::find($campo->pivot->valor);
                    $endereco->delete();
                    break;
            }
            $campo->inscricoesFeitas()->detach($inscricao->id);
        }

        $inscricao->delete();
    }

    public function cancelar(Inscricao $inscricao)
    {
        $evento = $inscricao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $this->destroy($inscricao->id);
        return redirect()->back()->with('message', 'Inscrição cancelada com sucesso!');
    }

    public function checarDados(Request $request, $id)
    {
        $validatorData = $request->validate([
            'categoria' => 'required',
            'promocao' => 'nullable',
            'valorTotal' => 'required',
            'atividades' => 'nullable',
            'cupom' => 'nullable',
            'atividadesPromo' => 'nullable',
            'valorPromocao' => 'nullable',
            'descricaoPromo' => 'nullable',
        ]);

        $categoria = CategoriaParticipante::find($request->categoria);
        $validator = $this->validarCamposExtras($request, $categoria);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('abrirmodalinscricao', true);
        }
        $evento = Evento::find($id);

        if ($evento->recolhimento == 'gratuito' && $request->valorTotal * 1 == 0) {
            return $this->cadastrarInscricaoRetornarProEvento($evento, $request, $categoria);
        }

        $valorDaInscricao = $request->valorTotal;
        $promocao = null;
        $atividades = null;
        $valorComDesconto = null;
        $cupom = null;

        if ($request->revisandoInscricao != null) {
            $inscricao = Inscricao::find($request->revisandoInscricao);
            $cupom = $inscricao->cupomDesconto;
        } else {
            $cupom = CupomDeDesconto::where([['evento_id', $evento->id], ['identificador', '=', $request->cupom]])->first();
        }

        if ($request->cupom != null || $request->revisandoInscricao != null) {
            if ($cupom != null && $cupom->porcentagem) {
                $valorComDesconto = $valorDaInscricao - $valorDaInscricao * ($cupom->valor / 100);
            } else {
                $valorComDesconto -= $cupom->valor;
            }
        }

        if ($request->promocao != null) {
            $promocao = Promocao::find($request->promocao);
        }

        if ($request->atividades != null) {
            if ($promocao->atividades != null) {
                $idsAtvsPromo = $promocao->atividades()->select('atividades.id')->get();
                foreach ($request->atividades as $atv) {
                    if ($idsAtvsPromo->contains($atv)) {
                        return redirect()->back()->withErrors(['atvIguais' => 'Existem atividades adicionais que já estão presentes no pacote. Logo foram removidas.'])->withInput($validatorData);
                    }
                }
            }
            $atividades = Atividade::whereIn('id', $request->atividades)->get();
        }

        if ($request->revisandoInscricao != null) {
            $inscricao = Inscricao::find($request->revisandoInscricao);
            $inscricao->user_id = auth()->user()->id;
            $inscricao->evento_id = $evento->id;
            $inscricao->categoria_participante_id = $categoria->id;
            if ($promocao != null) {
                $inscricao->promocao_id = $promocao->id;
            }
            if ($cupom != null) {
                $inscricao->cupom_desconto_id = $cupom->id;
            }
            $inscricao->pagamento_id = null;
            $inscricao->finalizada = false;
            $inscricao->update();

            $this->salvarCamposExtras($inscricao, $request, $categoria);
        } else {
            $inscricao = new Inscricao();
            $inscricao->user_id = auth()->user()->id;
            $inscricao->evento_id = $evento->id;
            $inscricao->categoria_participante_id = $categoria->id;
            if ($promocao != null) {
                $inscricao->promocao_id = $promocao->id;
            }
            if ($cupom != null) {
                $inscricao->cupom_desconto_id = $cupom->id;
            }
            $inscricao->pagamento_id = null;
            $inscricao->finalizada = false;
            $inscricao->save();

            $this->salvarCamposExtras($inscricao, $request, $categoria);
        }

        return view('evento.revisar_inscricao', ['evento' => $evento,
            'valor' => $valorDaInscricao,
            'promocao' => $promocao,
            'atividades' => $atividades,
            'cupom' => $cupom,
            'valorComDesconto' => $valorComDesconto,
            'inscricao' => $inscricao, ]);
    }

    public function cadastrarInscricaoRetornarProEvento(Evento $evento, Request $request, CategoriaParticipante $categoria)
    {
        $inscricao = new Inscricao();
        $inscricao->user_id = auth()->user()->id;
        $inscricao->evento_id = $evento->id;
        $inscricao->categoria_participante_id = $categoria->id;
        $inscricao->finalizada = !$evento->formEvento->modvalidarinscricao;
        $inscricao->save();
        $this->salvarCamposExtras($inscricao, $request, $categoria);

        return redirect()->action([EventoController::class, 'show'], ['id' => $evento->id])->with('message', 'Inscrição realizada com sucesso');
    }

    public function inscrever(Request $request)
    {
        auth()->user() != null;
        $evento = Evento::find($request->evento_id);
        //"pre inscrição" feita na submissão de trabalhos por um user não inscrito, sem categoria e pagamento
        $preInscricao = false;
        if (Inscricao::where('user_id', auth()->user()->id)->where('evento_id', $evento->id)->where('finalizada', true)->exists()) {
            return redirect()->action([EventoController::class, 'show'], ['id' => $request->evento_id])->with('message', 'Inscrição já realizada.');
        }
        if ($evento->eventoInscricoesEncerradas()) {
            return redirect()->action([EventoController::class, 'show'], ['id' => $request->evento_id])->with('message', 'Inscrições encerradas.');
        }
        if(Inscricao::where('user_id', auth()->user()->id)->where('evento_id', $evento->id)->where('finalizada', false)->exists()){
            $preInscricao = true;
        }
        $categoria = CategoriaParticipante::find($request->categoria);
        $possuiFormulario = $evento->possuiFormularioDeInscricao();
        if ($possuiFormulario) {
            $validator = Validator::make($request->all(), ['categoria' => 'required']);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('abrirmodalinscricao', true);
            }
            $validator = $this->validarCamposExtras($request, $categoria);
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('abrirmodalinscricao', true);
            }
        }
        if ($preInscricao){
            $inscricao = Inscricao::where('user_id', auth()->user()->id)
                ->where('evento_id', $evento->id)
                ->first();
            if ($inscricao != null) {
                $inscricao->categoria_participante_id = $request->categoria;
                $inscricao->save();
            }
        } else {
            $inscricao = new Inscricao();
            $inscricao->categoria_participante_id = $request->categoria;
            $inscricao->user_id = auth()->user()->id;
            $inscricao->evento_id = $request->evento_id;
            $inscricao->finalizada = false;
            $inscricao->save();
        }

        if ($possuiFormulario) {
            $this->salvarCamposExtras($inscricao, $request, $categoria);
        }

        auth()->user()->notify(new PreInscricao($evento, auth()->user()));

        if ($categoria != null && $categoria->valor_total != 0) {
            return redirect()->action([CheckoutController::class, 'telaPagamento'], ['evento' => $request->evento_id]);
        } else {
            $modvalidarinscricao = !$evento->formEvento->modvalidarinscricao;
            $inscricao->finalizada = $modvalidarinscricao;
            $inscricao->save();
            $message = 'Inscricao realizada, você receberá uma notificação no e-mail quando o coordenador aprovar sua inscrição.';
            if ($modvalidarinscricao) {
                $message = 'Inscrição realizada com sucesso';
                auth()->user()->notify(new InscricaoEvento($evento));
            }

            return redirect()->action([EventoController::class, 'show'], ['id' => $request->evento_id])->with('message', $message);
        }
    }

    public function voltarTela(Request $request, $id)
    {
        $atividadeExtras = null;
        $inscricao = Inscricao::find($id);

        if ($request->atividades != null) {
            $atividadeExtras = Atividade::whereIn('id', $request->atividades)->get();
        }

        $valorTotal = $request->valorTotal;

        return view('evento.nova_inscricao',
            [
                'evento' => $inscricao->evento,
                'inscricao' => $inscricao,
                'atividadesExtras' => $atividadeExtras,
                'valorTotal' => $valorTotal,
            ]
        );
    }

    public function confirmar(Request $request, $id)
    {
        $evento = Evento::find($request->evento_id);

        return view('coordenador.programacao.pagamento', compact('evento'));
    }

    public function aprovar(Inscricao $inscricao)
    {
        $evento = $inscricao->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $inscricao->finalizada = true;
        $inscricao->save();
        $inscricao->user->notify(new InscricaoAprovada($evento));
        return redirect()->back()->with('message', 'Inscrição aprovada com sucesso!');
    }

    public function validarCamposExtras(Request $request, $categoria)
    {
        $regras = [];
        foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo) {
            switch ($campo->tipo) {
                case 'email':
                    $regras['email-'.$campo->id] = $campo->obrigatorio ? 'required|string|email' : 'nullable|string|email';
                    break;
                case 'select':
                    $regras['select-'.$campo->id] = $campo->obrigatorio ? 'required|string' : 'nullable|string';
                    break;
                case 'text':
                    $regras['text-'.$campo->id] = $campo->obrigatorio ? 'required|string' : 'nullable|string';
                    break;
                case 'file':
                    $regras['file-'.$campo->id] = $campo->obrigatorio ? 'required|file|max:2000' : 'nullable|file|max:2000';
                    break;
                case 'date':
                    $regras['date-'.$campo->id] = $campo->obrigatorio ? 'required|date' : 'nullable|date';
                    break;
                case 'endereco':
                    $regras['endereco-cep-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-bairro-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-rua-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-complemento-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-cidade-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-uf-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    $regras['endereco-numero-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    break;
                case 'cpf':
                    $regras['cpf-'.$campo->id] = $campo->obrigatorio ? 'required|cpf' : 'nullable|cpf';
                    break;
                case 'contato':
                    $regras['contato-'.$campo->id] = $campo->obrigatorio ? 'required' : 'nullable';
                    break;
            }
        }

        $validator = Validator::make($request->all(), $regras);

        return $validator;
    }

    public function salvarCamposExtras($inscricao, Request $request, $categoria)
    {
        if ($request->revisandoInscricao != null) {
            foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo) {
                if ($campo->tipo == 'email' && $request->input('email-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('email-'.$campo->id)]);
                } elseif ($campo->tipo == 'text' && $request->input('text-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('text-'.$campo->id)]);
                } elseif ($campo->tipo == 'file' && $request->file('file-'.$campo->id) != null) {
                    $campoSalvo = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $campo->id)->first();
                    if ($campoSalvo != null && Storage::disk()->exists($campoSalvo->pivot->valor)) {
                        Storage::delete($campoSalvo->pivot->valor);
                    }

                    $path = Storage::putFileAs('eventos/'.$inscricao->evento->id.'/inscricoes/'.$inscricao->id.'/'.$campo->id, $request->file('file-'.$campo->id), $campo->titulo.'.pdf');

                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $path]);
                } elseif ($campo->tipo == 'date' && $request->input('date-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('date-'.$campo->id)]);
                } elseif ($campo->tipo == 'select' && $request->input('select-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('select-'.$campo->id)]);
                } elseif ($campo->tipo == 'endereco' && $request->input('endereco-cep-'.$campo->id) != null) {
                    $campoSalvo = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $campo->id)->first();
                    $endereco = Endereco::find($campoSalvo->pivot->valor);
                    $endereco->cep = $request->input('endereco-cep-'.$campo->id);
                    $endereco->bairro = $request->input('endereco-bairro-'.$campo->id);
                    $endereco->rua = $request->input('endereco-rua-'.$campo->id);
                    $endereco->complemento = $request->input('endereco-complemento-'.$campo->id);
                    $endereco->cidade = $request->input('endereco-cidade-'.$campo->id);
                    $endereco->uf = $request->input('endereco-uf-'.$campo->id);
                    $endereco->numero = $request->input('endereco-numero-'.$campo->id);
                    $endereco->update();
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $endereco->id]);
                } elseif ($campo->tipo == 'cpf' && $request->input('cpf-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('cpf-'.$campo->id)]);
                } elseif ($campo->tipo == 'contato' && $request->input('contato-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->updateExistingPivot($campo->id, ['valor' => $request->input('contato-'.$campo->id)]);
                }
            }
        } else {
            foreach ($categoria->camposNecessarios()->distinct()->orderBy('tipo')->get() as $campo) {
                if ($campo->tipo == 'email' && $request->input('email-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('email-'.$campo->id)]);
                } elseif ($campo->tipo == 'text' && $request->input('text-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('text-'.$campo->id)]);
                } elseif ($campo->tipo == 'file' && $request->file('file-'.$campo->id) != null) {
                    $extensao = $request->file('file-'.$campo->id)->getClientOriginalExtension();
                    $path = Storage::putFileAs('eventos/'.$inscricao->evento->id.'/inscricoes/'.$inscricao->id.'/'.$campo->id, $request->file('file-'.$campo->id), $campo->titulo.'.'.$extensao);

                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $path]);
                } elseif ($campo->tipo == 'date' && $request->input('date-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('date-'.$campo->id)]);
                } elseif ($campo->tipo == 'select' && $request->input('select-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('select-'.$campo->id)]);
                } elseif ($campo->tipo == 'endereco' && $request->input('endereco-cep-'.$campo->id) != null) {
                    $endereco = new Endereco();
                    $endereco->cep = $request->input('endereco-cep-'.$campo->id);
                    $endereco->bairro = $request->input('endereco-bairro-'.$campo->id);
                    $endereco->rua = $request->input('endereco-rua-'.$campo->id);
                    $endereco->complemento = $request->input('endereco-complemento-'.$campo->id);
                    $endereco->cidade = $request->input('endereco-cidade-'.$campo->id);
                    $endereco->uf = $request->input('endereco-uf-'.$campo->id);
                    $endereco->numero = $request->input('endereco-numero-'.$campo->id);
                    $endereco->save();
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $endereco->id]);
                } elseif ($campo->tipo == 'cpf' && $request->input('cpf-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('cpf-'.$campo->id)]);
                } elseif ($campo->tipo == 'contato' && $request->input('contato-'.$campo->id) != null) {
                    $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('contato-'.$campo->id)]);
                }
            }
        }
    }

    public function downloadFileCampoExtra($idInscricao, $idCampo)
    {
        $inscricao = Inscricao::findOrFail($idInscricao);
        if (auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $inscricao->evento) || auth()->user()->administradors()->exists()) {
            $caminho = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $idCampo)->first()->pivot->valor;
            if (Storage::disk()->exists($caminho)) {
                return Storage::download($caminho);
            }

            return abort(404);
        }
        return abort(403);
    }

    public function inscreverParticipante(Request $request)
    {
        $evento = Evento::find($request->evento_id);

        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        if ($request->identificador == 'email') {
            $participante = User::where('email', $request->email)->first();
        } elseif ($request->identificador == 'cpf') {
            $participante = User::where('cpf', $request->cpf)->first();
        }


        if(!$participante)
        {
            return redirect(route('inscricao.inscritos', ['evento' => $request->evento_id]))->with(['error_message' => 'Participante informado não possui cadastrado no sistema!']);
        }

        if (Inscricao::where('user_id', $participante->id)->where('evento_id', $evento->id)->exists())
        {
            return redirect(route('inscricao.inscritos', ['evento' => $request->evento_id]))->with(['error_message' => 'Participante informado já está inscrito neste evento!']);
        }

        $categoria = CategoriaParticipante::find($request->categoria);

        $possuiFormulario = $evento->possuiFormularioDeInscricao();

        if ($possuiFormulario)
        {
            $validator = Validator::make($request->all(), ['categoria' => 'required']);

            if ($validator->fails())
            {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('abrirmodalinscricao', true);
            }

            $validator = $this->validarCamposExtras($request, $categoria);

            if ($validator->fails())
            {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('abrirmodalinscricao', true);
            }
        }

        $inscricao = new Inscricao();

        $inscricao->categoria_participante_id = $request->categoria;
        $inscricao->user_id = $participante->id;
        $inscricao->evento_id = $request->evento_id;
        $inscricao->finalizada = true;

        $inscricao->save();


        if ($possuiFormulario)
        {
            $this->salvarCamposExtras($inscricao, $request, $categoria);
        }

        $participante->notify(new InscricaoEvento($evento));

        return redirect(route('inscricao.inscritos', ['evento' => $request->evento_id]))->with(['message' => 'Participante inscrito com sucesso!']);

    }

    public function alterarCategoria(Request $request, Inscricao $inscricao)
    {
        if (auth()->user()->id !== $inscricao->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($inscricao->finalizada) {
            return redirect()->back()->with(['message' => 'Não é possível alterar a categoria de uma inscrição já finalizada.', 'class' => 'danger']);
        }
        
        $validator = Validator::make($request->all(), [
            'categoria' => 'required|exists:categoria_participantes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inscricao->categoria_participante_id = $request->categoria;
        $inscricao->save();

        return redirect()->action([CheckoutController::class, 'telaPagamento'], ['evento' => $inscricao->evento_id])
                       ->with('message', 'Categoria alterada com sucesso! Prossiga com o pagamento.');
    }
}
