<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArquivoInfoRequest;
use App\Http\Requests\UpdateArquivoInfoRequest;
use App\Models\Submissao\ArquivoInfo;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivoInfoController extends Controller
{
    public function index(Evento $evento)
    {
        return view('coordenador.evento.arquivoInfo.arquivoInfo', ['evento' => $evento]);
    }

    public function store(StoreArquivoInfoRequest $request, Evento $evento)
    {
        $data = $request->validated();
        $arquivo = new ArquivoInfo();
        $arquivo->evento_id = $evento->id;
        $arquivo->nome = $data['nome'];
        $name = $data['arquivo']->getClientOriginalName();
        $path = 'eventos/'.$evento->id.'/arquivos/';
        Storage::disk('public')->putFileAs($path, $data['arquivo'], $name);
        $arquivo->path = $path.$name;
        $arquivo->save();

        return redirect()->back()->with(['success' => 'Arquivo adicionado com sucesso!']);
    }

    public function delete(ArquivoInfo $arquivoInfo)
    {
        Storage::disk('public')->delete($arquivoInfo->path);
        $arquivoInfo->delete();

        return redirect()->back()->with(['success' => 'Arquivo deletado com sucesso!']);
    }

    public function update(UpdateArquivoInfoRequest $request, ArquivoInfo $arquivoInfo)
    {
        $data = $request->validated();
        $arquivoInfo->nome = $data['nome'];
        if (isset($data['arquivo']) && Storage::disk('public')->exists($arquivoInfo->path)) {
            Storage::disk('public')->delete($arquivoInfo->path);
            $name = $data['arquivo']->getClientOriginalName();
            $path = 'eventos/'.$arquivoInfo->evento->id.'/arquivos/';
            Storage::disk('public')->putFileAs($path, $data['arquivo'], $name);
            $arquivoInfo->path = $path.$name;
        }
        $arquivoInfo->save();

        return redirect()->back()->with(['success' => 'Arquivo atualizado com sucesso!']);
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('order', []) as $item) {
            ArquivoInfo::where('id', $item['id'])
                ->update(['order' => $item['position']]);
        }

        return response()->json(['status' => 'ok']);
    }
}
