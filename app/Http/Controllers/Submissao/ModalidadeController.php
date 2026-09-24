<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\modalidades\StoreModalidadeRequest;
use App\Http\Requests\modalidades\UpdateModalidadeRequest;
use App\Http\Requests\ModalidadeStoreRequest;
use App\Models\Submissao\Area;
use App\Models\Submissao\DataExtra;
use App\Models\Submissao\Evento;
use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\TipoApresentacao;
use App\UseCases\Modalidade\StoreModalidadeUseCase;
use App\UseCases\Modalidade\UpdateModalidadeUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ModalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modalidades = Modalidade::where('evento_id', $evento->id)->orderBy('ordem')->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        // $areaModalidades = AreaModalidade::whereIn('areaId', $areasId)->get();

        return view('coordenador.modalidade.index', [
            'evento' => $evento,
            'modalidades' => $modalidades,
            // 'areaModalidades'         => $areaModalidades,
        ]);
    }

    public function find(Request $request)
    {
        $modalidadeEdit = Modalidade::find($request->modalidadeId);

        return $modalidadeEdit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $areas = Area::where('eventoId', $evento->id)->get();
        $modalidades = Modalidade::where('evento_id', $evento->id)->get();

        return view('coordenador.modalidade.create', [
            'evento' => $evento,
            'areas' => $areas,
            'modalidades' => $modalidades,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModalidadeRequest $request, StoreModalidadeUseCase $useCase, $evento_id)
    {
        $evento = Evento::findOrFail($evento_id);

        $this->authorize(
            'isCoordenadorOrCoordenadorDasComissoes',
            $evento
        );

        $useCase->execute(
            $request,
            $evento
        );

        return redirect()
            ->route('coord.modalidade.index', ['eventoId' => $evento->id])
            ->with(
                'success',
                'Modalidade cadastrada com sucesso!'
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function show(Modalidade $modalidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function edit($modalidade_id)
    {
        $modalidade = Modalidade::with('evento')->find($modalidade_id);
        $evento = $modalidade->evento;

        return view('coordenador.modalidade.edit', compact('modalidade', 'evento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateModalidadeRequest $request,
        UpdateModalidadeUseCase $updateModalidade
    ) {
        $modalidade = Modalidade::with('evento')
            ->findOrFail(
                $request->route('modalidade_id')
            );

        $this->authorize(
            'isCoordenadorOrCoordenadorDasComissoes',
            $modalidade->evento
        );

        $updateModalidade->execute(
            $request,
            $modalidade
        );

        return redirect()->back()->with('success', 'Modalidade salva com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modalidade = Modalidade::find($id);
        $evento = $modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        if (count($modalidade->revisores) > 0) {
            return redirect()->back()->withErrors(['excluirModalidade' => 'Não é possível excluir, existem revisores ligados a essa modalidade.']);
        }

        if (count($modalidade->trabalho) > 0) {
            return redirect()->back()->withErrors(['excluirModalidade' => 'Não é possível excluir, existem trabalhos submetidos ligados a essa modalidade.']);
        }

        $tipoApresentacao = TipoApresentacao::where('modalidade_id', $modalidade->id)->get();
        if ($tipoApresentacao != NULL) {
            for ($i = 0; $i < count($tipoApresentacao); $i++) {
                $tipoApagar = TipoApresentacao::where('id', $tipoApresentacao[$i]->id)->first();
                $tipoApagar->delete();
            }
        }

        $modalidade->delete();

        return redirect()->back()->with(['success' => 'Modalidade excluida com sucesso!']);
    }

    public function downloadRegras($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->regra)) {
            $file = Storage::get($modalidade->regra);
            $tipo = Storage::mimeType($modalidade->regra);

            $response = Response::make($file, 200, [
                'Content-Type' => $tipo,
                'Content-Disposition' => 'inline; filename=' . $modalidade->nome . ' regras.pdf',
            ]);

            return $response;
        }

        return abort(404);
    }

    public function downloadInstrucoes(Modalidade $modalidade)
    {
        if (Storage::exists($modalidade->instrucoes)) {
            return Storage::download($modalidade->instrucoes);
        }

        return abort(404);
    }

    public function downloadModelos($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->modelo_apresentacao)) {
            return Storage::download($modalidade->modelo_apresentacao, 'Modelos.' . explode('.', $modalidade->modelo_apresentacao)[1]);
        }

        return abort(404);
    }

    public function downloadTemplate($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->template)) {
            return Storage::download($modalidade->template, 'Template.' . explode('.', $modalidade->template)[1]);
        }

        return abort(404);
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $item) {
            Modalidade::where('id', $item['id'])
                ->update(['ordem' => $item['position']]);
        }
        return response()->json(['status' => 'ok']);
    }
}
