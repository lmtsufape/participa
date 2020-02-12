<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Area;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Endereco;
use App\ComissaoEvento;
use App\User;
class EventoController extends Controller
{
    public function index()
    {
        //
        $eventos = Evento::all();
        // dd($eventos);
        return view('coordenador.home',['eventos'=>$eventos]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('evento.criarEvento');
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

        // dd($request);
        // validar datas nulas antes, pois pode gerar um bug

        if(
          $request->dataInicio == null      ||
          $request->dataFim == null         ||
          $request->inicioSubmissao == null ||
          $request->fimSubmissao == null    ||
          $request->inicioRevisao == null   ||
          $request->fimRevisao == null      ||
          $request->inicioResultado == null ||
          $request->fimResultado == null
        ){
          $validatedData = $request->validate([
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
            'valorTaxa'           => ['required', 'integer'],
            'fotoEvento'          => ['file'],
          ]);
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
          'valorTaxa'           => ['required', 'integer'],
          'fotoEvento'          => ['file'],
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
          'coordenadorId'       => Auth::user()->id,
        ]);

        // se o evento tem foto

        if($request->fotoEvento != null){
          $file = $request->fotoEvento;
          $path =  '/' . $ppc->id . '/';
          $nome = $count . ".pdf";
          Storage::putFileAs($path, $file, $nome);
          $evento->fotoEvento = $path . $nome;
          $evento->save();
        }

        // se vou me tornar coordenador do Evento

        // if($request->isCoordenador == true){
        //   $evento->coordenadorId = Auth::user()->id;
        //   $evento->save();
        // }

        $evento->coordenadorId = Auth::user()->id;
        $evento->save();

        return redirect()->route('coord.home');
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
    public function edit($id)
    {
        // dd($id);
        $evento = Evento::find($id);
        $endereco = Endereco::find($evento->enderecoId);
        return view('evento.editarEvento',['evento'=>$evento,'endereco'=>$endereco]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);
        $endereco = Endereco::find($evento->enderecoId);

        $evento->nome                 = $request->nome;
        $evento->numeroParticipantes  = $request->numeroParticipantes;
        $evento->tipo                 = $request->tipo;
        $evento->dataInicio           = $request->dataInicio;
        $evento->dataFim              = $request->dataFim;
        $evento->inicioSubmissao      = $request->inicioSubmissao;
        $evento->fimSubmissao         = $request->fimSubmissao;
        $evento->inicioRevisao        = $request->inicioRevisao;
        $evento->fimRevisao           = $request->fimRevisao;
        $evento->inicioResultado      = $request->inicioResultado;
        $evento->fimResultado         = $request->fimResultado;
        $evento->possuiTaxa           = $request->possuiTaxa;
        $evento->valorTaxa            = $request->valorTaxa;
        $evento->enderecoId           = $endereco->id;
        $evento->save();

        $endereco->rua                = $request->rua;
        $endereco->numero             = $request->numero;
        $endereco->bairro             = $request->bairro;
        $endereco->cidade             = $request->cidade;
        $endereco->uf                 = $request->uf;
        $endereco->cep                = $request->cep;
        $endereco->save();

        $eventos = Evento::all();
        return view('coordenador.home',['eventos'=>$eventos]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);
        // dd($id);
        $endereco = Endereco::find($evento->enderecoId);
        $evento->delete();
        $endereco->delete();

        return redirect()->back();
    }

    public function detalhes(Request $request){
        $evento = Evento::find($request->eventoId);
        $ComissaoEvento = ComissaoEvento::where('eventosId',$evento->id)->get();
        // dd($ComissaoEventos);
        $ids = [];
        foreach($ComissaoEvento as $ce){
          array_push($ids,$ce->userId);
        }
        $users = User::find($ids);
        // dd($users);

        $this->authorize('isCoordenador', $evento);

        $areas = Area::where('eventoId', $evento->id)->get();
        return view('coordenador.detalhesEvento', [
                                                    'evento' => $evento,
                                                    'areas'  => $areas,
                                                    'users'=>$users,
                                                  ]);
    }
}
