<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSubmTraba;

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
        if(isset($request->etiquetabaixarregra)){
            $formevento->etiquetabaixarregra                 = $request->etiquetabaixarregra;
        }
        if(isset($request->etiquetabaixartemplate)){
            $formevento->etiquetabaixartemplate              = $request->etiquetabaixartemplate;
        }
        
        $formevento->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $id]);
    }

}
