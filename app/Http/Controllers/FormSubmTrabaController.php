<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSubmTraba;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FormSubmTrabaController extends Controller
{
    public function update(Request $request, $id){

        $validatedData = $request->validate([
            'etiquetatitulotrabalho'    => ['nullable', 'string'],
            'etiquetaautortrabalho'     => ['nullable', 'string'],
            'etiquetacoautortrabalho'   => ['nullable', 'string'],
            'etiquetaresumotrabalho'    => ['nullable', 'string'],
            'etiquetaareatrabalho'      => ['nullable', 'string'],
            'etiquetauploadtrabalho'    => ['nullable', 'string'],
            'etiquetabaixarregra'       => ['nullable', 'string'],
            'etiquetabaixartemplate'    => ['nullable', 'string'],
            'etiquetacampoextra1'       => ['nullable', 'string'],
            'etiquetacampoextra2'       => ['nullable', 'string'],
            'etiquetacampoextra3'       => ['nullable', 'string'],
            'etiquetacampoextra4'       => ['nullable', 'string'],
            'etiquetacampoextra5'       => ['nullable', 'string'],
            'select_campo1'             => ['nullable', 'string'],
            'select_campo2'             => ['nullable', 'string'],
            'select_campo3'             => ['nullable', 'string'],
            'select_campo4'             => ['nullable', 'string'],
            'select_campo5'             => ['nullable', 'string'],
        ]);
        
        $formevento = FormSubmTraba::where('eventoId',$id)->first();

        if(isset($request->etiquetatitulotrabalho)){
            $formevento->etiquetatitulotrabalho              = $request->etiquetatitulotrabalho;
        }
        if(isset($request->etiquetaautortrabalho)){
            $formevento->etiquetaautortrabalho               = $request->etiquetaautortrabalho;
        }
        if(isset($request->etiquetacoautortrabalho)){
            $formevento->etiquetacoautortrabalho             = $request->etiquetacoautortrabalho;
        }
        if(isset($request->etiquetaresumotrabalho)){
            $formevento->etiquetaresumotrabalho              = $request->etiquetaresumotrabalho;
        }
        if(isset($request->etiquetaareatrabalho)){
            $formevento->etiquetaareatrabalho                = $request->etiquetaareatrabalho;
        }
        if(isset($request->etiquetauploadtrabalho)){
            $formevento->etiquetauploadtrabalho              = $request->etiquetauploadtrabalho;
        }
        // if(isset($request->etiquetabaixarregra)){
        //     $formevento->etiquetabaixarregra                 = $request->etiquetabaixarregra;
        // }
        // if(isset($request->etiquetabaixartemplate)){
        //     $formevento->etiquetabaixartemplate              = $request->etiquetabaixartemplate;
        // }
        if(isset($request->etiquetacampoextra1)){
            $formevento->etiquetacampoextra1                 = $request->etiquetacampoextra1;
        }
        if(isset($request->etiquetacampoextra2)){
            $formevento->etiquetacampoextra2                 = $request->etiquetacampoextra2;
        }
        if(isset($request->etiquetacampoextra3)){
            $formevento->etiquetacampoextra3                 = $request->etiquetacampoextra3;
        }
        if(isset($request->etiquetacampoextra4)){
            $formevento->etiquetacampoextra4                 = $request->etiquetacampoextra4;
        }
        if(isset($request->etiquetacampoextra5)){
            $formevento->etiquetacampoextra5                 = $request->etiquetacampoextra5;
        }

        // Opções para tipo de campos extras
        if(isset($request->select_campo1)){
            $formevento->tipocampo1                       = $request->select_campo1;
        }
        if(isset($request->select_campo2)){
            $formevento->tipocampo2                       = $request->select_campo2;
        }
        if(isset($request->select_campo3)){
            $formevento->tipocampo3                       = $request->select_campo3;
        }
        if(isset($request->select_campo4)){
            $formevento->tipocampo4                       = $request->select_campo4;
        }
        if(isset($request->select_campo5)){
            $formevento->tipocampo5                       = $request->select_campo5;
        }
        

        // Checkboxes para exibição ou não de campos extras
        // na tela de submissão de trabalhos

        if(isset($request->checkcampoextra1)){
            $formevento->checkcampoextra1       = $request->checkcampoextra1;
        }
        if(isset($request->checkcampoextra2)){
            $formevento->checkcampoextra2       = $request->checkcampoextra2;
        }
        if(isset($request->checkcampoextra3)){
            $formevento->checkcampoextra3       = $request->checkcampoextra3;
        }
        if(isset($request->checkcampoextra4)){
            $formevento->checkcampoextra4       = $request->checkcampoextra4;
        }
        if(isset($request->checkcampoextra5)){
            $formevento->checkcampoextra5       = $request->checkcampoextra5;
        }
        
        $formevento->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $id]);
    }

}
