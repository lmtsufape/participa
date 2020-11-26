<?php

namespace App\Http\Controllers\Submissao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegraSubmisController extends Controller
{
    public function downloadArquivo(Request $request){
        return response()->download(storage_path('app/'.$request->file));
    }
}
