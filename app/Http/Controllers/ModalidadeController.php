<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Modalidade;
use App\FormTipoSubm;
use App\RegraSubmis;
use App\TemplateSubmis;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\File;
use Illuminate\Support\Facades\File;
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

    public function find(Request $request)
    {
        $modalidadeEdit = Modalidade::find($request->modalidadeId);
        return $modalidadeEdit;

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
        $palavras = false;
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

        if(isset($request->maxcaracteres) && isset($request->mincaracteres) && $request->maxcaracteres <= $request->mincaracteres){
            return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!']);
        }
        if(isset($request->maxpalavras) && isset($request->minpalavras) && $request->maxpalavras <= $request->minpalavras){
            return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!']);
        }

        // if($request->custom_field == "option1") {

        //     if ($request->limit == "limit-option1") {
        //         $caracteres = true;
        //         $texto == true;
        //     }
        //     else {
        //         $palavras = true;
        //         $texto == true;
        //     }
        // }
        // else {
        //     $arquivo == true;
        // }
        if($request->custom_field == "option1"){
            $texto = true;
            $arquivo = false;
        }
        if ($request->custom_field == "option2") {
            $arquivo = true;
            $texto = false;
            $caracteres = false;
            $palavras = false;
        }
        if ($request->limit == "limit-option1") {
            $caracteres = true;
            $palavras = false;
        }
        if ($request->limit == "limit-option2") {
            $caracteres = false;
            $palavras = true;
        }
        
        $modalidade = Modalidade::create([
            'nome'              => $request->nomeModalidade,
            'inicioSubmissao'   => $request->inicioSubmissao,
            'fimSubmissao'      => $request->fimSubmissao,
            'inicioRevisao'     => $request->inicioRevisao,
            'fimRevisao'        => $request->fimRevisao,
            'inicioResultado'   => $request->inicioResultado,
            'texto'             => $texto,
            'arquivo'           => $arquivo,
            'caracteres'        => $caracteres,
            'palavras'          => $palavras,
            'mincaracteres'     => $request->mincaracteres,
            'maxcaracteres'     => $request->maxcaracteres,
            'minpalavras'       => $request->minpalavras,
            'maxpalavras'       => $request->maxpalavras,
            'pdf'               => $request->pdf,
            'jpg'               => $request->jpg,
            'jpeg'              => $request->jpeg,
            'png'               => $request->png,
            'docx'              => $request->docx,
            'odt'               => $request->odt,
            'eventoId'          => $request->eventoId,
        ]);
        
        if(isset($request->arquivoRegras)){
            $fileRegras = $request->arquivoRegras;
            $pathRegras = 'regras/' . $modalidade->id . '/';
            $nomeRegras = $request->arquivoRegras->getClientOriginalName();

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
            $nomeTemplates = $request->arquivoTemplates->getClientOriginalName();
            
            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);
            
            $templatesubmissao = TemplateSubmis::create([
                'nome'  => $pathTemplates . $nomeTemplates,
                'modalidadeId'  => $modalidade->id,
            ]);

            $templatesubmissao->save();
        }
        
        $modalidade->save();

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
    public function update(Request $request)
    {
        $modalidadeEdit = Modalidade::find($request->modalidadeEditId);

        $validatedData = $request->validate([

            'nomeModalidadeEdit'         => ['nullable', 'string'],
            'inicioSubmissaoEdit'        => ['nullable', 'date'],
            'fimSubmissaoEdit'           => ['nullable', 'date'],
            'inicioRevisaoEdit'          => ['nullable', 'date'],
            'fimRevisaoEdit'             => ['nullable', 'date'],
            'inicioResultadoEdit'        => ['nullable', 'date'],
            'mincaracteresEdit'          => ['nullable', 'integer'],
            'maxcaracteresEdit'          => ['nullable', 'integer'],
            'minpalavrasEdit'            => ['nullable', 'integer'],
            'maxpalavrasEdit'            => ['nullable', 'integer'],
            'arquivoRegrasEdit'          => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],
            'arquivoTemplatesEdit'       => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],

        ]);

        if(isset($request->maxcaracteresEdit) && isset($request->mincaracteresEdit) && $request->maxcaracteresEdit <= $request->mincaracteresEdit){
            return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!']);
        }
        if(isset($request->maxpalavrasEdit) && isset($request->minpalavrasEdit) && $request->maxpalavrasEdit <= $request->minpalavrasEdit){
            return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!']);
        }

        // Condição para opção de texto escolhida
        if($request->custom_fieldEdit == "option1Edit"){
            
            $texto = true;
            $arquivo = false;

            $modalidadeEdit->pdf  = false;
            $modalidadeEdit->jpg  = false;
            $modalidadeEdit->jpeg = false;
            $modalidadeEdit->png  = false;
            $modalidadeEdit->docx = false;
            $modalidadeEdit->odt  = false;
            
            // Condição para opção de caracteres escolhida 
            if ($request->limitEdit == "limit-option1Edit") {
                $caracteres = true;
                $palavras = false;
                $modalidadeEdit->maxcaracteres       = $request->maxcaracteresEdit;
                $modalidadeEdit->mincaracteres       = $request->mincaracteresEdit;
                $modalidadeEdit->minpalavras         = null;
                $modalidadeEdit->maxpalavras         = null;
            }
            // Condição para opção de palavras escolhida
            if ($request->limitEdit == "limit-option2Edit") {
                $caracteres = false;
                $palavras = true;
                $modalidadeEdit->maxcaracteres       = null;
                $modalidadeEdit->mincaracteres       = null;
                $modalidadeEdit->minpalavras         = $request->minpalavrasEdit;
                $modalidadeEdit->maxpalavras         = $request->maxpalavrasEdit;
            }
        }

        // Condição para opção de arquivo escolhida
        if ($request->custom_fieldEdit == "option2Edit") {
            $arquivo = true;
            $texto = false;
            $caracteres = false;
            $palavras = false;

            $modalidadeEdit->pdf  = $request->pdfEdit;
            $modalidadeEdit->jpg  = $request->jpgEdit;
            $modalidadeEdit->jpeg = $request->jpegEdit;
            $modalidadeEdit->png  = $request->pngEdit;
            $modalidadeEdit->docx = $request->docxEdit;
            $modalidadeEdit->odt  = $request->odtEdit;

            $modalidadeEdit->maxcaracteres = null;
            $modalidadeEdit->mincaracteres = null;
            $modalidadeEdit->minpalavras   = null;
            $modalidadeEdit->maxpalavras   = null;
        }

        $modalidadeEdit->nome                = $request->nomeModalidadeEdit;
        $modalidadeEdit->inicioSubmissao     = $request->inicioSubmissaoEdit;
        $modalidadeEdit->fimSubmissao        = $request->fimSubmissaoEdit;
        $modalidadeEdit->inicioRevisao       = $request->inicioRevisaoEdit;
        $modalidadeEdit->fimRevisao          = $request->fimRevisaoEdit;
        $modalidadeEdit->inicioResultado     = $request->inicioResultadoEdit;
        $modalidadeEdit->texto               = $texto;
        $modalidadeEdit->arquivo             = $arquivo;
        $modalidadeEdit->caracteres          = $caracteres;
        $modalidadeEdit->palavras            = $palavras;


        if(isset($request->arquivoRegrasEdit)){
            
            $regraEdit = RegraSubmis::where('modalidadeId', $modalidadeEdit->id)->first();
            $path = $regraEdit->nome;
            Storage::delete($path);

            $fileRegras = $request->arquivoRegrasEdit;
            $pathRegras = 'regras/' . $modalidadeEdit->id . '/';
            $nomeRegras = $request->arquivoRegrasEdit->getClientOriginalName();
            
            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $regraEdit->nome = $pathRegras . $nomeRegras;

            $regraEdit->save();
        }

        if (isset($request->arquivoTemplatesEdit)) {

            $templateEdit = TemplateSubmis::where('modalidadeId', $modalidadeEdit->id)->first();
            $path = $templateEdit->nome;
            Storage::delete($path);
            
            $fileTemplates = $request->arquivoTemplatesEdit;
            $pathTemplates = 'templates/' . $modalidadeEdit->id . '/';
            $nomeTemplates = $request->arquivoTemplatesEdit->getClientOriginalName();
            
            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);

            $templateEdit->nome = $pathTemplates . $nomeTemplates;

            $templateEdit->save();
        }
        
        $modalidadeEdit->save();

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
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
