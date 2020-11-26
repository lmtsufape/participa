<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submissao\Evento;
use App\Models\Inscricao\Promocao;
use App\Models\Submissao\Atividade;
use App\Models\Inscricao\CupomDeDesconto;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\CampoFormulario;

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
            'promocao'          => 'nullable',
            'valorTotal'        => 'required',
            'atividades'        => 'nullable',
            'cupom'             => 'nullable',
            'atividadesPromo'   => 'nullable',
            'valorPromocao'     => 'nullable',
            'descricaoPromo'    => 'nullable',
        ]);

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
                        return redirect()->back()->withErrors(['atvIguais' => "Existem atividades adicionais que já estão presentes na promoção. Logo foram removidas."])->withInput($validatorData);
                    }
                }
            }
            $atividades = Atividade::whereIn('id', $request->atividades)->get();
        }

        return view('evento.revisar_inscricao', ['evento'           => $evento,
                                                'valor'             => $valorDaInscricao,
                                                'promocao'          => $promocao,
                                                'atividades'        => $atividades,
                                                'cupom'             => $cupom,
                                                'valorComDesconto'  => $valorComDesconto]);
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
        dd($request);
    }
}
