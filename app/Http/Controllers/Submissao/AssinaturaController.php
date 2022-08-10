<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssinaturaRequest;
use App\Models\Submissao\Assinatura;
use App\Models\Submissao\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssinaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $assinaturas = Assinatura::where('evento_id', $evento->id)->get();

        return view('coordenador.certificado.indexAssinatura', [
            'evento'=> $evento,
            'assinaturas' => $assinaturas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        return view('coordenador.certificado.createAssinatura', [
            'evento'=> $evento,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssinaturaRequest $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $request->validated();
        $assinatura = new Assinatura();
        $assinatura->setAtributes($request);
        $assinatura->evento_id = $evento->id;

        $assinatura->caminho = $request->fotoAssinatura->store("assinaturas/{$evento->id}", 'public');
        $assinatura->save();

        return redirect(route('coord.listarAssinaturas', ['eventoId' => $evento->id]))->with(['success' => 'Assinatura cadastrada com sucesso.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submissao\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function show(Assinatura $assinatura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submissao\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $assinatura = Assinatura::find($id);

        return view('coordenador.certificado.editAssinatura', [
            'assinatura' => $assinatura,
            'evento'=> $evento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submissao\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $validatedData = $request->validate([
            'nome'              => 'required|string|min:10|max:290',
            'cargo'              => 'required|string|max:290',
        ]);
        $assinatura = Assinatura::find($id);
        $assinatura->setAtributes($request);

        if ($request->fotoAssinatura != null) {
            $validatedData = $request->validate([
                'fotoAssinatura'  => 'required|file|mimes:png,jpeg,jpg|max:2048',
            ]);
            if (Storage::disk()->exists('public/'.$assinatura->caminho)) {
                Storage::delete('storage/'.$assinatura->caminho);
            }
            $imagem = $request->fotoAssinatura;
            $path = 'assinaturas/'.$evento->id.'/';
            $nome = $imagem->getClientOriginalName();
            $nomeSemEspaco = str_replace(' ', '', $nome);
            Storage::putFileAs('public/'.$path, $imagem, $nomeSemEspaco);
            $assinatura->caminho = $path.$nomeSemEspaco;
        }
        $assinatura->update();

        return redirect(route('coord.listarAssinaturas', ['eventoId' => $evento->id]))->with(['success' => 'Assinatura editada com sucesso.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submissao\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assinatura = Assinatura::find($id);
        $evento = $assinatura->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        if ($assinatura->certificados()->first() != null) {
            return redirect(route('coord.listarAssinaturas', ['eventoId' => $evento->id]))->with(['error' => 'Esta assinatura está presente em um modelo de certificado, e ela não pode ser deletada.']);
        } else {
            $assinatura->delete();

            return redirect(route('coord.listarAssinaturas', ['eventoId' => $evento->id]))->with(['success' => 'Assinatura deletada com sucesso.']);
        }
    }
}
