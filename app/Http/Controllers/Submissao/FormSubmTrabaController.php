<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\FormSubmTraba;
use Illuminate\Http\Request;

class FormSubmTrabaController extends Controller
{
    public function update(Request $request, $id)
    {
        $ordem = $request->all();
        $array = [];
        foreach ($ordem as $key => $value) {
            array_push($array, $key);
            // echo  $key." => ".$value."<br/>";
        }
        $ordemString = implode(',', $array); //String com a ordem dos campos

        $validatedData = $request->validate([
            'etiquetatitulotrabalho' => ['nullable', 'string'],
            'etiquetaautortrabalho' => ['nullable', 'string'],
            'etiquetacoautortrabalho' => ['nullable', 'string'],
            'etiquetaresumotrabalho' => ['nullable', 'string'],
            'etiquetaareatrabalho' => ['nullable', 'string'],
            'etiquetauploadtrabalho' => ['nullable', 'string'],
            'etiquetacampoextra1' => ['nullable', 'string'],
            'etiquetacampoextra2' => ['nullable', 'string'],
            'etiquetacampoextra3' => ['nullable', 'string'],
            'etiquetacampoextra4' => ['nullable', 'string'],
            'etiquetacampoextra5' => ['nullable', 'string'],
            'select_campo1' => ['nullable', 'string'],
            'select_campo2' => ['nullable', 'string'],
            'select_campo3' => ['nullable', 'string'],
            'select_campo4' => ['nullable', 'string'],
            'select_campo5' => ['nullable', 'string'],
            'etiquetatitulotrabalho_en' => ['nullable', 'string'],
            'etiquetaresumotrabalho_en' => ['nullable', 'string'],
            'etiquetacampoextra1_en' => ['nullable', 'string'],
            'etiquetacampoextra2_en' => ['nullable', 'string'],
            'etiquetacampoextra3_en' => ['nullable', 'string'],
            'etiquetacampoextra4_en' => ['nullable', 'string'],
            'etiquetacampoextra5_en' => ['nullable', 'string'],
        ]);

        $formevento = FormSubmTraba::where('eventoId', $id)->first();

        if (isset($request->etiquetatitulotrabalho)) {
            $formevento->etiquetatitulotrabalho = $request->etiquetatitulotrabalho;
        }
        if (isset($request->etiquetatitulotrabalho_en)) {
            $formevento->etiquetatitulotrabalho_en = $request->etiquetatitulotrabalho_en;
        }
        if (isset($request->etiquetaautortrabalho)) {
            $formevento->etiquetaautortrabalho = $request->etiquetaautortrabalho;
        }
        if (isset($request->etiquetacoautortrabalho)) {
            $formevento->etiquetacoautortrabalho = $request->etiquetacoautortrabalho;
        }
        if (isset($request->etiquetaresumotrabalho)) {
            $formevento->etiquetaresumotrabalho = $request->etiquetaresumotrabalho;
        }
        if (isset($request->etiquetaresumotrabalho_en)) {
            $formevento->etiquetaresumotrabalho_en = $request->etiquetaresumotrabalho_en;
        }
        if (isset($request->etiquetaareatrabalho)) {
            $formevento->etiquetaareatrabalho = $request->etiquetaareatrabalho;
        }
        if (isset($request->etiquetauploadtrabalho)) {
            $formevento->etiquetauploadtrabalho = $request->etiquetauploadtrabalho;
        }

        if (isset($request->etiquetacampoextra1)) {
            $formevento->etiquetacampoextra1 = $request->etiquetacampoextra1;
        }
        if (isset($request->etiquetacampoextra2)) {
            $formevento->etiquetacampoextra2 = $request->etiquetacampoextra2;
        }
        if (isset($request->etiquetacampoextra3)) {
            $formevento->etiquetacampoextra3 = $request->etiquetacampoextra3;
        }
        if (isset($request->etiquetacampoextra4)) {
            $formevento->etiquetacampoextra4 = $request->etiquetacampoextra4;
        }
        if (isset($request->etiquetacampoextra5)) {
            $formevento->etiquetacampoextra5 = $request->etiquetacampoextra5;
        }

        if (isset($request->etiquetacampoextra1_en)) {
            $formevento->etiquetacampoextra1_en = $request->etiquetacampoextra1_en;
        }
        if (isset($request->etiquetacampoextra2_en)) {
            $formevento->etiquetacampoextra2_en = $request->etiquetacampoextra2_en;
        }
        if (isset($request->etiquetacampoextra3_en)) {
            $formevento->etiquetacampoextra3_en = $request->etiquetacampoextra3_en;
        }
        if (isset($request->etiquetacampoextra4_en)) {
            $formevento->etiquetacampoextra4_en = $request->etiquetacampoextra4_en;
        }
        if (isset($request->etiquetacampoextra5_en)) {
            $formevento->etiquetacampoextra5_en = $request->etiquetacampoextra5_en;
        }

        // Opções para tipo de campos extras
        if (isset($request->select_campo1)) {
            $formevento->tipocampoextra1 = $request->select_campo1;
        }
        if (isset($request->select_campo2)) {
            $formevento->tipocampoextra2 = $request->select_campo2;
        }
        if (isset($request->select_campo3)) {
            $formevento->tipocampoextra3 = $request->select_campo3;
        }
        if (isset($request->select_campo4)) {
            $formevento->tipocampoextra4 = $request->select_campo4;
        }
        if (isset($request->select_campo5)) {
            $formevento->tipocampoextra5 = $request->select_campo5;
        }

        // Checkboxes para exibição ou não de campos extras
        // na tela de submissão de trabalhos

        if (isset($request->checkcampoextra1)) {
            $formevento->checkcampoextra1 = $request->checkcampoextra1;
        }
        if (isset($request->checkcampoextra2)) {
            $formevento->checkcampoextra2 = $request->checkcampoextra2;
        }
        if (isset($request->checkcampoextra3)) {
            $formevento->checkcampoextra3 = $request->checkcampoextra3;
        }
        if (isset($request->checkcampoextra4)) {
            $formevento->checkcampoextra4 = $request->checkcampoextra4;
        }
        if (isset($request->checkcampoextra5)) {
            $formevento->checkcampoextra5 = $request->checkcampoextra5;
        }

        $formevento->ordemCampos = $ordemString;

        $formevento->save();

        return redirect()->back()->with(['success' => 'Formulário de submissão do trabalho salvo com sucesso!']);
    }
}
