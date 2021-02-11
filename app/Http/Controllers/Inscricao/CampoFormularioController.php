<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Submissao\Evento;
use App\Models\Inscricao\CategoriaParticipante;

class CampoFormularioController extends Controller
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

        $validateData = $request->validate([
            'criarCampo'      => 'required',
            'titulo_do_campo' => 'required',
            'tipo_campo'      => 'required',
            'categoria.*'     => 'nullable',
        ]);

        if ($request->para_todas == null && $request->categoria == null) {
            return redirect()->back()->withErrors(['erroCategoria' => 'Escolha a categoria que o campo será exibido.'])->withInput($validateData);
        }

        $campo = new CampoFormulario();

        $campo->titulo      = $request->titulo_do_campo;
        $campo->tipo        = $request->tipo_campo;
        $campo->evento_id   = $evento->id;

        if ($request->input("campo_obrigatório") == "on") {
            $campo->obrigatorio = true;
        } else {
            $campo->obrigatorio = false;
        }
        
        $campo->save();
        
        if ($request->para_todas == "on") {
            $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

            foreach ($categorias as $categoria) {
                $campo->categorias()->attach($categoria->id);
            }
        } else if ($request->categoria != null) {
            foreach ($request->categoria as $categoria) {
                $campo->categorias()->attach($categoria);
            }
        }

        return redirect()->back()->with(['mensagem' => 'Campo salvo com sucesso!']);
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
        $evento = Evento::find($request->evento_id);

        $validateData = $request->validate([
            'campo_id'        => 'required',
            'titulo_do_campo' => 'required',
            'categoria'       => 'nullable',
            'categoria.*'     => 'nullable',
        ]);

        if ($request->para_todas == null && $request->categoria == null) {
            return redirect()->back()->withErrors(['erroCategoriaEdit'.$id => 'Escolha a categoria que o campo será exibido.'])->withInput($validateData);
        }

        $campo = CampoFormulario::find($id);

        foreach ($campo->categorias as $categoria) {
            $campo->categorias()->detach($categoria->id);
        }

        $campo->titulo      = $request->titulo_do_campo;
        if ($request->input("campo_obrigatório") == "on") {
            $campo->obrigatorio = true;
        } else {
            $campo->obrigatorio = false;
        }
        
        $campo->update();

        if ($request->para_todas == "on") {
            $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

            foreach ($categorias as $categoria) {
                $campo->categorias()->attach($categoria->id);
            }
        } else if ($request->categoria != null) {
            foreach ($request->categoria as $categoria) {
                $campo->categorias()->attach($categoria);
            }
        }

        return redirect()->back()->with(['mensagem' => 'Campo atualizado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Checar erros futuros após a criação da inscrição
        $campo = CampoFormulario::find($id);

        if(count($campo->inscricoesFeitas) > 0) {
            return redirect()->back()->with(['excluirCampoExtra' => 'Não foi possivel excluir, há inscrições realizadas que utilizam o campo.']);
        }

        foreach ($campo->categorias as $categoria) {
            $campo->categorias()->detach($categoria->id);
        }

        $campo->delete();

        return redirect()->back()->with(['mensagem' => 'Campo extra deletado com sucesso!']);
    }
}
