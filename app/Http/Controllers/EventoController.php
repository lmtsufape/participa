<?php

namespace App\Http\Controllers;

use App\Evento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Endereco;

class EventoController extends Controller
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
        $mytime = Carbon::now('America/Recife');
        $yesterday = Carbon::yesterday('America/Recife');
        $yesterday = $yesterday->toDateString();

        // validar datas nulas antes, pois pode gerar um bug

        if(
          $request->dataInicio == null      ||
          $request->dataFim == null         ||
          $request->inicioSubmissao == null ||
          $request->fimSubmissao == null    ||
          $request->inicioRevisao == null   ||
          $request->fimRevisao == null      ||
          $request->inicioResultado == null ||
          $request->fimResultado == null    ||
        ){
          'nome'                => ['required', 'string'],
          'numeroParticipantes' => ['required', 'integer', 'gt:0'],
          'tipo'                => ['required', 'string'],
          'dataInicio'          => ['required', 'date','after:'. $yesterday],
          'dataFim'             => ['required', 'date'],
          'inicioSubmissao'     => ['required', 'date'],
          'fimSubmissao'        => ['required', 'date'],
          'inicioRevisao'       => ['required', 'date'],
          'fimRevisao'          => ['required', 'date'],
          'inicioResultado'     => ['required', 'date'],
          'fimResultado'        => ['required', 'date'],
          'possuiTaxa'          => ['required', 'boolean'],
          'valorTaxa'           => ['required', 'integer'],
          'fotoEvento'          => ['file'],
          'isCoordenador'       => ['required', 'boolean'],
        }

        // validacao normal

        $validatedData = $request->validate([
          'nome'                => ['required', 'string'],
          'numeroParticipantes' => ['required', 'integer', 'gt:0'],
          'tipo'                => ['required', 'string'],
          'dataInicio'          => ['required', 'date', 'after:' . $yesterday],
          'dataFim'             => ['required', 'date', 'after:' . $request->dataInicio],
          'inicioSubmissao'     => ['required', 'date', 'after:' . $yesterday],
          'fimSubmissao'        => ['required', 'date', 'after:' . $request->inicioSubmissao],
          'inicioRevisao'       => ['required', 'date', 'after:' . $yesterday],
          'fimRevisao'          => ['required', 'date', 'after:' . $request->inicioRevisao],
          'inicioResultado'     => ['required', 'date', 'after:' . $yesterday],
          'fimResultado'        => ['required', 'date', 'after:' . $request->inicioResultado],
          'possuiTaxa'          => ['required', 'boolean'],
          'valorTaxa'           => ['required', 'integer'],
          'fotoEvento'          => ['file'],
          'isCoordenador'       => ['required', 'boolean'],
        ]);

        // validar endereco

        $validatedData = $request->validate([
          'rua'                 => ['required', 'string'],
          'numero'              => ['required', 'string'],
          'bairro'              => ['required', 'string'],
          'cidade'              => ['required', 'string'],
          'uf'                  => ['required', 'string'],
          'cep'                 => ['required', 'string'],
        ]);

        $endereco = Endereco::create([
          'rua'                 => $request->rua,
          'numero'              => $request->numero,
          'bairro'              => $request->bairro,
          'cidade'              => $request->cidade,
          'uf'                  => $request->uf,
          'cep'                 => $request->cep,
        ]);

        $evento = Evento::create([
          'nome'                => $request->nome,
          'numeroParticipantes' => $request->numeroParticipantes,
          'tipo'                => $request->tipo,
          'dataInicio'          => $request->dataInicio,
          'dataFim'             => $request->dataFim,
          'inicioSubmissao'     => $request->inicioSubmissao,
          'fimSubmissao'        => $request->fimSubmissao,
          'inicioRevisao'       => $request->inicioRevisao,
          'fimRevisao'          => $request->fimRevisao,
          'inicioResultado'     => $request->inicioResultado,
          'fimResultado'        => $request->fimResultado,
          'possuiTaxa'          => $request->possuiTaxa,
          'valorTaxa'           => $request->valorTaxa,
          'enderecoId'          => $endereco->id,
        ]);

        // se o evento tem foto

        if($request->fotoEvento != null){
          $file = $request->fotoEvento;
          $path =  . '/' . $ppc->id . '/';
          $nome = $count . ".pdf";
          Storage::putFileAs($path, $file, $nome);
          $evento->fotoEvento = $path . $nome;
          $evento->save();
        }

        // se vou me tornar coordenador do Evento

        if($request->isCoordenador == true){
          $evento->coordenadorId = Auth::user()->id;
          $evento->save();
        }

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $evento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $evento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $evento)
    {
        //
    }
}
