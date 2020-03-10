<?php

namespace App\Http\Controllers;

use App\Atribuicao;
use Illuminate\Http\Request;
use App\Evento;
use App\Revisor;
use App\User;
use App\Trabalho;
use App\Area;

class AtribuicaoController extends Controller
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
        $validatedData = $request->validate([
          'revisorId'      => ['required', 'integer',],
          'trabalhoId'     => ['required', 'integer'],
        ]);

        $atribuicao = Atribuicao::create([
          'confirmacao' => false,
          'parecer'     => 'processando',
          'revisorId'   => $request->revisorId,
          'trabalhoId'  => $request->trabalhoId,
        ]);

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function show(Atribuicao $atribuicao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function edit(Atribuicao $atribuicao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Atribuicao $atribuicao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Atribuicao $atribuicao)
    {
        //
    }

    public function distribuicaoAutomatica(Request $request){
      $this->authorize('isCoordenador', $evento);

      $validatedData = $request->validate([
        'eventoId' => ['required', 'integer'],
      ]);

      $evento = Evento::find($request->eventoId);
      $areas = Area::where('eventoId', $evento->id)->get();
      $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
      $revisores = Revisor::where('eventoId', $evento->id)->get();
      $trabalhos = Trabalho::whereIn('areaId', $areasId)->get();

      foreach ($areas as $area) {
        $trabalhosArea = Trabalho::where('areaId', $area->id)->get();
        $revisoresArea = Revisor::where('areaId', $area->id)->get();
        $numRevisores = count($revisoresArea);
        $i = 0;
        foreach ($trabalhosArea as $trabalho) {
          $atribuicao = Atribuicao::create([
            'confirmacao' => false,
            'parecer'     => 'processando',
            'revisorId'   => $revisoresArea[$i]->id,
            'trabalhoId'  => $trabalho->id,
          ]);
          $i++;
          if($i == $numRevisores){
            $i = 0;
          }
        }
      }

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function distribuicaoPorArea(Request $request){
      $validatedData = $request->validate([
        'eventoId'                     => ['required', 'integer'],
        'areaId'                       => ['required', 'integer'],
        'numeroDeRevisoresPorTrabalho' => ['required', 'integer']
      ]);

      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);


      $evento = Evento::find($request->eventoId);
      $area = Area::find($request->areaId);
      $revisores = Revisor::where('areaId', $area->id)->get();
      $trabalhos = Trabalho::where('areaId', $area->id)->get();
      $trabalhosArea = Trabalho::where('areaId', $area->id)->get();
      $revisoresArea = Revisor::where('areaId', $area->id)->get();
      $numRevisores = count($revisores);
      $i = 0;
      foreach ($trabalhosArea as $trabalho) {
        for($j = 0; $j < $request->numeroDeRevisoresPorTrabalho; $j++){
          $atribuicao = Atribuicao::create([
            'confirmacao' => false,
            'parecer'     => 'processando',
            'revisorId'   => $revisoresArea[$i]->id,
            'trabalhoId'  => $trabalho->id,
          ]);
          $aux = Revisor::find($revisoresArea[$i]->id);
          $aux->correcoesEmAndamento = $aux->correcoesEmAndamento + 1;
          $aux->save();
          $i++;
          if($i == $numRevisores){
            $i = 0;
          }
        }
      }

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }
}
