<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegraSubmisController extends Controller
{
    public function downloadArquivo(Request $request){
        return response()->download(storage_path('app/'.$request->file));
    }
}
