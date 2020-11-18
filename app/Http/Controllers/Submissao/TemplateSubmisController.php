<?php

namespace App\Http\Controllers\Submissao;

use Illuminate\Http\Request;

class TemplateSubmisController extends Controller
{
    public function downloadArquivo(Request $request){
        return response()->download(storage_path('app/'.$request->file));
    }
}
