<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Evento;
use App\Models\Inscricao\Promocao;
use App\Atividade;
use App\Models\Inscricao\CupomDeDesconto;

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
        return view('coordenador.programacao.inscricoes', ['evento' => $evento,
                                                           'promocoes' => $promocoes,
                                                           'atividades' => $atividades,
                                                           'cupons' => $cuponsDeDescontro]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $evento = Evento::find($id);

        return view('evento.nova_inscricao', ['evento' => $evento]);
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
        $cupom = CupomDeDesconto::where([['evento_id', $evento->id],['identificador', '=', $request->cupom]])->first();

        if ($request->cupom != null) {
            if ($cupom != null && $cupom->porcentagem) {
                $valorDaInscricao = $valorDaInscricao - $valorDaInscricao * ($cupom->valor / 100);
            } else {
                $valorDaInscricao -= $cupom->valor;
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

        return view('evento.revisar_inscricao', ['evento'       => $evento,
                                                             'valor'        => $valorDaInscricao,
                                                             'promocao'     => $promocao,
                                                             'atividades'   => $atividades,
                                                             'cupom'        => $cupom]);
    }
}
