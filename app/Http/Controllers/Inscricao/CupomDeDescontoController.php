<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Evento;
use App\Models\Inscricao\CupomDeDesconto;

class CupomDeDescontoController extends Controller
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

        $validadeData = $request->validate([
            'identificador' => 'required',
            'quantidade'    => 'required',
            'tipo_valor'    => 'required',
            'valor'         => 'required',
            'início'        => 'required|date',
            'fim'           => 'required|date|after:início',
        ]);

        $cupomDeDesconto = new CupomDeDesconto();
        $cupomDeDesconto->evento_id             = $evento->id;
        $cupomDeDesconto->identificador         = $request->identificador;
        $cupomDeDesconto->valor                 = $request->valor;
        $cupomDeDesconto->quantidade_aplicacao  = $request->quantidade;
        $cupomDeDesconto->inicio                = $request->input('início');
        $cupomDeDesconto->fim                   =$request->fim;

        if ($request->tipo_valor == "porcentagem") {
            $cupomDeDesconto->porcentagem = true;
        } else {
            $cupomDeDesconto->porcentagem = false;
        }

        $cupomDeDesconto->save();

        return redirect()->back()->with(['mensagem' => 'Cupom salvo com sucesso!']);
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
        $cupom = CupomDeDesconto::find($id);

        // Checar se o cupom foi aplicado em alguma inscrição antes de excluir
        $cupom->delete();
        
        return redirect()->back()->with(['mensagem' => 'Cupom excluido com sucesso!']);
    }
}
