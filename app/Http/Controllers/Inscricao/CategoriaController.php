<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaParticipanteRequest;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\LinksPagamento;
use App\Models\Inscricao\ValorCategoria;
use App\Models\Submissao\Evento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaParticipanteRequest $request)
    {

        $validateData = $request->validated();

        $evento = Evento::find($request->evento_id);
        $categoria = new CategoriaParticipante();
        $categoria->evento_id = $evento->id;
        $categoria->nome = $validateData['nome'];
        $categoria->descricao = $validateData['descricao'];
        $categoria->valor_total = $validateData['valor_total'];
        $categoria->permite_submissao = $request->boolean('permite_submissao');
        $categoria->permite_inscricao = $request->boolean('permite_inscricao');
        $categoria->limite_inscricao = $request->input('limite_inscricao');
        $categoria->save();
        $categoria->camposNecessarios()->attach($evento->camposFormulario);


        if (isset($request->linkPagamento)) {
            $qtdeLinks = count($request->linkPagamento);
            for ($i = 0; $i < $qtdeLinks; $i++) {
                $link = new LinksPagamento();
                $link->valor = $request->valorLink[$i];
                $link->link = $request->linkPagamento[$i];
                $link->dataInicio = $request->dataInicioLink[$i];
                $link->dataFim = $request->dataFinalLink[$i];
                $link->categoria_id = $categoria->id;
                $link->save();
            }
        }



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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());

        if(isset($request->linkIdExcluir)){
            for ($i = 0; $i < count($request->linkIdExcluir); $i++) {
                $link = LinksPagamento::where('link', $request->linkIdExcluir[$i])->first();
                $link->delete();
            }
        }

        $categoria = CategoriaParticipante::find($id);

        if(isset($request->linkPagamento)){
            for ($i = 0; $i < count($request->linkPagamento); $i++) {

                $link = LinksPagamento::where('link', $request->linkPagamento[$i])->first();

                if (!$link) {

                    $novoLink = new LinksPagamento();
                    $novoLink->valor = $request->valorLink[$i];
                    $novoLink->link = $request->linkPagamento[$i];
                    $novoLink->dataInicio = $request->dataInicioLink[$i];
                    $novoLink->dataFim = $request->dataFinalLink[$i];
                    $novoLink->categoria_id = $categoria->id;

                    $novoLink->save();
                }
            }
        }


        $categoria->nome = $request->input("nome_{$categoria->id}");
        $categoria->valor_total = $request->input("valor_total_{$categoria->id}");
        $categoria->descricao = $request->input("descricao");
        $categoria->limite_inscricao = $request->input("limite_inscricao_{$categoria->id}");
        $categoria->permite_submissao = $request->boolean('permite_submissao_' . $categoria->id);
        $categoria->permite_inscricao = $request->boolean('permite_inscricao_' . $categoria->id);
        $categoria->update();

        if ($request->input('tipo_valor_' . $categoria->id) != null) {
            $categoria->valores()->whereNotIn('id', array_keys($request->input('tipo_valor_' . $categoria->id)))->delete();
            foreach ($request->input('tipo_valor_' . $categoria->id) as $key => $tipo_valor) {
                ValorCategoria::updateOrCreate(
                    ['id' => $key, 'categoria_participante_id' => $categoria->id],
                    [
                        'porcentagem' => $tipo_valor == 'porcentagem',
                        'valor' => $request->input('valorDesconto_' . $categoria->id)[$key],
                        'inicio_prazo' => $request->input('inícioDesconto_' . $categoria->id)[$key],
                        'fim_prazo' => $request->input('fimDesconto_' . $categoria->id)[$key],
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
        $linkPagamento = LinksPagamento::where('categoria_id', $id)->get();
        if ($linkPagamento) {
            for ($i = 0; $i < $linkPagamento->count(); $i++) {
                $linkParaApagar = LinksPagamento::find($linkPagamento[$i]->id);
                $linkParaApagar->delete();
            }
        }

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

    public function destroyLink($id)
    {
        // dd('chegou: ',$id);
        $linkParaApagar = LinksPagamento::find($id);
        $linkParaApagar->delete();
        return redirect()->back()->with(['mensagem' => 'Link de pagamento excluido com sucesso!']);
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
