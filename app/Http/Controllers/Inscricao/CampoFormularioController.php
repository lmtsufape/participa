<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CampoFormularioSelect;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\ValorCampoExtra;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if (!$evento->categoriasParticipantes()->exists()) {
            return redirect()->back()->withErrors(['erroCategoria' => 'É necessário criar categoria antes de cadastrar os campos do formulário.'])->withInput($validateData);
        }

        $campo = new CampoFormulario();
        $campo->titulo = $request->titulo_do_campo;
        $campo->tipo = $request->tipo_campo;
        $campo->evento_id = $evento->id;
        $campo->obrigatorio = $request->input('campo_obrigatorio') == 'on';
        $campo->save();

        if ($request->input('tipo_campo') == 'select') {
            foreach ($request->input('select-text') as $opcao) {
                $campo->opcoes()->save(new CampoFormularioSelect(['nome' => $opcao]));
            }
        }

        if ($request->para_todas == 'on') {
            $categorias = $evento->categoriasParticipantes->pluck('id');
            $campo->categorias()->attach($categorias);
        } elseif ($request->categoria != null) {
            $categorias = CategoriaParticipante::whereIn('id', $request->categoria)->pluck('id');
            $campo->categorias()->attach($categorias);
        }

        return redirect()->back()->with(['success' => 'Campo salvo com sucesso!']);
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

        return redirect()->back()->with(['success' => 'Campo atualizado com sucesso!']);
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

        if(count($campo->inscricoesFeitas) > 0){
            $valores = DB::table('valor_campo_extras')->where('campo_formulario_id', $campo->id)->get();
            for ($i=0; $i < count($valores); $i++) {

               DB::table('valor_campo_extras')->where('id', $valores[$i]->id)->delete();
            }
        }
        if ($campo->opcoes()->exists()) {
            $campo->opcoes()->delete();
        }

        $campo->categorias()->detach($campo->categorias);
        $campo->delete();

        return redirect()->back()->with(['success' => 'Campo extra deletado com sucesso!']);
    }
}
