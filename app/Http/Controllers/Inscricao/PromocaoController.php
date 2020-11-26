<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscricao\Promocao;
use App\Models\Inscricao\Lote;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Submissao\Evento;

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
            'novaPromocao'          => 'required',
            'identificador'         => 'required',
            'valor'                 => 'required',
            'descrição'             => 'nullable|max:1000',
            'dataDeInício.*'        => 'required|date',
            'dataDeFim.*'           => 'required|date|after:dataDeInício.*',
            'disponibilidade.*'     => 'required',
            'atividades.*'          => 'nullable', 
            'para_todas_categorias' => 'nullable', 
            'categorias.*'          => 'nullable',  
        ]);

        if ($request->para_todas_categorias == null && $request->categorias == null) {
            return redirect()->back()->withErrors(['errorCategorias' => 'Seleciona as categorias que o pacote será exibido.'])->withInput($validadeData);
        }
        
        $promocao = new Promocao();
        $promocao->identificador = $request->identificador;
        $promocao->evento_id     = $request->evento_id;
        $promocao->descricao     = $request->input('descrição'); 
        $promocao->valor         = $request->valor;
        $promocao->save();

        if ($request->para_todas_categorias == "on") {
            $categorias = CategoriaParticipante::where('evento_id', $evento->id)->get();

            foreach ($categorias as $categoria) {
                $promocao->categorias()->attach($categoria->id);
            }
        } else if ($request->categorias != null && count($request->categorias) > 0) {
            foreach ($request->categorias as $categoria) {
                $promocao->categorias()->attach($categoria);
            }
        }

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

        return redirect()->back()->with(['mensagem' => 'Pacote salvo com sucesso!']);
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
        // dd($request);
        $evento = Evento::find($request->evento_id);
        $promocao = Promocao::find($id);

        $validateData = $request->validate([
            'editarPromocao'                        => 'required',
            'identificador_'.$promocao->id          => 'required',
            'valor_'.$promocao->id                  => 'required',
            'descrição_'.$promocao->id              => 'nullable',
            'dataDeInício_'.$promocao->id.'.*'      => 'required|date',
            'dataDeFim_'.$promocao->id.'.*'         => 'required|date|after:dataDeInício_'.$promocao->id.'.*',
            'disponibilidade_'.$promocao->id.'.*'   => 'required',
            'atividades_'.$promocao->id.'.*'        => 'nullable',
            'para_todas_categorias_'.$promocao->id  => 'nullable',
            'categorias_'.$promocao->id.'.*'        => 'nullable',
        ]);

        if ($request->input('para_todas_categorias_'.$promocao->id) == null && $request->input('categorias_'.$promocao->id) == null) {
            return redirect()->back()->withErrors(['errorCategorias_'.$promocao->id => 'Seleciona as categorias que o pacote será exibido.'])->withInput($validateData);
        }


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
        
        foreach ($promocao->categorias as $categoria) {
            if(!$promocao->categorias()->detach($categoria->id)) {
                abort(500);
            }
        }

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

    public function atividades(Request $request) {
        if ($request->id != null) {
            $promocao = Promocao::find($request->id);
            $promo = ['promocao_id' => $promocao->id, 'valorPromo' => $promocao->valor, 'descricao' => $promocao->descricao];

            $atividades = collect();

            $atividades->push($promo);
            foreach ($promocao->atividades as $atv) {
                $atividade = [
                    'id'        => $atv->id,
                    'titulo'    => $atv->titulo,
                    'tipo'      => $atv->tipoAtividade->descricao,
                    'valor'     => $atv->valor,
                    'local'     => $atv->local,
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
