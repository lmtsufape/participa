<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use App\Models\Submissao\FormEvento;
use App\Support\EventoModules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormEventoController extends Controller
{
    public function update(Request $request, $id)
    {

        $evento = Evento::findOrFail($id);
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

        return redirect()->back()->with(['success' => 'Etiquetas salvas com sucesso!']);
    }

    public function exibirModulo(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $request->validate(array_fill_keys(EventoModules::requestFields(), ['sometimes', 'boolean']));

        DB::transaction(function () use ($request, $evento) {
            $formevento = FormEvento::where('eventoId', $evento->id)->firstOrFail();

            foreach (EventoModules::definitions() as $module) {
                $enabledField = $module['enabled']['field'];
                $enabled = $request->boolean($enabledField);

                $formevento->{$enabledField} = $enabled;

                foreach ($module['options'] as $option) {
                    $value = $enabled && $request->boolean($option['field']);
                    $column = $option['column'] ?? $option['field'];

                    if ($option['storage'] === 'evento') {
                        $evento->{$column} = $value;
                    } else {
                        $formevento->{$column} = $value;
                    }
                }
            }

            $evento->save();
            $formevento->save();
        });

        return redirect()->back()->with(['success' => 'Módulos em uso salvos com sucesso!']);
    }

    public function indexModulo($id)
    {
        $evento = Evento::findOrFail($id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);
        $modulos = FormEvento::where('eventoId', $id)->firstOrFail();

        return view('coordenador.evento.modulos')->with([
            'modulos' => $modulos,
            'evento' => $evento,
            'moduleGroups' => EventoModules::definitions(),
        ]);
    }
}
