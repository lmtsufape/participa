<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\PalestranteStoreRequest;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\Palestrante;
use Illuminate\Http\Request;

class PalestranteController extends Controller
{
    public function index(Request $request) {
        $evento = Evento::find($request->eventoId);
        $palestras = $evento->palestras;
        return view('coordenador.palestrante.index', compact('palestras', 'evento'));
    }

    public function create(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        return view('coordenador.palestrante.create', compact('evento'));
    }

    public function store(PalestranteStoreRequest $request)
    {
        $validated = $request->validated();
        $titulo = $validated['titulo'];
        $nomes = $validated['nomeDoPalestrante'];
        $emails = $validated['emailDoPalestrante'];
        $palestra = Palestra::create(['titulo' => $titulo, 'evento_id' => $validated['eventoId']]);
        foreach ($nomes as $index => $nome) {
            Palestrante::create(['nome' => $nome, 'email' => $emails[$index], 'palestra_id' => $palestra->id]);
        }
        return redirect()->route('coord.palestrantes.index', ['eventoId' => $validated['eventoId']]);
    }

    public function update(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $palestra = Palestra::find($request->idPalestra);
        $palestra->titulo = $request->titulo;
        $palestra->save();
        $palestrantesCadastrados = $palestra->palestrantes()->pluck('id')->all();
        Palestrante::destroy(array_diff($palestrantesCadastrados, $request->idPalestrante));

        // if: tem palestrantes para atualizar
        if ($request->idPalestrante != null && count($request->idPalestrante) > 0) {
            for ($i = 0; $i < count($palestrantesCadastrados); $i++) {
                if (in_array($palestrantesCadastrados[$i], $request->idPalestrante)) {
                    $key = array_search($palestrantesCadastrados[$i], $request->idPalestrante);
                    $palestrante = Palestrante::find($palestrantesCadastrados[$i]);
                    $palestrante->nome            = $request->nomeDoPalestrante[$key];
                    $palestrante->email           = $request->emailDoPalestrante[$key];
                    $palestrante->save();
                }
            }
            for ($i = 0; $i < count($request->idPalestrante); $i++) {
                if ($request->idPalestrante[$i] == 0) {
                    $palestrante = new Palestrante();
                    $palestrante->nome        = $request->nomeDoPalestrante[$i];
                    $palestrante->email       = $request->emailDoPalestrante[$i];
                    $palestrante->palestra_id = $palestra->id;
                    $palestrante->save();
                }
            }
        // else: só cadastrar novos palestrantes, não tem nenhum para atualizar
        } else {
            if ($request->nomeDoPalestrante != null) {
                for ($i = 0; $i < count($request->nomeDoPalestrante); $i++) {
                    $palestrante = new Palestrante();
                    $palestrante->nome        = $request->nomeDoPalestrante[$i];
                    $palestrante->email       = $request->emailDoPalestrante[$i];
                    $palestrante->palestra_id = $palestra->id;
                    $palestrante->save();
                }
            }
        }
        return redirect()->route('coord.palestrantes.index', ['eventoId' => $request->eventoId]);
    }

    public function destroy(Request $request, Palestra $palestra)
    {
        $evento = $palestra->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        Palestrante::destroy($palestra->palestrantes()->pluck('id'));
        $palestra->delete();
        return redirect()->route('coord.palestrantes.index', ['eventoId' => $evento->id]);
    }
}
