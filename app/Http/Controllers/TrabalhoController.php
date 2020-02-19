<?php

namespace App\Http\Controllers;

use App\Trabalho;
use App\Coautor;
use App\Evento;
use App\User;
use App\AreaModalidade;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;

class TrabalhoController extends Controller
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
        'nomeTrabalho'      => ['required', 'string',],
        'areaModalidadeId'  => ['required', 'integer'],
        'eventoId'          => ['required', 'integer'],
        'emailCoautor'      => ['string'],
      ]);

      $mytime = Carbon::now('America/Recife');
      $mytime = $mytime->toDateString();


      $autor = Auth::user();
      $evento = Evento::find($request->eventoId);
      $areaModalidade = AreaModalidade::find($request->areaModalidadeId);
      $coautores = explode(', ', $request->emailCoautor);
      $existemUsuariosCadastrados = true;
      foreach ($coautores as $key) {
        $userCoautor = User::where('email', $key)->first();
        if($userCoautor == null){
          $existemUsuariosCadastrados = false;
        }
      }
      if($existemUsuariosCadastrados == false){
        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId])
                         ->withInput(['nomeTrabalho' => $request->nomeTrabalho,
                                      'emailCoautor' => $request->emailCoautor])
                         ->withErrors(['emailNaoEncontrado' => 'E-mail(s) de coautores incorretos ou não cadastrados.']);
      }

      $trabalho = Trabalho::create([
        'titulo' => $request->nomeTrabalho,
        'autores' => '-',
        'data'  => $mytime,
        'modalidadeId'  => $areaModalidade->modalidade->id,
        'areaId'  => $areaModalidade->modalidade->id,
        'autorId' => $autor->id,
      ]);

      foreach ($coautores as $key) {
        $userCoautor = User::where('email', $key)->first();
        Coautor::create([
          'ordem' => '-',
          'autorId' => $userCoautor->id,
          'trabalhoId'  => $trabalho->id,
        ]);
      }

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function show(Trabalho $trabalho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function edit(Trabalho $trabalho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trabalho $trabalho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trabalho $trabalho)
    {
        //
    }
}
