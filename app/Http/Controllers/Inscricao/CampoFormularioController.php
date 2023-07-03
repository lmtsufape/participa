<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $validateData = $request->validate([
            'criarCampo' => 'required',
            'titulo_do_campo' => 'required',
            'tipo_campo' => 'required',
            'categoria.*' => 'nullable',
        ]);

        if ($request->para_todas == null && $request->categoria == null) {
            return redirect()->back()->withErrors(['erroCategoria' => 'Escolha a categoria que o campo será exibido.'])->withInput($validateData);
        }

        $campo = new CampoFormulario();
        $campo->titulo = $request->titulo_do_campo;
        $campo->tipo = $request->tipo_campo;
        $campo->evento_id = $evento->id;
        $campo->obrigatorio = $request->input('campo_obrigatorio') == 'on';
        $campo->save();

        if ($request->para_todas == 'on') {
            $categorias = $evento->categoriasParticipantes->pluck('id');
            $campo->categorias()->attach($categorias);
        } elseif ($request->categoria != null) {
            $categorias = CategoriaParticipante::whereIn('id', $request->categoria)->pluck('id');
            $campo->categorias()->attach($categorias);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $validateData = $request->validate([
            'campo_id' => 'required',
            'titulo_do_campo' => 'required',
            'categoria' => 'nullable',
            'categoria.*' => 'nullable',
        ]);

        if ($request->para_todas == null && $request->categoria == null) {
            return redirect()->back()->withErrors(['erroCategoriaEdit'.$id => 'Escolha a categoria que o campo será exibido.'])->withInput($validateData);
        }

        $campo = CampoFormulario::find($id);
        $campo->titulo = $request->titulo_do_campo;
        $campo->obrigatorio = $request->input('campo_obrigatório') == 'on';
        $campo->update();

        if ($request->para_todas == 'on') {
            $categorias = $evento->categoriasParticipantes->pluck('id');
            $campo->categorias()->attach($categorias);
        } elseif ($request->categoria != null) {
            $campo->categorias()->sync($request->categoria);
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
        $evento = $campo->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        if (count($campo->inscricoesFeitas) > 0) {
            return redirect()->back()->with(['excluirCampoExtra' => 'Não foi possivel excluir, há inscrições realizadas que utilizam o campo.']);
        }

        $campo->categorias()->detach($campo->categorias);
        $campo->delete();

        return redirect()->back()->with(['mensagem' => 'Campo extra deletado com sucesso!']);
    }
}
