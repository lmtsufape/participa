<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Modalidade;
use App\FormTipoSubm;
use App\RegraSubmis;
use App\TemplateSubmis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
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

            'inicioSubmissao'   => ['nullable', 'date'],
            'fimSubmissao'      => ['nullable', 'date'],
            'inicioRevisao'     => ['nullable', 'date'],
            'fimRevisao'        => ['nullable', 'date'],
            'inicioResultado'   => ['nullable', 'date'],
            'mincaracteres'     => ['nullable', 'integer'],
            'maxcaracteres'     => ['nullable', 'integer'],
            'minpalavras'       => ['nullable', 'integer'],
            'maxpalavras'       => ['nullable', 'integer'],
            'arquivoRegras'     => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],
            'arquivoTemplates'  => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],
        ]);
        
        if ($request->texto == true && $request->arquivo == false || $request->texto == true && $request->arquivo == true) {
            if ($request->limit == "limit-option1") {
                $caracteres = true;
                $palavras = false;
            }
            if ($request->limit == "limit-option2") {
                $caracteres = false;
                $palavras = true;
            }
        }
        else {
            $caracteres = false;
            $palavras = false;
        }
        
        if(isset($request->maxcaracteres) && isset($request->mincaracteres) && $request->maxcaracteres <= $request->mincaracteres){
            return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!']);
        }
        if(isset($request->maxpalavras) && isset($request->minpalavras) && $request->maxpalavras <= $request->minpalavras){
            return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!']);
        }
        
        $modalidade = Modalidade::create([
            'nome'              => $request->nomeModalidade,
            'inicioSubmissao'   => $request->inicioSubmissao,
            'fimSubmissao'      => $request->fimSubmissao,
            'inicioRevisao'     => $request->inicioRevisao,
            'fimRevisao'        => $request->fimRevisao,
            'inicioResultado'   => $request->inicioResultado,
            'eventoId'          => $request->eventoId,
        ]);
        
        $formtiposubmissao = FormTipoSubm::create([
            'texto'             => $request->texto,
            'arquivo'           => $request->arquivo,
            'caracteres'        => $caracteres,
            'palavras'          => $palavras,
            'mincaracteres'    => $request->mincaracteres,
            'maxcaracteres'    => $request->maxcaracteres,
            'minpalavras'      => $request->minpalavras,
            'maxpalavras'      => $request->maxpalavras,
            'pdf'               => $request->pdf,
            'jpg'               => $request->jpg,
            'jpeg'              => $request->jpeg,
            'png'               => $request->png,
            'docx'              => $request->docx,
            'odt'               => $request->odt,
            'modalidadeId'      => $modalidade->id
        ]);
        
        if(isset($request->arquivoRegras)){

            $fileRegras = $request->arquivoRegras;
            $pathRegras = 'regras/' . $modalidade->id . '/';
            $nomeRegras = "regra_submissao.pdf";

            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $regrasubmissao = RegraSubmis::create([
                'nome'  => $pathRegras . $nomeRegras,
                'modalidadeId'  => $modalidade->id,
            ]);

            $regrasubmissao->save();
        }

        if ($request->arquivoTemplates) {
            
            $fileTemplates = $request->arquivoTemplates;
            $pathTemplates = 'templates/' . $modalidade->id . '/';
            $nomeTemplates = "templates_submissao.pdf";
            
            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);
            
            $templatesubmissao = TemplateSubmis::create([
                'nome'  => $pathTemplates . $nomeTemplates,
                'modalidadeId'  => $modalidade->id,
            ]);

            $templatesubmissao->save();
        }
        
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
