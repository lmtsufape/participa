<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use App\Models\Submissao\FormEvento;
use Illuminate\Http\Request;

class FormEventoController extends Controller
{
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $request->validate([
            'etiquetanomeevento' => ['nullable', 'string'],
            'etiquetatipoevento' => ['nullable', 'string'],
            'etiquetadescricaoevento' => ['nullable', 'string'],
            'etiquetadatas' => ['nullable', 'string'],
            'etiquetasubmissoes' => ['nullable', 'string'],
            'etiquetaenderecoevento' => ['nullable', 'string'],
            'etiquetamoduloinscricao' => ['nullable', 'string'],
            'etiquetamoduloprogramacao' => ['nullable', 'string'],
            'etiquetamoduloorganizacao' => ['nullable', 'string'],
            'etiquetabaixarregra' => ['nullable', 'string'],
            'etiquetabaixartemplate' => ['nullable', 'string'],
            'etiquetabaixarapresentacao' => ['nullable', 'string'],
            'etiquetaarquivo' => ['nullable', 'string'],
        ]);
        $formevento = FormEvento::where('eventoId', $id)->first();
        if (isset($request->etiquetanomeevento)) {
            $formevento->etiquetanomeevento = $request->etiquetanomeevento;
        }
        if (isset($request->etiquetatipoevento)) {
            $formevento->etiquetatipoevento = $request->etiquetatipoevento;
        }
        if (isset($request->etiquetadescricaoevento)) {
            $formevento->etiquetadescricaoevento = $request->etiquetadescricaoevento;
        }
        if (isset($request->etiquetadatas)) {
            $formevento->etiquetadatas = $request->etiquetadatas;
        }
        if (isset($request->etiquetasubmissoes)) {
            $formevento->etiquetasubmissoes = $request->etiquetasubmissoes;
        }
        if (isset($request->etiquetaenderecoevento)) {
            $formevento->etiquetaenderecoevento = $request->etiquetaenderecoevento;
        }
        if (isset($request->etiquetamoduloinscricao)) {
            $formevento->etiquetamoduloinscricao = $request->etiquetamoduloinscricao;
        }
        if (isset($request->etiquetamoduloprogramacao)) {
            $formevento->etiquetamoduloprogramacao = $request->etiquetamoduloprogramacao;
        }
        if (isset($request->etiquetamoduloorganizacao)) {
            $formevento->etiquetamoduloorganizacao = $request->etiquetamoduloorganizacao;
        }
        if (isset($request->etiquetabaixarregra)) {
            $formevento->etiquetabaixarregra = $request->etiquetabaixarregra;
        }
        if (isset($request->etiquetabaixarapresentacao)) {
            $formevento->etiquetabaixarapresentacao = $request->etiquetabaixarapresentacao;
        }
        if (isset($request->etiquetabaixartemplate)) {
            $formevento->etiquetabaixartemplate = $request->etiquetabaixartemplate;
        }
        if (isset($request->etiquetaarquivo)) {
            $formevento->etiquetaarquivo = $request->etiquetaarquivo;
        }
        $formevento->save();

        return redirect()->back()->with(['mensagem' => 'Etiquetas salvas com sucesso!']);
    }

    public function exibirModulo(Request $request, $id)
    {
        $formevento = FormEvento::where('eventoId', $id)->first();
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        if (isset($request->modinscricao)) {
            $formevento->modinscricao = $request->modinscricao;
        }
        if (isset($request->modprogramacao)) {
            $formevento->modprogramacao = $request->modprogramacao;
            $evento->exibir_calendario_programacao = isset($request->exibir_calendario);
            $evento->exibir_pdf = isset($request->exibir_pdf);
        }
        if (isset($request->modorganizacao)) {
            $formevento->modorganizacao = $request->modorganizacao;
        }
        if (isset($request->modsubmissao)) {
            $formevento->modsubmissao = $request->modsubmissao;
        }
        if (isset($request->modarquivo)) {
            $evento->modarquivo = $request->modarquivo;
        }
        $evento->update();
        $formevento->save();

        return redirect()->back()->with(['mensagem' => 'Módulos em uso salvos com sucesso!']);
    }

    public function indexModulo($id)
    {
        $evento = Evento::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $modulos = FormEvento::where('eventoId', $id)->first();

        return view('coordenador.evento.modulos')->with(['modulos' => $modulos, 'evento' => $evento]);
    }
}
