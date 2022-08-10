<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemoriaRequest;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Memoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemoriaController extends Controller
{
    public function create(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        return view('coordenador.memorias.create', compact('evento'));
    }

    public function index(Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $registros = $evento->memorias;

        return view('coordenador.memorias.index', compact('registros', 'evento'));
    }

    public function store(MemoriaRequest $request, Evento $evento)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validatedData = $request->validated();
        if ($request->has('arquivo')) {
            $path = $request->file('arquivo')->store("eventos/$evento->id/registros", 'public');
            if (! $path) {
                return redirect()->back('erro', 'Não foi possível salvar o arquivo enviado');
            }
            $validatedData['arquivo'] = $path;
        }
        $memoria = new Memoria($validatedData);
        $memoria->evento()->associate($evento);
        $memoria->save();

        return redirect()->back()->with(['mensagem' => 'Registro adicionado com sucesso!']);
    }

    public function update(MemoriaRequest $request, Evento $evento, Memoria $memoria)
    {
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validatedData = $request->validated();
        if ($request->has('arquivo')) {
            $path = $request->file('arquivo')->store("eventos/$evento->id/registros", 'public');
            if (! $path) {
                return redirect()->back('error', 'Não foi possível salvar o arquivo enviado');
            }
            Storage::disk('public')->delete($memoria->arquivo);
            $validatedData['arquivo'] = $path;
        }
        $memoria->fill($validatedData);
        $memoria->update();

        return redirect()->back()->with(['mensagem' => 'Registro atualizada com sucesso!']);
    }

    public function destroy(Request $request)
    {
        $evento = Evento::find($request->evento);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $memoria = Memoria::find($request->memoria);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        Storage::disk('public')->delete($memoria->arquivo);
        $memoria->delete();

        return redirect()->back()->with(['mensagem' => 'Registro excluido com sucesso!']);
    }
}
