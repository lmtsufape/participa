<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormSubmTraba;

class FormSubmTrabaController extends Controller
{
    public function update(Request $request, $id){

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
        if(isset($request->etiquetaregrasub)){
            $formevento->etiquetaregrasub                    = $request->etiquetaregrasub;
        }
        if(isset($request->etiquetatemplatesub)){
            $formevento->etiquetatemplatesub                 = $request->etiquetatemplatesub;
        }
        
        $formevento->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $id]);
    }

}
