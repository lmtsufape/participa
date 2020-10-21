<?php

namespace App\Http\Controllers;

use App\Atribuicao;
use Illuminate\Http\Request;
use App\Evento;
use App\Revisor;
use App\User;
use App\Trabalho;
use App\Area;
use App\Mail\EmailLembrete;
use Illuminate\Support\Facades\Mail;

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

      $revisor = Revisor::find($request->revisorId);
      $trabalho = Trabalho::find($request->trabalhoId);

      $revisor->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atribuicao  $atribuicao
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function distribuicaoAutomatica(Request $request){
      $this->authorize('isCoordenadorOrComissao', $evento);

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
          $revisoresArea[$i]->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
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
        'área'                       => ['required', 'integer', 'min:1'],
        'numeroDeRevisoresPorTrabalho' => ['required', 'integer']
      ]);

      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenadorOrComissao', $evento);


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
          //checar se ja existe atribuicao para esse revisor se sim entao vai pro proximo
          $atribuicao = $revisoresArea[$i]->trabalhosAtribuidos()->where('trabalho_id', $trabalho->id)->get();
          if($atribuicao != null && count($atribuicao) > 0){
            $i++;
            if($i == $numRevisores){
              $i = 0;
            }
            continue;
          }
          // atribui para um revisor
          $revisoresArea[$i]->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
          $aux = Revisor::find($revisoresArea[$i]->id);
          $aux->correcoesEmAndamento = $aux->correcoesEmAndamento + 1;
          $aux->save();

          $trabalho = Trabalho::find($trabalho->id);
          $trabalho->avaliado = 'processando';
          $trabalho->save();

          $i++;
          if($i == $numRevisores){
            $i = 0;
          }
        }
      }

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function distribuicaoManual(Request $request){
      $validatedData = $request->validate([
        'eventoId'  => ['required', 'integer'],
        'trabalhoId'=> ['required', 'integer'],
        'revisorId' => ['required', 'integer']
      ]);

      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);

      $trabalho = Trabalho::find($request->trabalhoId);
      $trabalho->avaliado = 'processando';
      $trabalho->save();

      $revisor = Revisor::find($request->revisorId);
      $revisor->trabalhosAtribuidos()->attach($trabalho->id, ['confirmacao' => false, 'parecer' => 'processando']);
      $revisor->correcoesEmAndamento = $revisor->correcoesEmAndamento + 1;
      $revisor->save();

      $subject = "Trabalho atribuido";
      $informacoes = $trabalho->titulo;
      Mail::to($revisor->user->email)
            ->send(new EmailLembrete($revisor->user, $subject, $informacoes));

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function deletePorRevisores(Request $request){
      $validatedData = $request->validate([
        'eventoId'    => ['required', 'integer'],
        'trabalhoId'  => ['required', 'integer'],
        'revisores.*' => ['required', 'integer']
      ]);

      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);

      foreach ($request->revisores as $key) {
        $revisor = Revisor::find($key);
        $atribuicao = $revisor->trabalhosAtribuidos()->where('trabalho_id', $request->trabalhoId)->get();
        if($atribuicao != null && count($atribuicao) > 0){
          $atribuicao->delete();

          $trabalho = Trabalho::find($request->trabalhoId);
          $trabalho->avaliado = 'nao';
          $trabalho->save();

          $revisor = Revisor::find($key);
          $revisor->correcoesEmAndamento = $revisor->correcoesEmAndamento - 1;
          $revisor->save();
        }

      }

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }
}
