<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\ValorCategoria;
use App\Models\Submissao\Evento;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $validateData = $request->validate(
            [
                'nome'                  => 'required',
                'valor_total'           => 'required|numeric|min:0',
                'tipo_valor.*'          => 'nullable',
                'valorDesconto.*'       => 'required_with:tipo_valor.*|numeric|min:0',
                'inícioDesconto.*'      => 'required_with:tipo_valor.*|date',
                'fimDesconto.*'         => 'required_with:tipo_valor.*|date|after:inícioDesconto.*',
            ],
            [
                'valor_total.min' => 'Digite um valor positivo ou 0 para gratuito.',
            ],
            [
                'valorDesconto.*' => 'O valor',
            ]
        );

        $categoria = new CategoriaParticipante();
        $categoria->evento_id = $evento->id;
        $categoria->nome = $validateData['nome'];
        $categoria->valor_total = $validateData['valor_total'];
        $categoria->save();

        if ($request->has('tipo_valor')) {
            foreach ($request->tipo_valor as $key => $tipo_valor) {
                $valor = new ValorCategoria();
                $valor->porcentagem = $tipo_valor == 'porcentagem';
                $valor->valor = $validateData['valorDesconto'][$key];
                $valor->inicio_prazo = $validateData['inícioDesconto'][$key];
                $valor->fim_prazo = $validateData['fimDesconto'][$key];
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
        $categoria = CategoriaParticipante::find($id);
        $evento = $categoria->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $request->validate(
            [
                'editarCategoria'  => 'required',
                "nome_{$categoria->id}"             => 'required',
                "valor_total_{$categoria->id}"      => 'required|numeric|min:0',
                "tipo_valor_{$categoria->id}.*"     => 'nullable',
                "valorDesconto_{$categoria->id}.*"  => "required_with:tipo_valor_{$categoria->id}.*|numeric|min:0",
                "inícioDesconto_{$categoria->id}.*" => "required_with:tipo_valor_{$categoria->id}.*|date",
                "fimDesconto_{$categoria->id}.*"    => "required_with:tipo_valor_{$categoria->id}.*|date|after:inícioDesconto_{$categoria->id}.*",
            ],
            [
                "valorDesconto_[{$categoria->id}].*" => 'Digite um valor positivo ou 0 para gratuito.',
            ],
            [
                "valorDesconto_{$categoria->id}.*" => 'O valor',
            ]
        );

        $categoria->nome = $request->input("nome_{$categoria->id}");
        $categoria->valor_total = $request->input("valor_total_{$categoria->id}");
        $categoria->update();

        if ($request->input('tipo_valor_'.$categoria->id) != null) {
            $categoria->valores()->whereNotIn('id', array_keys($request->input('tipo_valor_'.$categoria->id)))->delete();
            foreach ($request->input('tipo_valor_'.$categoria->id) as $key => $tipo_valor) {
                ValorCategoria::updateOrCreate(
                    ['id' => $key, 'categoria_participante_id' => $categoria->id],
                    [
                        'porcentagem' => $tipo_valor == 'porcentagem',
                        'valor' => $request->input('valorDesconto_'.$categoria->id)[$key],
                        'inicio_prazo' => $request->input('inícioDesconto_'.$categoria->id)[$key],
                        'fim_prazo' => $request->input('fimDesconto_'.$categoria->id)[$key],
                    ]
                );
            }
        }

        return redirect()->back()->with(['mensagem' => 'Categoria de participante atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria = CategoriaParticipante::find($id);
        $evento = $categoria->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $qt_inscricoes = Inscricao::where('categoria_participante_id', $id)->count();
        if ($qt_inscricoes > 0) {
            return redirect()->back()->with(['error' => 'Categoria não pode ser excluida, existem inscrições realizadas.']);
        }
        $qt_inscricoes = $categoria->camposNecessarios()->count();
        if ($qt_inscricoes > 0) {
            return redirect()->back()->with(['error' => 'Categoria não pode ser excluida, existem campos criados para essa categoria.']);
        }
        $categoria->valores()->delete();
        $categoria->delete();

        return redirect()->back()->with(['mensagem' => 'Categoria excluida com sucesso!']);
    }

    public function valorAjax(Request $request)
    {
        $categoria = CategoriaParticipante::find($request->categoria_id);
        $hoje = Carbon::now('America/Recife')->subHours(3);
        foreach ($categoria->valores as $lote) {
            if ($hoje > Carbon::create($lote->inicio_prazo) && $hoje < Carbon::create($lote->fim_prazo)) {
                if ($lote->porcentagem) {
                    $valor = $categoria->valor_total - ($categoria->valor_total * $lote->valor / 100);

                    return response()->json(collect(['valor' => $valor]));
                } else {
                    $valor = $categoria->valor_total - $lote->valor;

                    return response()->json(collect(['valor' => $valor]));
                }
            }
        }

        return response()->json(collect(['valor' => $categoria->valor_total]));
    }
}
