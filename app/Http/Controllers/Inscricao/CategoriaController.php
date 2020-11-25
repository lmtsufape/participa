<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\ValorCategoria;
use App\Models\Submissao\Evento;

class CategoriaController extends Controller
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
        // dd($request);
        $evento = Evento::find($request->evento_id);
        $validateData = $request->validate([
            'nome'                  => 'required',
            'valor_total'           => 'required',
            'tipo_valor.*'          => 'nullable',
            'valorDesconto.*'       => 'required_with:tipo_valor.*',  
            'inícioDesconto.*'      => 'required_with:tipo_valor.*|date',
            'fimDesconto.*'         => 'required_with:tipo_valor.*|date|after:inícioDesconto.*',
        ]);

        $categoria = new CategoriaParticipante();
        $categoria->evento_id   = $evento->id;
        $categoria->nome        = $request->nome;
        $categoria->valor_total = $request->valor_total;
        $categoria->save();

        if ($request->tipo_valor != null) {
            foreach ($request->tipo_valor as $key => $tipo_valor) {
                $valor = new ValorCategoria();
                if ($request->tipo_valor[$key] == "porcentagem") {
                    $valor->porcentagem = true;
                } else {
                    $valor->porcentagem = false;
                }
                $valor->valor                     = $request->valorDesconto[$key];
                $valor->inicio_prazo              = $request->input('inícioDesconto')[$key];
                $valor->fim_prazo                 = $request->fimDesconto[$key];
                $valor->categoria_participante_id = $categoria->id;
                $valor->save();
            }
        }

        return redirect()->back()->with(['mensagem' => 'Categoria criada com sucesso!']);
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
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // LEMBRETE
        //checar se está aplicado em alguma inscrição futuramente
        $categoria = CategoriaParticipante::find($id);

        foreach ($categoria->valores as $valor) {
            $valor->delete();
        }

        $categoria->delete();

        return redirect()->back()->with(['mensagem' => 'Categoria excluida com sucesso!']);
    }
}
