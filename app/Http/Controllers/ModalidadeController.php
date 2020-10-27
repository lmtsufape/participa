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
use App\Evento;

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
        $evento = Evento::find($request->eventoId);
        // dd($request->eventoId);
        $validatedData = $request->validate([

            'inícioDaSubmissão' => ['required', 'date'],
            'fimDaSubmissão'    => ['required', 'date', 'after:inícioDaSubmissão'],
            'inícioDaRevisão'   => ['required', 'date', 'after:inícioDaSubmissão'],
            'fimDaRevisão'      => ['required', 'date', 'after:inícioDaRevisão'],
            'inícioDoResultado' => ['required', 'date', 'after:fimDaRevisão'],
            'mincaracteres'     => ['nullable', 'integer'],
            'maxcaracteres'     => ['nullable', 'integer'],
            'minpalavras'       => ['nullable', 'integer'],
            'maxpalavras'       => ['nullable', 'integer'],
            'arquivoRegras'     => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],
            'arquivoTemplates'  => ['nullable', 'file', 'mimes:pdf', 'max:2000000'],
        ]);

        // Verificar se o limite máximo de palavra ou caractere é menor que o limite mínimo
        if(isset($request->maxcaracteres) && isset($request->mincaracteres) && $request->maxcaracteres <= $request->mincaracteres){
            return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!']);
        }
        if(isset($request->maxpalavras) && isset($request->minpalavras) && $request->maxpalavras <= $request->minpalavras){
            return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!']);
        }

        if ($request->limit == null) {
            return redirect()->back()->withErrors(['caracteresoupalavras' => 'O tipo caracteres ou palavras não foi selecionado.']);
        }

        if ($request->limit == "limit-option1") {
            // Verifica se um campo foi deixado em branco
            if ($request->mincaracteres == null || $request->maxcaracteres == null){
                return redirect()->back()->withErrors(['semcaractere' => 'A opção caractere foi escolhida, porém nenhum ou um dos valores não foi passado']);
            }
            $caracteres = true;
            $palavras = false;
        }
        if ($request->limit == "limit-option2") {
            // Verifica se um campo foi deixado em branco
            if ($request->minpalavras == null || $request->maxpalavras == null){
                return redirect()->back()->withErrors(['sempalavra' => 'A opção palavra foi escolhida, porém nenhum ou um dos valores não foi passado']);
            }
            $caracteres = false;
            $palavras = true;
        }

        if ($request->arquivo == true) {
            // Verifica se um campo foi deixado em branco
            if ($request->pdf == null && $request->jpg == null && $request->jpeg == null && $request->png == null && $request->docx == null && $request->odt == null) {
                return redirect()->back()->withErrors(['marcarextensao' => 'O campo arquivo foi selecionado, mas nenhuma extensão foi selecionada.']);
            }
        }

        // Campo TEXTO boolean removido? 
        $modalidade = new Modalidade();
        $modalidade->nome               = $request->nomeModalidade;
        $modalidade->inicioSubmissao    = $request->input("inícioDaSubmissão");
        $modalidade->fimSubmissao       = $request->input("fimDaSubmissão");
        $modalidade->inicioRevisao      = $request->input("inícioDaRevisão");
        $modalidade->fimRevisao         = $request->input("fimDaRevisão");
        $modalidade->inicioResultado    = $request->input("inícioDoResultado");
        $modalidade->arquivo            = $request->arquivo;
        $modalidade->caracteres         = $caracteres;
        $modalidade->palavras           = $palavras;
        $modalidade->mincaracteres      = $request->mincaracteres;
        $modalidade->maxcaracteres      = $request->maxcaracteres;
        $modalidade->minpalavras        = $request->minpalavras;
        $modalidade->maxpalavras        = $request->maxpalavras;
        $modalidade->pdf                = $request->pdf;
        $modalidade->jpg                = $request->jpg;
        $modalidade->jpeg               = $request->jpeg;
        $modalidade->png                = $request->png;
        $modalidade->docx               = $request->docx;
        $modalidade->odt                = $request->odt;
        $modalidade->evento_id          = $request->eventoId;
        $modalidade->save();

        if(isset($request->arquivoRegras)){
            $fileRegras = $request->arquivoRegras;
            $pathRegras = 'regras/' . $modalidade->nome . '/';
            $nomeRegras = $request->arquivoRegras->getClientOriginalName();

            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $modalidade->regra = $pathRegras . $nomeRegras;
        }

        if (isset($request->arquivoTemplates)) {
            $fileTemplates = $request->arquivoTemplates;
            $pathTemplates = 'templates/' . $modalidade->nome . '/';
            $nomeTemplates = $request->arquivoTemplates->getClientOriginalName();
            
            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);

            $modalidade->template = $pathTemplates . $nomeTemplates;
        }
        
        $modalidade->save();

        return redirect()->back()->with(['mensagem' => 'Modalidade cadastrada com sucesso!']);
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

        // Condição para opção de caracteres escolhida 
        if ($request->limitEdit == "limit-option1Edit") {
            // Verifica se um campo foi deixado em branco
            if ($request->mincaracteresEdit == null || $request->maxcaracteresEdit == null){
                return redirect()->back()->withErrors(['semcaractere' => 'A opção caractere foi escolhida, porém nenhum ou um dos valores não foi passado']);
            }
            $caracteres = true;
            $palavras = false;
            $modalidadeEdit->maxcaracteres       = $request->maxcaracteresEdit;
            $modalidadeEdit->mincaracteres       = $request->mincaracteresEdit;
            $modalidadeEdit->minpalavras         = null;
            $modalidadeEdit->maxpalavras         = null;
        }
        // Condição para opção de palavras escolhida
        if ($request->limitEdit == "limit-option2Edit") {
            // Verifica se um campo foi deixado em branco
            if ($request->minpalavrasEdit == null || $request->maxpalavrasEdit == null){
                return redirect()->back()->withErrors(['sempalavra' => 'A opção palavra foi escolhida, porém nenhum ou um dos valores não foi passado']);
            }
            $caracteres = false;
            $palavras = true;
            $modalidadeEdit->maxcaracteres       = null;
            $modalidadeEdit->mincaracteres       = null;
            $modalidadeEdit->minpalavras         = $request->minpalavrasEdit;
            $modalidadeEdit->maxpalavras         = $request->maxpalavrasEdit;
        }

        // // Condição para opção de texto escolhida
        // if($request->custom_fieldEdit == "option1Edit"){
            
        //     $texto = true;
        //     $arquivo = false;

        //     $modalidadeEdit->pdf  = false;
        //     $modalidadeEdit->jpg  = false;
        //     $modalidadeEdit->jpeg = false;
        //     $modalidadeEdit->png  = false;
        //     $modalidadeEdit->docx = false;
        //     $modalidadeEdit->odt  = false;
            
            
        // }

        // Condição para opção de arquivo escolhida
        if ($request->arquivoEdit == true) {
            if ($request->pdfEdit == null && $request->jpgEdit == null && $request->jpegEdit == null && $request->pngEdit == null && $request->docxEdit == null && $request->odtEdit == null) {
                return redirect()->back()->withErrors(['marcarextensao' => 'O campo arquivo foi selecionado, mas nenhuma extensão foi selecionada.']);
            }

            $modalidadeEdit->pdf  = $request->pdfEdit;
            $modalidadeEdit->jpg  = $request->jpgEdit;
            $modalidadeEdit->jpeg = $request->jpegEdit;
            $modalidadeEdit->png  = $request->pngEdit;
            $modalidadeEdit->docx = $request->docxEdit;
            $modalidadeEdit->odt  = $request->odtEdit;

        }
        else {
            $modalidadeEdit->pdf  = false;
            $modalidadeEdit->jpg  = false;
            $modalidadeEdit->jpeg = false;
            $modalidadeEdit->png  = false;
            $modalidadeEdit->docx = false;
            $modalidadeEdit->odt  = false;
        }

        $modalidadeEdit->nome                = $request->nomeModalidadeEdit;
        $modalidadeEdit->inicioSubmissao     = $request->inicioSubmissaoEdit;
        $modalidadeEdit->fimSubmissao        = $request->fimSubmissaoEdit;
        $modalidadeEdit->inicioRevisao       = $request->inicioRevisaoEdit;
        $modalidadeEdit->fimRevisao          = $request->fimRevisaoEdit;
        $modalidadeEdit->inicioResultado     = $request->inicioResultadoEdit;
        // $modalidadeEdit->texto               = $texto;
        $modalidadeEdit->arquivo             = $request->arquivoEdit;
        $modalidadeEdit->caracteres          = $caracteres;
        $modalidadeEdit->palavras            = $palavras;


        if(isset($request->arquivoRegrasEdit)){
            
            $path = $modalidadeEdit->regra;
            Storage::delete($path);

            $fileRegras = $request->arquivoRegrasEdit;
            $pathRegras = 'regras/' . $modalidadeEdit->nome . '/';
            $nomeRegras = $request->arquivoRegrasEdit->getClientOriginalName();
            
            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $modalidadeEdit->regra = $pathRegras . $nomeRegras;

            $modalidadeEdit->save();
        }

        if (isset($request->arquivoTemplatesEdit)) {

            $path = $modalidadeEdit->template;
            Storage::delete($path);
            
            $fileTemplates = $request->arquivoTemplatesEdit;
            $pathTemplates = 'templates/' . $modalidadeEdit->nome . '/';
            $nomeTemplates = $request->arquivoTemplatesEdit->getClientOriginalName();
            
            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);

            $modalidadeEdit->template = $pathTemplates . $nomeTemplates;

            $modalidadeEdit->save();
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

    public function downloadRegras($id) {
        $modalidade = Modalidade::find($id);
        return Storage::download($modalidade->regra);
    }
}
