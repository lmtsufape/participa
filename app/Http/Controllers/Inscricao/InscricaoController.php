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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Users\User;
use App\Models\Users\Administrador;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\ProcessarInscricaoAutomaticaJob;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function inscritos(Evento $evento, Request $request)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        if ($evento->subeventos->count() > 0) {
            $subeventoIds = $evento->subeventos->pluck('id')->toArray();
            $query = Inscricao::where(function($q) use ($evento, $subeventoIds) {
                $q->where('evento_id', $evento->id)
                  ->orWhereIn('evento_id', $subeventoIds);
            });
        } else {
            $query = $evento->inscricaos();
        }

        if ($request->filled('nome')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->nome) . '%']);
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($request->email) . '%']);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'finalizada') {
                $query->where('finalizada', true);
            } elseif ($request->status === 'pendente') {
                $query->where('finalizada', false);
            }
        }

        $inscricoes = $query->orderBy('finalizada', 'desc')->paginate(50)->withQueryString();

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

        $categoriasInscricaoAutomatica = [
            'Associado - Agricultoras/es, povos e comunidades tradicionais',
            'Associado - Pessoa com Deficiência (PCD)',
        ];

        if (in_array($categoria->nome, $categoriasInscricaoAutomatica)) {
            if ($preInscricao){
                $inscricao = Inscricao::where('user_id', auth()->user()->id)->where('evento_id', $evento->id)->first();
                $inscricao->categoria_participante_id = $request->categoria;
            } else {
                $inscricao = new Inscricao();
                $inscricao->user_id = auth()->user()->id;
                $inscricao->evento_id = $request->evento_id;
                $inscricao->categoria_participante_id = $request->categoria;
            }

            $inscricao->finalizada = true;
            $inscricao->save();

            if ($possuiFormulario) {
                $this->salvarCamposExtras($inscricao, $request, $categoria);
            }

            auth()->user()->notify(new InscricaoEvento($evento));

            return redirect()->action([EventoController::class, 'show'], ['id' => $request->evento_id])->with('message', 'Inscrição realizada com sucesso!');
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

    public function inscreverParticipante(Request $request, $evento_id)
    {
        $evento = Evento::find($evento_id);

        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        if ($request->has('participantes')) {
            return $this->inscreverParticipantesColetivos($request, $evento);
        } else {
            return $this->inscreverParticipanteIndividual($request, $evento);
        }
    }

    private function inscreverParticipanteIndividual(Request $request, $evento)
    {
        if ($request->identificador == 'email') {
            $participante = User::where('email', $request->email)->first();
        } elseif ($request->identificador == 'cpf') {
            $participante = User::where('cpf', $request->cpf)->first();
        }


        if(!$participante)
        {
            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))->with(['error_message' => 'Participante informado não possui cadastrado no sistema!']);
        }

        if (Inscricao::where('user_id', $participante->id)->where('evento_id', $evento->id)->where('finalizada', true)->exists())
        {
            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))->with(['error_message' => 'Participante informado já está inscrito neste evento!']);
        }

        $preInscricaoCancelada = $this->cancelarPreInscricao($participante->id, $evento->id);

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
        $inscricao->evento_id = $evento->id;
        $inscricao->finalizada = true;

        $inscricao->save();


        if ($possuiFormulario)
        {
            $this->salvarCamposExtras($inscricao, $request, $categoria);
        }

        $participante->notify(new InscricaoEvento($evento));

        return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))->with(['message' => 'Participante inscrito com sucesso!']);
    }

    private function inscreverParticipantesColetivos(Request $request, $evento)
    {
        $participantes = $request->participantes;

        if (empty($participantes) || !is_array($participantes)) {
            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))->with(['error_message' => 'Nenhum participante foi informado.']);
        }

        $sucessos = 0;
        $erros = [];
        $preInscricoesCanceladas = 0;
        $possuiFormulario = $evento->possuiFormularioDeInscricao();

        foreach ($participantes as $index => $dadosParticipante) {
            try {
                if (empty($dadosParticipante['categoria']) || $dadosParticipante['categoria'] == 0) {
                    $erros[] = "Participante " . ($index + 1) . ": Categoria é obrigatória";
                    continue;
                }

                if (empty($dadosParticipante['email']) && empty($dadosParticipante['cpf'])) {
                    $erros[] = "Participante " . ($index + 1) . ": E-mail ou CPF é obrigatório";
                    continue;
                }

                // busca por cpf ou email, que são os 2 meios de inscrever uma pessoa
                $participante = null;
                if (isset($dadosParticipante['identificador']) && $dadosParticipante['identificador'] == 'email' && !empty($dadosParticipante['email'])) {
                    $participante = User::where('email', $dadosParticipante['email'])->first();
                } elseif (isset($dadosParticipante['identificador']) && $dadosParticipante['identificador'] == 'cpf' && !empty($dadosParticipante['cpf'])) {
                    $participante = User::where('cpf', $dadosParticipante['cpf'])->first();
                } elseif (!empty($dadosParticipante['email'])) {
                    $participante = User::where('email', $dadosParticipante['email'])->first();
                } elseif (!empty($dadosParticipante['cpf'])) {
                    $participante = User::where('cpf', $dadosParticipante['cpf'])->first();
                }

                if (!$participante) {
                    $erros[] = "Participante " . ($index + 1) . ": Não possui cadastro no sistema";
                    continue;
                }

                if (Inscricao::where('user_id', $participante->id)->where('evento_id', $evento->id)->where('finalizada', true)->exists()) {
                    $erros[] = "Participante " . ($index + 1) . " ({$participante->name}): Já está inscrito neste evento";
                    continue;
                }

                $preInscricaoCancelada = $this->cancelarPreInscricao($participante->id, $evento->id);
                if ($preInscricaoCancelada) {
                    $preInscricoesCanceladas++;
                }

                $categoria = CategoriaParticipante::find($dadosParticipante['categoria']);

                if (!$categoria) {
                    $erros[] = "Participante " . ($index + 1) . " ({$participante->name}): Categoria inválida";
                    continue;
                }

                if ($possuiFormulario) {
                    $validator = Validator::make($dadosParticipante, ['categoria' => 'required']);
                    if ($validator->fails()) {
                        $erros[] = "Participante " . ($index + 1) . " ({$participante->name}): Categoria é obrigatória";
                        continue;
                    }

                    $validator = $this->validarCamposExtras(new Request($dadosParticipante), $categoria);
                    if ($validator->fails()) {
                        $erros[] = "Participante " . ($index + 1) . " ({$participante->name}): " . implode(', ', $validator->errors()->all());
                        continue;
                    }
                }

                $inscricao = new Inscricao();
                $inscricao->categoria_participante_id = $dadosParticipante['categoria'];
                $inscricao->user_id = $participante->id;
                $inscricao->evento_id = $evento->id;
                $inscricao->finalizada = true;
                $inscricao->save();

                if ($possuiFormulario) {
                    $this->salvarCamposExtras($inscricao, new Request($dadosParticipante), $categoria);
                }

                $participante->notify(new InscricaoEvento($evento));

                $sucessos++;

            } catch (\Exception $e) {
                $erros[] = "Participante " . ($index + 1) . ": Erro interno - " . $e->getMessage();
            }
        }

        $mensagem = '';
        if ($sucessos > 0) {
            $mensagem .= "{$sucessos} participante(s) inscrito(s) com sucesso! ";
        }
        if ($preInscricoesCanceladas > 0) {
            $mensagem .= "{$preInscricoesCanceladas} pré-inscrição(ões) cancelada(s) automaticamente. ";
        }
        if (!empty($erros)) {
            $mensagem .= "Erros: " . implode('; ', $erros);
        }

        $tipoMensagem = !empty($erros) ? 'error_message' : 'message';

        return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))->with([$tipoMensagem => $mensagem]);
    }

    private function cancelarPreInscricao($user_id, $evento_id)
    {
        $preInscricao = Inscricao::where('user_id', $user_id)
                                 ->where('evento_id', $evento_id)
                                 ->where('finalizada', false)
                                 ->first();

        if ($preInscricao) {
            $this->destroy($preInscricao->id);
            return true;
        }

        return false;
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

        if ($inscricao->pagamento && $inscricao->pagamento->status !== 'approved') {
            $pagamentoAntigo = $inscricao->pagamento;
            $inscricao->pagamento_id = null;
            $inscricao->save();
            $pagamentoAntigo->delete();
        }

        $inscricao->categoria_participante_id = $request->categoria;
        $inscricao->save();

        return redirect()->action([CheckoutController::class, 'telaPagamento'], ['evento' => $inscricao->evento_id])
                       ->with('message', 'Categoria alterada com sucesso! Prossiga com o pagamento.');
    }
    public function validarRecibo($codigo)
    {
        $inscricao = Inscricao::where('codigo_validacao', $codigo)->first();

        if (!$inscricao) {
            return view('validacao.recibo_invalido');
        }

        $data = [
            'nome' => $inscricao->user->name,
            'valor' => $inscricao->pagamento ? $inscricao->pagamento->valor : 0,
            'data' => $inscricao->created_at,
            'codigo_validacao' => $inscricao->codigo_validacao,
            'evento' => $inscricao->evento->nome ?? 'Evento',
            'categoria' => $inscricao->categoria->nome ?? 'Categoria',
        ];

        return view('validacao.recibo_valido', $data);
    }

    public function recibo(Inscricao $inscricao)
    {
        try{


        if (! $inscricao->finalizada) {
            return redirect()->back()->with(['error_message' => 'Recibo disponível apenas para inscrições finalizadas.']);
        }

        if (!$inscricao->codigo_validacao) {
            $inscricao->codigo_validacao = $this->gerarCodigoValidacaoUnico();
            $inscricao->save();

        }

        $data = [
            'nome' => $inscricao->user->name,
            'valor' => $inscricao->categoria ? $inscricao->categoria->valor_total : 0,
            'data' => now(),
            'codigo_validacao' => $inscricao->codigo_validacao,
        ];


        $pdf = Pdf::loadView('inscricao.recibo_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download("recibo-{$inscricao->id}.pdf");
    } catch (\Throwable $e) {
        \Log::error('Erro ao gerar recibo', [
            'inscricao_id' => $inscricao->id ?? null,
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return redirect()->back()->with(['error_message' => 'Erro ao gerar recibo.']);
    }
    }

    private function gerarCodigoValidacaoUnico(): string
    {
        do {
            $codigo = strtoupper(substr(md5(uniqid()), 0, 16));
        } while (Inscricao::where('codigo_validacao', $codigo)->exists());

        return $codigo;
    }

    public function inscricaoAutomaticaIndex(Request $request)
    {
        $eventoId = $request->get('evento_id');
        $evento = Evento::find($eventoId);

        if (! (Gate::allows('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento) ||
               Gate::allows('isAdmin', \App\Models\Users\Administrador::class))) {
            abort(403, 'Acesso negado');
        }

        $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

        return view('coordenador.inscricoes.inscricao-automatica', compact('evento', 'categorias'));
    }

    public function inscricaoAutomaticaProcessar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls|max:10240', //10mb
            'evento_id' => 'required|exists:eventos,id',
            'categoria_id' => 'required|exists:categoria_participantes,id',
        ]);

        try {
            $evento = Evento::find($request->evento_id);
            $categoria = CategoriaParticipante::find($request->categoria_id);

            $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

            $arquivo = $request->file('arquivo');
            $caminhoArquivo = $arquivo->store('temp');
            $caminhoCompleto = storage_path('app/' . $caminhoArquivo);

            // ler a planilha
            $spreadsheet = IOFactory::load($caminhoCompleto);
            $worksheet = $spreadsheet->getActiveSheet();
            $dados = $worksheet->toArray();

            $cabecalho = array_shift($dados);

            $dados = array_filter($dados, function($linha) {
                return !empty($linha[0]) && (!empty($linha[1]) || !empty($linha[2]));
            });

            $jobId = uniqid('inscricao_', true);

            ProcessarInscricaoAutomaticaJob::dispatch($dados, $evento->id, $categoria->id, $jobId);

            \Log::info("Job despachado com ID: {$jobId}, Total de dados: " . count($dados));

            Storage::delete($caminhoArquivo);

            return redirect()->route('inscricao-automatica.progresso', ['job_id' => $jobId])
                ->with('success', 'Processamento iniciado! Acompanhe o progresso abaixo.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    private function gerarPlanilhaResultado($usuariosNaoCadastrados, $usuariosJaInscritos, $usuariosInscritosComSucesso, $erros)
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Nome');
        $worksheet->setCellValue('B1', 'CPF');
        $worksheet->setCellValue('C1', 'Email');
        $worksheet->setCellValue('D1', 'Status');

        $linha = 2;

        foreach ($usuariosNaoCadastrados as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        foreach ($usuariosJaInscritos as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        foreach ($usuariosInscritosComSucesso as $usuario) {
            $worksheet->setCellValue('A' . $linha, $usuario['nome']);
            $worksheet->setCellValue('B' . $linha, $usuario['cpf']);
            $worksheet->setCellValue('C' . $linha, $usuario['email']);
            $worksheet->setCellValue('D' . $linha, $usuario['status']);
            $linha++;
        }

        foreach ($erros as $erro) {
            $worksheet->setCellValue('A' . $linha, $erro['nome']);
            $worksheet->setCellValue('B' . $linha, $erro['cpf']);
            $worksheet->setCellValue('C' . $linha, $erro['email']);
            $worksheet->setCellValue('D' . $linha, $erro['status']);
            $linha++;
        }

        // Salvar arquivo temporário
        $caminhoArquivo = storage_path('app/temp/resultado_inscricao_' . time() . '.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($caminhoArquivo);

        return $caminhoArquivo;
    }

    public function inscricaoAutomaticaProgresso(Request $request)
    {
        $jobId = $request->get('job_id');

        if (!$jobId) {
            return redirect()->route('inscricao-automatica.index')
                ->with('error', 'ID do processamento não encontrado.');
        }

        return view('coordenador.inscricoes.inscricao-automatica-progresso', compact('jobId'));
    }

    public function inscricaoAutomaticaStatusProgresso(Request $request)
    {
        $jobId = $request->get('job_id');

        $progresso = Cache::get("inscricao_progress_{$jobId}");
        $completado = Cache::get("inscricao_completed_{$jobId}");

        \Log::info("Status request para job {$jobId}: progresso=" . ($progresso ? 'encontrado' : 'não encontrado') . ", completado=" . ($completado ? 'sim' : 'não'));

        if ($completado) {
            return response()->json([
                'completado' => true,
                'progresso' => 100,
                'dados' => $progresso
            ]);
        }

        if ($progresso) {
            return response()->json([
                'completado' => false,
                'progresso' => $progresso['progresso'] ?? 0,
                'processados' => $progresso['processados'] ?? 0,
                'total' => $progresso['total'] ?? 0
            ]);
        }

        return response()->json([
            'completado' => false,
            'progresso' => 0,
            'processados' => 0,
            'total' => 0
        ]);
    }

    public function inscricaoAutomaticaDownloadResultado(Request $request)
    {
        $jobId = $request->get('job_id');

        $dados = Cache::get("inscricao_progress_{$jobId}");

        if (!$dados) {
            return redirect()->route('inscricao-automatica.index')
                ->with('error', 'Dados do processamento não encontrados.');
        }

        $planilhaResultado = $this->gerarPlanilhaResultado(
            $dados['usuariosNaoCadastrados'] ?? [],
            $dados['usuariosJaInscritos'] ?? [],
            $dados['usuariosInscritosComSucesso'] ?? [],
            $dados['erros'] ?? []
        );

        // Limpar cache
        Cache::forget("inscricao_progress_{$jobId}");
        Cache::forget("inscricao_completed_{$jobId}");

        return response()->download($planilhaResultado, 'resultado_inscricao_automatica.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function gerenciarAlimentacao(Request $request, $evento_id)
    {
        $evento = Evento::find($evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $identificador = $request->identificador;
        $email = $request->email;
        $cpf = $request->cpf;

        if (empty($email) && empty($cpf)) {
            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                ->with(['error_message' => 'E-mail ou CPF é obrigatório.']);
        }

        try {
            $participante = null;
            if ($identificador === 'email' && !empty($email)) {
                $participante = User::where('email', $email)->first();
            } elseif ($identificador === 'cpf' && !empty($cpf)) {
                $participante = User::where('cpf', $cpf)->first();
            } else {
                if (!empty($email)) {
                    $participante = User::where('email', $email)->first();
                } elseif (!empty($cpf)) {
                    $participante = User::where('cpf', $cpf)->first();
                }
            }

            if (!$participante) {
                return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                    ->with(['error_message' => 'Usuário não encontrado no sistema.']);
            }

            $inscricao = Inscricao::where('user_id', $participante->id)
                ->where('evento_id', $evento->id)
                ->where('finalizada', true)
                ->first();

            if (!$inscricao) {
                return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                    ->with(['error_message' => "O participante {$participante->name} não está inscrito neste evento."]);
            }

            if ($inscricao->alimentacao) {
                return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                    ->with(['error_message' => "O participante {$participante->name} já possui alimentação cadastrada."]);
            }

            $inscricao->alimentacao = true;
            $inscricao->save();

            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                ->with(['message' => "Alimentação adicionada com sucesso para {$participante->name}."]);

        } catch (\Exception $e) {
            return redirect(route('inscricao.inscritos', ['evento' => $evento->id]))
                ->with(['error_message' => 'Erro ao processar: ' . $e->getMessage()]);
        }
    }


    function processarRelatorioInscricoesJSON(Request $request)
    {

        $evento_id = $request->integer('evento_id') ?: null;
        $arquivo  = $request->file('arquivo');

        $sheet = Excel::toCollection(null, $arquivo)->first();

        $linhas = $sheet->skip(1)->map(function ($row) {
            $nome        = $row['nome']        ?? $row[0] ?? null;
            $cpf         = $row['cpf']          ?? $row[1] ?? null;
            $email       = $row['email']       ?? $row[2] ?? null;
            $alimentacao = $row['alimentacao'] ?? $row[3] ?? null;

            $cpfNormalizado = $cpf ? $this->normalizarCpf($cpf) : null;

            $emailNormalizado = $email ? mb_strtolower(trim($email)) : null;


            return [
                'nome'        => trim((string) $nome),
                'cpf'         => $cpfNormalizado,
                'email'       => $emailNormalizado,
                'alimentacao' => $alimentacao,
            ];
        })->filter(fn ($l) => $l['cpf'] || $l['email'])->values();

        $cpfs = $linhas->pluck('cpf')->filter()->unique()->values();
        $emails = $linhas->pluck('email')->filter()->unique()->values();

        $users = collect();

        if ($cpfs->isNotEmpty()) {
            $usersCpf = User::query()
                ->whereIn('cpf', $cpfs)
                ->get(['id', 'cpf', 'email']);
            $users = $users->merge($usersCpf);
        }

        if ($emails->isNotEmpty()) {
            $usersEmail = User::query()
                ->whereIn('email', $emails)
                ->get(['id', 'cpf', 'email']);
            $users = $users->merge($usersEmail);
        }

        $users = $users->unique('id');

        if ($users->isNotEmpty()) {
            $userIds = $users->pluck('id');

            $users = User::query()
                ->whereIn('id', $userIds)
                ->withExists([
                    'inscricaos as tem_inscricao_confirmada' => function ($q) use ($evento_id) {
                        $q->when($evento_id, fn ($q) => $q->where('evento_id', $evento_id))
                        ->where('finalizada', true);
                    },
                ])
                ->addSelect([
                    'alimentacao' => Inscricao::select('alimentacao')
                        ->whereColumn('user_id', 'users.id')
                        ->when($evento_id, fn ($q) => $q->where('evento_id', $evento_id))
                        ->where('finalizada', true)
                        ->latest('created_at')
                        ->limit(1),
                ])
                ->get(['id', 'cpf', 'email']);
        } else {
            $users = collect();
        }


        $mapaUsuarios = collect();
        foreach ($users as $user) {
            if ($user->cpf) {
                $mapaUsuarios->put($user->cpf, $user);
            }
            if ($user->email) {
                $mapaUsuarios->put($user->email, $user);
            }
        }

        $encontrados = collect();
        $naoEncontrados = collect();

        foreach ($linhas as $linha) {
            $chave = $linha['cpf'] ?: $linha['email'];
            $u = $mapaUsuarios->get($chave);

            if ($u) {
                $encontrados->push([
                    'nome'            => $linha['nome'],
                    'cpf'             => $linha['cpf'],
                    'email'           => $linha['email'],
                    'alimentacao'     => (bool) ($u->alimentacao),
                    'usuario_existe'  => true,
                    'inscricao'       => (bool) ($u->tem_inscricao_confirmada ?? false)
                ]);
            } else {
                $naoEncontrados->push([
                    'nome'            => $linha['nome'],
                    'cpf'             => $linha['cpf'],
                    'email'           => $linha['email'],
                    'alimentacao'     => false,
                    'usuario_existe'  => false,
                    'inscricao'       => false,
                ]);
            }
        }

        return response()->json([
            'encontrados'     => $encontrados->values(),
            'nao_encontrados' => $naoEncontrados->values(),
            'total'           => [
                'encontrados'     => $encontrados->count(),
                'nao_encontrados' => $naoEncontrados->count(),
            ],
        ]);
    }

    private function normalizarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) < 11) {
            $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        }
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' .
                   substr($cpf, 3, 3) . '.' .
                   substr($cpf, 6, 3) . '-' .
                   substr($cpf, 9, 2);
        }

        return $cpf;
    }

}
