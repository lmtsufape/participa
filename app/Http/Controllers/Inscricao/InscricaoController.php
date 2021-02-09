<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Submissao\Evento;
use App\Models\Inscricao\Promocao;
use App\Models\Submissao\Atividade;
use App\Models\Inscricao\CupomDeDesconto;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Endereco;

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
        $this->authorize('isCoordenadorOrComissaoOrganizadora', $evento);
        
        $promocoes = Promocao::where('evento_id', $id)->get();
        $atividades = Atividade::where('eventoId', $id)->get();
        $cuponsDeDescontro = CupomDeDesconto::where('evento_id', $id)->get();
        $categoriasParticipante = CategoriaParticipante::where('evento_id', $id)->get();
        $camposDoFormulario = CampoFormulario::where('evento_id', $id)->get();

        return view('coordenador.programacao.inscricoes', ['evento'     => $evento,
                                                           'promocoes'  => $promocoes,
                                                           'atividades' => $atividades,
                                                           'cupons'     => $cuponsDeDescontro,
                                                           'categorias' => $categoriasParticipante,
                                                           'campos'     => $camposDoFormulario,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $evento = Evento::find($id);
        
        return view('evento.nova_inscricao', ['evento'              => $evento,
                                              'eventoVoltar'        => null,
                                              'valorTotalVoltar'    => null,
                                              'promocaoVoltar'      => null,
                                              'atividadesVoltar'    => null,
                                              'cupomVoltar'         => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
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
        //
    }

    public function checarDados(Request $request, $id) {
        
        // dd($request);
        $validatorData = $request->validate([
            'categoria'         => 'required',
            'promocao'          => 'nullable',
            'valorTotal'        => 'required',
            'atividades'        => 'nullable',
            'cupom'             => 'nullable',
            'atividadesPromo'   => 'nullable',
            'valorPromocao'     => 'nullable',
            'descricaoPromo'    => 'nullable',
        ]);
        
        $categoria = CategoriaParticipante::find($request->categoria);
        // dd($categoria->camposNecessarios()->orderBy('tipo')->get());
        $validatorData = $this->validarCamposExtras($request, $categoria, $validatorData);
        
        $evento = Evento::find($id);
        $valorDaInscricao = $request->valorTotal;
        $promocao = null;
        $atividades = null;
        $valorComDesconto = null;
        $cupom = CupomDeDesconto::where([['evento_id', $evento->id],['identificador', '=', $request->cupom]])->first();

        if ($request->cupom != null) {
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
                        return redirect()->back()->withErrors(['atvIguais' => "Existem atividades adicionais que já estão presentes no pacote. Logo foram removidas."])->withInput($validatorData);
                    }
                }
            }
            $atividades = Atividade::whereIn('id', $request->atividades)->get();
        }

        $inscricao = new Inscricao();
        $inscricao->user_id = auth()->user()->id;
        $inscricao->evento_id = $evento->id;
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

        return view('evento.revisar_inscricao', ['evento'           => $evento,
                                                'valor'             => $valorDaInscricao,
                                                'promocao'          => $promocao,
                                                'atividades'        => $atividades,
                                                'cupom'             => $cupom,
                                                'valorComDesconto'  => $valorComDesconto,
                                                'inscricao'         => $inscricao]);
    }

    public function voltarTela(Request $request, $id) {
        // dd($request);

        $evento = Evento::find($request->evento_id);
        $valorTotal = $request->valorTotal;
        $promocao = null;
        $atividades = null;
        $cupom = null;

        if ($request->promocao_id != null) {
            $promocao = Promocao::find($request->promocao_id);             
        }

        if ($request->atividades != null && count($request->atividades) > 0) {
            $atividades = Atividade::whereIn('id', $request->atividades)->get();
        }

        if ($request->cupom != null) {
            $cupom = CupomDeDesconto::find($request->cupom);
        }

        return view('evento.nova_inscricao', ['evento'              => $evento,
                                              'eventoVoltar'        => $evento,
                                              'valorTotalVoltar'    => $valorTotal,
                                              'promocaoVoltar'      => $promocao,
                                              'atividadesVoltar'    => $atividades,
                                              'cupomVoltar'         => $cupom]);
    }

    public function confirmar(Request $request, $id) {

        $evento = Evento::find($request->evento_id);


        return view('coordenador.programacao.pagamento', compact('evento'));
    }

    public function validarCamposExtras(Request $request, $categoria, $validate) {
        foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo) {
            switch ($campo->tipo) {
                case "email": 
                    $validatorData = $request->validate([
                        'email-'.$campo->id => $campo->obrigatorio ? 'required|string|email' : 'nullable|string|email',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "text":
                    $validatorData = $request->validate([
                        'text-'.$campo->id => $campo->obrigatorio ? 'required|string' : 'nullable|string',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "file":
                    $validatorData = $request->validate([
                        'file-'.$campo->id => $campo->obrigatorio ? 'required|file|mimes:pdf|max:2000' : 'nullable|file|mimes:pdf|max:2000',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "date":
                    $validatorData = $request->validate([
                        'date-'.$campo->id => $campo->obrigatorio ? 'required|date' : 'nullable|date',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "endereco":
                    $validatorData = $request->validate([
                        'endereco-cep-'.$campo->id          => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-bairro-'.$campo->id       => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-rua-'.$campo->id          => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-complemento-'.$campo->id  => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-cidade-'.$campo->id       => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-uf-'.$campo->id           => $campo->obrigatorio ? 'required' : 'nullable',
                        'endereco-numero-'.$campo->id       => $campo->obrigatorio ? 'required' : 'nullable',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "cpf":
                    $validatorData = $request->validate([
                        'cpf-'.$campo->id => $campo->obrigatorio ? 'required|cpf' : 'nullable|cpf',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
                case "contato":
                    $validatorData = $request->validate([
                        'contato-'.$campo->id => $campo->obrigatorio ? 'required|telefone' : 'nullable|telefone',
                    ]);
                    $validate = array_merge($validate, $validatorData);
                    break;
            }
        }
        return $validate;
    }

    public function salvarCamposExtras($inscricao, Request $request, $categoria) {
        foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo) {
            
            if ($campo->tipo == "email" && $request->input('email-'.$campo->id) != null) {
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('email-'.$campo->id)]);
            
            } else if ($campo->tipo == "text" && $request->input('text-'.$campo->id) != null) {
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('text-'.$campo->id)]);
            
            } else if ($campo->tipo == "file" && $request->file("file-".$campo->id) != null) {
                
                $path = Storage::putFileAs('public/eventos/'.$inscricao->evento->id.'/inscricoes/'.$inscricao->id.'/'.$campo->id, $request->file('file-'.$campo->id), $campo->titulo.".pdf");

                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $path]);
            
            } else if ($campo->tipo == "date" && $request->input('date-'.$campo->id) != null) {
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('date-'.$campo->id)]);
            
            } else if ($campo->tipo == "endereco" && $request->input('endereco-cep-'.$campo->id) != null) {
                $endereco               = new Endereco();
                $endereco->cep          = $request->input('endereco-cep-'.$campo->id);
                $endereco->bairro       = $request->input('endereco-bairro-'.$campo->id);      
                $endereco->rua          = $request->input('endereco-rua-'.$campo->id);         
                $endereco->complemento  = $request->input('endereco-complemento-'.$campo->id);  
                $endereco->cidade       = $request->input('endereco-cidade-'.$campo->id);       
                $endereco->uf           = $request->input('endereco-uf-'.$campo->id);          
                $endereco->numero       = $request->input('endereco-numero-'.$campo->id);
                $endereco->save();
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $endereco->id]);
            
            } else if ($campo->tipo == "cpf" && $request->input('cpf-'.$campo->id) != null) {
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('cpf-'.$campo->id)]);
            
            } else if ($campo->tipo == "contato" && $request->input('contato-'.$campo->id) != null) {
                $inscricao->camposPreenchidos()->attach($campo->id, ['valor' => $request->input('contato-'.$campo->id)]);
            }
        }
    }

    public function downloadFileCampoExtra($idInscricao, $idCampo) {
        $inscricao = Inscricao::find($idInscricao);
        $caminho = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $idCampo)->first()->pivot->valor;
        if (Storage::disk()->exists($caminho)) {
            return Storage::download($caminho);
        }
        return abort(404);
    }
}
