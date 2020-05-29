<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Modalidade;
use App\FormTipoSubm;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $mytime = Carbon::now('America/Recife');
        $yesterday = Carbon::yesterday('America/Recife');
        $yesterday = $yesterday->toDateString();
        
        $validatedData = $request->validate([
            'nomeModalidade'         => ['required', 'string'],
            'custom_field'           => ['required', 'string'], //Dado para restrição de campos da submissão de arquivo!
        ]);
        
        if($request->custom_field == "option1"){
            $texto = true;
            $arquivo = false;
        }
        else{
            $arquivo = true;
            $texto = false;
        }
        
        $modalidade = Modalidade::create([
            'nome'              => $request->nomeModalidade,
            'inicioSubmissao'   => $request->inicioSubmissao,
            'fimSubmissao'      => $request->fimSubmissao,
            'inicioRevisao'     => $request->inicioRevisao,
            'fimRevisao'        => $request->fimRevisao,
            'inicioResultado'   => $request->inicioResultado
        ]);
        
        $formtiposubmissao = FormTipoSubm::create([
            'texto'             => $texto,
            'arquivo'           => $arquivo,
            'min_caracteres'    => $request->min_caracteres,
            'max_caracteres'    => $request->max_caracteres,
            'pdf'       => $request->pdf,
            'jpg'       => $request->jpg,
            'jpeg'      => $request->jpeg,
            'png'       => $request->png,
            'docx'      => $request->docx,
            'odt'       => $request->odt,
            'modalidadeId'      => $modalidade->id
        ]);

        dd($formtiposubmissao);
        $modalidade->save();
        $formtiposubmissao->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function show(Modalidade $modalidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Modalidade $modalidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modalidade $modalidade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modalidade $modalidade)
    {
        //
    }
}
