<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscricao\Promocao;
use App\Models\Inscricao\Lote;
use App\Evento;

class PromocaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrComissaoOrganizadora', $evento);

        $validadeData = $request->validate([
            'novaPromocao'      => 'required',
            'identificador'     => 'required',
            'valor'             => 'required',
            'descrição'         => 'nullable|max:1000',
            'dataDeInício.*'    => 'required|date',
            'dataDeFim.*'       => 'required|date|after:dataDeInício.*',
            'disponibilidade.*' => 'required',
            'atividades.*'      => 'nullable',    
        ]);
        
        $promocao = new Promocao();
        $promocao->identificador = $request->identificador;
        $promocao->evento_id     = $request->evento_id;
        $promocao->descricao     = $request->input('descrição'); 
        $promocao->valor         = $request->valor;
        $promocao->save();

        foreach ($request->input('dataDeInício') as $key => $lote) {
            $lote = new Lote();
            $lote->promocao_id              = $promocao->id;
            $lote->inicio_validade          = $request->input('dataDeInício.'.$key);
            $lote->fim_validade             = $request->input('dataDeFim.'.$key);
            $lote->quantidade_de_aplicacoes = $request->input('disponibilidade.'.$key);
            $lote->save();
        }

        if ($request->input('atividades') != null) {
            foreach ($request->input('atividades') as $id) {
                $promocao->atividades()->attach($id);
            }
        }

        return redirect()->back()->with(['mensagem' => 'Promoção salva com sucesso!']);
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
        $promocao = Promocao::find($id);
        $this->authorize('isCoordenadorOrComissaoOrganizadora', $promocao->evento);
        // Lembrar de atualizar essa função para checar se a promoção
        // já foi aplicada em alguma inscrição
        $atividades = $promocao->atividades;
        

        foreach ($atividades as $atv) {
            if(!$promocao->atividades()->detach($atv->id)) {
                abort(500);
            }
        }

        foreach ($promocao->lotes as $lote) {
            if (!$lote->delete()) {
                abort(500);
            }
        }

        if(!$promocao->delete()) {
            abort(500);
        }

        return redirect()->back()->with(['mensagem' => 'Promoção deletada com sucesso!']);
    }
}
