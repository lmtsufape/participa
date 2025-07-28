<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\Lote;
use App\Models\Inscricao\Promocao;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $validadeData = $request->validate([
            'novaPromocao' => 'required',
            'identificador' => 'required',
            'valor' => 'required',
            'descrição' => 'nullable|max:1000',
            'dataDeInício.*' => 'required|date',
            'dataDeFim.*' => 'required|date|after:dataDeInício.*',
            'disponibilidade.*' => 'required',
            'atividades.*' => 'nullable',
            'para_todas_categorias' => 'nullable',
            'categorias.*' => 'nullable',
        ]);

        if ($request->valor < 0) {
            return redirect()->back()->withErrors(['valor' => 'Digite um valor positivo ou 0 para gratuito.'])->withInput($validadeData);
        }

        foreach ($request->disponibilidade as $i => $dis) {
            if ($dis < 0) {
                return redirect()->back()->withErrors(['disponibilidade.'.$i => 'Digite um valor positivo ou 0 para ilimitada.'])->withInput($validadeData);
            }
        }

        if ($request->para_todas_categorias == null && $request->categorias == null) {
            return redirect()->back()->withErrors(['errorCategorias' => 'Seleciona as categorias que o pacote será exibido.'])->withInput($validadeData);
        }

        $promocao = new Promocao();
        $promocao->identificador = $request->identificador;
        $promocao->evento_id = $request->evento_id;
        $promocao->descricao = $request->input('descrição');
        $promocao->valor = $request->valor;
        $promocao->save();

        if ($request->para_todas_categorias == '1') {
            $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

            foreach ($categorias as $categoria) {
                $promocao->categorias()->attach($categoria->id);
            }
        } elseif ($request->categorias != null && count($request->categorias) > 0) {
            foreach ($request->categorias as $categoria) {
                $promocao->categorias()->attach($categoria);
            }
        }

        foreach ($request->input('dataDeInício') as $key => $lote) {
            $lote = new Lote();
            $lote->promocao_id = $promocao->id;
            $lote->inicio_validade = $request->input('dataDeInício.'.$key);
            $lote->fim_validade = $request->input('dataDeFim.'.$key);
            if ($request->input('disponibilidade.'.$key) == 0) {
                $lote->quantidade_de_aplicacoes = -1;
            } else {
                $lote->quantidade_de_aplicacoes = $request->input('disponibilidade.'.$key);
            }
            $lote->save();
        }

        if ($request->input('atividades') != null) {
            foreach ($request->input('atividades') as $id) {
                $promocao->atividades()->attach($id);
            }
        }

        return redirect()->back()->with(['success' => 'Pacote salvo com sucesso!']);
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
        // dd($request);
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $promocao = Promocao::find($id);

        $validateData = $request->validate([
            'editarPromocao' => 'required',
            'identificador_'.$promocao->id => 'required',
            'valor_'.$promocao->id => 'required',
            'descrição_'.$promocao->id => 'nullable',
            'dataDeInício_'.$promocao->id.'.*' => 'required|date',
            'dataDeFim_'.$promocao->id.'.*' => 'required|date|after:dataDeInício_'.$promocao->id.'.*',
            'disponibilidade_'.$promocao->id.'.*' => 'required',
            'atividades_'.$promocao->id.'.*' => 'nullable',
            'para_todas_categorias_'.$promocao->id => 'nullable',
            'categorias_'.$promocao->id.'.*' => 'nullable',
        ]);

        if ($request->input('valor_'.$promocao->id) < 0) {
            return redirect()->back()->withErrors(['valor_'.$promocao->id => 'Digite um valor positivo ou 0 para gratuito.'])->withInput($validateData);
        }

        foreach ($request->input('disponibilidade_'.$promocao->id) as $i => $disp) {
            if ($disp < 0) {
                return redirect()->back()->withErrors(['disponibilidade_'.$promocao->id.'.'.$i => 'Digite um valor positivo ou 0 para ilimitada.'])->withInput($validateData);
            }
        }

        if ($request->input('para_todas_categorias_'.$promocao->id) == null && $request->input('categorias_'.$promocao->id) == null) {
            return redirect()->back()->withErrors(['errorCategorias_'.$promocao->id => 'Seleciona as categorias que o pacote será exibido.'])->withInput($validateData);
        }

        // Excluindo relações atuais

        foreach ($promocao->lotes as $lote) {
            $lote->delete();
        }
        foreach ($promocao->atividades as $atv) {
            $promocao->atividades()->detach($atv->id);
        }
        foreach ($promocao->categorias as $categoria) {
            $promocao->categorias()->detach($categoria->id);
        }

        // Atualizando dados
        $promocao->identificador = $request->input('identificador_'.$promocao->id);
        $promocao->descricao = $request->input('descrição_'.$promocao->id);
        $promocao->valor = $request->input('valor_'.$promocao->id);
        $promocao->update();

        if ($request->input('para_todas_categorias_'.$promocao->id) == '1') {
            $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

            foreach ($categorias as $categoria) {
                $promocao->categorias()->attach($categoria->id);
            }
        } elseif ($request->input('categorias_'.$promocao->id) != null && count($request->input('categorias_'.$promocao->id)) > 0) {
            foreach ($request->input('categorias_'.$promocao->id) as $categoria) {
                $promocao->categorias()->attach($categoria);
            }
        }

        foreach ($request->input('dataDeInício_'.$promocao->id) as $key => $loteRequest) {
            $lote = new Lote();
            $lote->promocao_id = $promocao->id;
            $lote->inicio_validade = $request->input('dataDeInício_'.$promocao->id.'.'.$key);
            $lote->fim_validade = $request->input('dataDeFim_'.$promocao->id.'.'.$key);
            if ($request->input('disponibilidade_'.$promocao->id.'.'.$key) == 0) {
                $lote->quantidade_de_aplicacoes = -1;
            } else {
                $lote->quantidade_de_aplicacoes = $request->input('disponibilidade_'.$promocao->id.'.'.$key);
            }
            $lote->save();
        }

        if ($request->input('atividades_'.$promocao->id) != null) {
            foreach ($request->input('atividades_'.$promocao->id) as $id) {
                $promocao->atividades()->attach($id);
            }
        }

        return redirect()->back()->with(['success' => 'Pacote atualizado com sucesso!']);
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
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $promocao->evento);
        // Lembrar de atualizar essa função para checar se a promoção
        // já foi aplicada em alguma inscrição
        $atividades = $promocao->atividades;

        foreach ($promocao->categorias as $categoria) {
            if (! $promocao->categorias()->detach($categoria->id)) {
                abort(500);
            }
        }

        foreach ($atividades as $atv) {
            if (! $promocao->atividades()->detach($atv->id)) {
                abort(500);
            }
        }

        foreach ($promocao->lotes as $lote) {
            if (! $lote->delete()) {
                abort(500);
            }
        }

        if (! $promocao->delete()) {
            abort(500);
        }

        return redirect()->back()->with(['success' => 'Promoção deletada com sucesso!']);
    }

    public function atividades(Request $request)
    {
        if ($request->id != null) {
            $promocao = Promocao::find($request->id);
            $promo = ['promocao_id' => $promocao->id, 'valorPromo' => $promocao->valor, 'descricao' => $promocao->descricao];

            $atividades = collect();

            $atividades->push($promo);
            foreach ($promocao->atividades as $atv) {
                $atividade = [
                    'id' => $atv->id,
                    'titulo' => $atv->titulo,
                    'tipo' => $atv->tipoAtividade->descricao,
                    'valor' => $atv->valor,
                    'local' => $atv->local,
                    'descricao' => $atv->descricao,
                ];
                $atividades->push($atividade);
            }

            return response()->json($atividades);
        } else {
            return abort(404);
        }
    }
}
