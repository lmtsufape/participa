<?php

namespace App\Http\Controllers\Submissao;

use App\Exports\PalestrasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PalestranteStoreRequest;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\Palestrante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PalestranteController extends Controller
{
    public function index(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $palestras = $evento->palestras;

        return view('coordenador.palestrante.index', compact('palestras', 'evento'));
    }

    public function create(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        return view('coordenador.palestrante.create', compact('evento'));
    }

    public function store(PalestranteStoreRequest $request)
    {
        $validated = $request->validated();
        $titulo = $validated['titulo'];
        $nomes = $validated['nomeDoPalestrante'];
        $emails = $validated['emailDoPalestrante'];
        $fotos = $request->file('fotoPalestrante');
        $palestra = Palestra::create([
            'titulo' => $titulo,
            'evento_id' => $validated['eventoId']
        ]);

        foreach ($nomes as $index => $nome) {
            $palestrante = Palestrante::create([
                'nome' => $nome,
                'email' => $emails[$index],
                'palestra_id' => $palestra->id,
            ]);

            if (isset($fotos[$index])) {
                $file = $fotos[$index];
                $nomeUnico = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $caminho = 'palestrantes/' . $palestrante->id;
                $caminhoCompleto = $file->storeAs($caminho, $nomeUnico, 'public');
                $palestrante->fotoPalestrante = $caminhoCompleto;
                $palestrante->save();
            }
        }

        return redirect()->route('coord.palestrantes.index', ['eventoId' => $validated['eventoId']]);
    }


    public function exportar(Evento $evento)
    {
        return Excel::download(new PalestrasExport($evento), $evento->nome.'.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function update(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $palestra = Palestra::find($request->idPalestra);
        $palestra->titulo = $request->titulo;
        $palestra->save();


        // --- 1. LÓGICA PARA REMOVER PALESTRANTES E SUAS FOTOS ---
        $palestrantesCadastradosIDs = $palestra->palestrantes()->pluck('id')->all();
        $palestrantesEnviadosIDs = $request->idPalestrante ?? [];
        $idsParaDeletar = array_diff($palestrantesCadastradosIDs, $palestrantesEnviadosIDs);
        if (!empty($idsParaDeletar)) {
            $palestrantesParaDeletar = Palestrante::findMany($idsParaDeletar);
            foreach ($palestrantesParaDeletar as $palestrante) {
                if ($palestrante->fotoPalestrante) {
                    Storage::disk('public')->delete($palestrante->fotoPalestrante);
                }
            }
            Palestrante::destroy($idsParaDeletar);
        }

        // --- 2. LÓGICA PARA ATUALIZAR PALESTRANTES EXISTENTES ---
        if ($request->idPalestrante != null) {
            foreach ($request->idPalestrante as $index => $id) {
                if ($id > 0) {
                    $palestrante = Palestrante::find($id);
                    if ($palestrante) {
                        $palestrante->nome = $request->nomeDoPalestrante[$index];
                        $palestrante->email = $request->emailDoPalestrante[$index];

                        if ($request->hasFile("fotoPalestrante.{$id}")) {
                            // Apaga a foto antiga se existir
                            if ($palestrante->fotoPalestrante) {
                                Storage::disk('public')->delete($palestrante->fotoPalestrante);
                            }
                            $file = $request->file("fotoPalestrante.{$id}");
                            $nomeUnico = Str::uuid() . '.' . $file->getClientOriginalExtension();
                            $caminho = 'palestrantes/' . $palestrante->id;
                            $caminhoCompleto = $file->storeAs($caminho, $nomeUnico, 'public');
                            $palestrante->fotoPalestrante = $caminhoCompleto;
                        }
                        $palestrante->save();
                    }
                }
            }
        }

        // --- 3. LÓGICA PARA ADICIONAR NOVOS PALESTRANTES ---
        $novoPalestranteFileIndex = 0;
        $novasFotos = $request->file('fotoPalestrante.0');
        if ($request->nomeDoPalestrante != null) {
            foreach ($request->nomeDoPalestrante as $index => $nome) {
                if (!isset($request->idPalestrante[$index]) || $request->idPalestrante[$index] == 0) {
                    $novoPalestrante = Palestrante::create([
                        'nome' => $nome,
                        'email' => $request->emailDoPalestrante[$index],
                        'palestra_id' => $palestra->id,
                    ]);

                    if (isset($novasFotos[$novoPalestranteFileIndex])) {
                        $file = $novasFotos[$novoPalestranteFileIndex];
                        $nomeUnico = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $caminho = 'palestrantes/' . $novoPalestrante->id;
                        $caminhoCompleto = $file->storeAs($caminho, $nomeUnico, 'public');
                        $novoPalestrante->fotoPalestrante = $caminhoCompleto;
                        $novoPalestrante->save();
                    }
                    $novoPalestranteFileIndex++;
                }
            }
        }
        return redirect()->route('coord.palestrantes.index', ['eventoId' => $request->eventoId]);
    }

    public function destroy(Request $request, Palestra $palestra)
    {
        $evento = $palestra->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        foreach ($palestra->palestrantes as $palestrante) {
            if ($palestrante->fotoPalestrante) {
                Storage::disk('public')->delete($palestrante->fotoPalestrante);
            }
        }

        $palestra->delete();

        return redirect()->route('coord.palestrantes.index', ['eventoId' => $evento->id]);
    }
}
