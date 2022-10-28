<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use App\Models\Submissao\MidiaExtra;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\TipoApresentacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
        // dd($request->all());
        $mytime = Carbon::now('America/Recife');
        $yesterday = Carbon::yesterday('America/Recife');
        $yesterday = $yesterday->toDateString();
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        // dd($request->eventoId);
        $validatedData = $request->validate([
            'nomeModalidade'    => ['required', 'string'],
            'inícioDaSubmissão' => ['required', 'date'],
            'fimDaSubmissão'    => ['required', 'date', 'after:inícioDaSubmissão'],
            'inícioDaRevisão'   => ['nullable', 'date', 'after:inícioDaSubmissão'],
            'fimDaRevisão'      => ['nullable', 'date', 'after:inícioDaRevisão'],

            'inícioCorreção'   => ['nullable', 'date', 'after:fimDaRevisão', 'required_with:fimCorreção'],
            'fimCorreção'      => ['nullable', 'date', 'after:inícioCorreção', 'required_with:inícioCorreção'],
            'inícioValidação'   => ['nullable', 'date', 'after:fimCorreção', 'required_with:fimValidação'],
            'fimValidação'      => ['nullable', 'date', 'after:inícioValidação', 'required_with:inícioValidação'],

            'resultado'         => ['required', 'date', 'after:fimDaSubmissão'],

            'texto'             => ['nullable'],
            'limit'             => ['nullable'],
            'arquivo'           => ['nullable'],
            'apresentacao'      => ['nullable'],
            'pdf'               => ['nullable'],
            'jpg'               => ['nullable'],
            'jpeg'              => ['nullable'],
            'png'               => ['nullable'],
            'docx'              => ['nullable'],
            'odt'               => ['nullable'],
            'zip'               => ['nullable'],
            'svg'               => ['nullable'],
            'mp4'               => ['nullable'],
            'mp3'               => ['nullable'],

            'mincaracteres'     => ['nullable', 'integer'],
            'maxcaracteres'     => ['nullable', 'integer'],
            'minpalavras'       => ['nullable', 'integer'],
            'maxpalavras'       => ['nullable', 'integer'],
            'arquivoRegras'     => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'arquivoInstrucoes' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'arquivoModelo'     => ['nullable', 'file', 'mimes:odt,ott,docx,doc,rtf,pdf,ppt,pptx,odp', 'max:2048'],
            'arquivoTemplates'  => ['nullable', 'file', 'mimes:odt,ott,docx,doc,rtf,txt,pdf', 'max:2048'],
        ]);
        // dd($request);
        $caracteres = false;
        $palavras = false;
        if ($request->texto == true) {
            // Verificar se o limite máximo de palavra ou caractere é menor que o limite mínimo
            if (isset($request->maxcaracteres) && isset($request->mincaracteres) && $request->maxcaracteres <= $request->mincaracteres) {
                return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!'])->withInput($request->all());
            }
            if (isset($request->maxpalavras) && isset($request->minpalavras) && $request->maxpalavras <= $request->minpalavras) {
                return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!'])->withInput($request->all());
            }

            if ($request->limit == null) {
                return redirect()->back()->withErrors(['caracteresoupalavras' => 'O tipo caracteres ou palavras não foi selecionado.'])->withInput($request->all());
            }

            if ($request->limit == 'limit-option1') {
                // Verifica se um campo foi deixado em branco
                if ($request->mincaracteres == null || $request->maxcaracteres == null) {
                    return redirect()->back()->withErrors(['semcaractere' => 'A opção caractere foi escolhida, porém nenhum ou um dos valores não foi passado'])->withInput($request->all());
                }
                $caracteres = true;
                $palavras = false;
            }
            if ($request->limit == 'limit-option2') {
                // Verifica se um campo foi deixado em branco
                if ($request->minpalavras == null || $request->maxpalavras == null) {
                    return redirect()->back()->withErrors(['sempalavra' => 'A opção palavra foi escolhida, porém nenhum ou um dos valores não foi passado'])->withInput($request->all());
                }
                $caracteres = false;
                $palavras = true;
            }
        }

        if ($request->arquivo == true) {
            // Verifica se um campo foi deixado em branco
            if ($request->pdf == null && $request->jpg == null && $request->jpeg == null && $request->png == null && $request->docx == null && $request->odt == null && $request->mp3 == null && $request->mp4 == null) {
                return redirect()->back()->withErrors(['marcarextensao' => 'O campo arquivo foi selecionado, mas nenhuma extensão foi selecionada.'])->withInput($request->all());
            }
        }

        if ($request->apresentacao) {
            if (! $request->presencial && ! $request->remoto && ! $request->a_distancia && ! $request->semipresencial) {
                return redirect()->back()->withErrors(['apresentacao' => 'Selecione pelos menos um tipo de apresentação.'])->withInput($request->all());
            }
        }

        if ($request->documentosExtra != null) {
            foreach ($request->documentosExtra as $doc) {
                if (count($doc) <= 1) {
                    return redirect()->back()->withErrors(['documentosExtra' => 'Selecione pelos menos um tipo de extensão.'])->withInput($request->all());
                }
            }
        }

        // Campo TEXTO boolean removido?
        // $modalidade = Modalidade::create($request->all());
        $modalidade = new Modalidade();
        $modalidade->nome = $request->nomeModalidade;
        $modalidade->inicioSubmissao = $request->input('inícioDaSubmissão');
        $modalidade->fimSubmissao = $request->input('fimDaSubmissão');
        $modalidade->inicioRevisao = $request->input('inícioDaRevisão');
        $modalidade->fimRevisao = $request->input('fimDaRevisão');
        $modalidade->inicioCorrecao = $request->input('inícioCorreção');
        $modalidade->fimCorrecao = $request->input('fimCorreção');
        $modalidade->inicioValidacao = $request->input('inícioValidação');
        $modalidade->fimValidacao = $request->input('fimValidação');
        $modalidade->inicioResultado = $request->resultado;
        $modalidade->texto = $request->texto;
        $modalidade->arquivo = $request->arquivo;
        $modalidade->caracteres = $caracteres;
        $modalidade->palavras = $palavras;
        $modalidade->mincaracteres = $request->mincaracteres;
        $modalidade->maxcaracteres = $request->maxcaracteres;
        $modalidade->minpalavras = $request->minpalavras;
        $modalidade->maxpalavras = $request->maxpalavras;
        $modalidade->pdf = $request->pdf;
        $modalidade->jpg = $request->jpg;
        $modalidade->jpeg = $request->jpeg;
        $modalidade->png = $request->png;
        $modalidade->docx = $request->docx;
        $modalidade->odt = $request->odt;
        $modalidade->zip = $request->zip;
        $modalidade->svg = $request->svg;
        $modalidade->mp4 = $request->mp4;
        $modalidade->mp3 = $request->mp3;
        $modalidade->evento_id = $request->eventoId;
        $modalidade->apresentacao = $request->apresentacao ? true : false;
        $modalidade->save();

        if (isset($request->arquivoRegras)) {
            $fileRegras = $request->arquivoRegras;
            $pathRegras = 'regras/'.$modalidade->nome.'/';
            $nomeRegras = $request->arquivoRegras->getClientOriginalName();

            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $modalidade->regra = $pathRegras.$nomeRegras;
        }

        if (isset($request->arquivoInstrucoes)) {
            $modalidade->instrucoes = $request->arquivoInstrucoes->store('instrucoes/'.$modalidade->nome);
        }

        if (isset($request->arquivoTemplates)) {
            $fileTemplates = $request->arquivoTemplates;
            $pathTemplates = 'templates/'.$modalidade->nome.'/';
            $nomeTemplates = $request->arquivoTemplates->getClientOriginalName();

            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);

            $modalidade->template = $pathTemplates.$nomeTemplates;
        }

        if (isset($request->arquivoModelos)) {
            $fileModelos = $request->arquivoModelos;
            $pathModelos = 'modelos/'.$modalidade->nome.'/';
            $nomeModelos = $request->arquivoModelos->getClientOriginalName();

            Storage::putFileAs($pathModelos, $fileModelos, $nomeModelos);

            $modalidade->modelo_apresentacao = $pathModelos.$nomeModelos;
        }

        if ($request->apresentacao) {
            if ($request->presencial) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Presencial";
                $tipo->modalidade_id = $modalidade->id;
                $tipo->save();
            }
            if ($request->remoto) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Remoto";
                $tipo->modalidade_id = $modalidade->id;
                $tipo->save();
            }
            if ($request->a_distancia) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "À distância";
                $tipo->modalidade_id = $modalidade->id;
                $tipo->save();
            }
            if ($request->semipresencial) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Semipresencial";
                $tipo->modalidade_id = $modalidade->id;
                $tipo->save();
            }
        }

        if ($request->documentosExtra != null) {
            foreach ($request->documentosExtra as $doc) {
                $this->saveMidiaExtra($doc, $modalidade);
            }
        }

        $modalidade->save();

        return redirect()->back()->with(['mensagem' => 'Modalidade cadastrada com sucesso!']);
    }

    private function saveMidiaExtra($doc, Modalidade $modalidade)
    {
        $documento = new MidiaExtra();
        $documento->modalidade_id = $modalidade->id;

        foreach ($doc as $indice => $parametro) {
            if ($indice != 0) {
                $documento->$parametro = true;
            } else {
                $documento->nome = $parametro;
            }
        }
        $documento->save();
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
        $evento = $modalidadeEdit->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        // dd($request);
        $validatedData = $request->validate([
            'nome'.$request->modalidadeEditId                   => ['required', 'string'],
            'inícioSubmissão'.$request->modalidadeEditId        => ['required', 'date'],
            'fimSubmissão'.$request->modalidadeEditId           => ['required', 'date', 'after:inícioSubmissão'.$request->modalidadeEditId],
            'inícioRevisão'.$request->modalidadeEditId          => ['nullable', 'date', 'after:inícioSubmissão'.$request->modalidadeEditId],
            'fimRevisão'.$request->modalidadeEditId             => ['nullable', 'date', 'after:inícioRevisão'.$request->modalidadeEditId],

            'inícioCorreção'.$request->modalidadeEditId         => ['nullable', 'date', 'after:fimDaRevisão'.$request->modalidadeEditId, 'required_with:fimCorreção'.$request->modalidadeEditId],
            'fimCorreção'.$request->modalidadeEditId            => ['nullable', 'date', 'after:inícioCorreção'.$request->modalidadeEditId, 'required_with:inícioCorreção'.$request->modalidadeEditId],
            'inícioValidação'.$request->modalidadeEditId        => ['nullable', 'date', 'after:fimCorreção'.$request->modalidadeEditId, 'required_with:fimValidação'.$request->modalidadeEditId],
            'fimValidação'.$request->modalidadeEditId           => ['nullable', 'date', 'after:inícioValidação'.$request->modalidadeEditId, 'required_with:inícioValidação'.$request->modalidadeEditId],

            'resultado'.$request->modalidadeEditId              => ['required', 'date', 'after:fimRevisão'.$request->modalidadeEditId],
            'texto'.$request->modalidadeEditId                  => ['nullable'],
            'limit'.$request->modalidadeEditId                  => ['nullable'],
            'arquivoEdit'.$request->modalidadeEditId            => ['nullable'],
            'apresentacao'                                      => ['nullable'],
            'pdf'.$request->modalidadeEditId                    => ['nullable'],
            'jpg'.$request->modalidadeEditId                    => ['nullable'],
            'jpeg'.$request->modalidadeEditId                   => ['nullable'],
            'png'.$request->modalidadeEditId                    => ['nullable'],
            'docx'.$request->modalidadeEditId                   => ['nullable'],
            'odt'.$request->modalidadeEditId                    => ['nullable'],
            'zip'.$request->modalidadeEditId                    => ['nullable'],
            'svg'.$request->modalidadeEditId                    => ['nullable'],
            'mp4'.$request->modalidadeEditId                    => ['nullable'],
            'mp3'.$request->modalidadeEditId                    => ['nullable'],

            'deleteapresentacao'                                => ['nullable'],
            'deleteregra'                                       => ['nullable'],
            'deletetemplate'                                    => ['nullable'],
            'deleteinstrucoes'                                  => ['nullable'],

            'mincaracteres'.$request->modalidadeEditId          => ['nullable', 'integer'],
            'maxcaracteres'.$request->modalidadeEditId          => ['nullable', 'integer'],
            'minpalavras'.$request->modalidadeEditId            => ['nullable', 'integer'],
            'maxpalavras'.$request->modalidadeEditId            => ['nullable', 'integer'],
            'arquivoRegras'.$request->modalidadeEditId          => ['nullable', 'file', 'max:2048'],
            'arquivoInstrucoes'.$request->modalidadeEditId      => ['nullable', 'file', 'max:2048'],
            'arquivoModelos'.$request->modalidadeEditId         => ['nullable', 'file', 'max:2048'],
            'arquivoTemplates'.$request->modalidadeEditId       => ['nullable', 'file', 'max:2048'],
        ]);
        $caracteres = $modalidadeEdit->caracteres;
        $palavras = $modalidadeEdit->palavras;

        if ($request->input('maxcaracteres'.$request->modalidadeEditId) != null && $request->input('mincaracteres'.$request->modalidadeEditId) != null && $request->input('maxcaracteres'.$request->modalidadeEditId) <= $request->input('mincaracteres'.$request->modalidadeEditId)) {
            return redirect()->back()->withErrors(['comparacaocaracteres' => 'Limite máximo de caracteres é menor que limite minimo. Corrija!'])->withInput($request->all());;
        }
        if ($request->input('maxpalavras'.$request->modalidadeEditId) != null && $request->input('minpalavras'.$request->modalidadeEditId) != null && $request->input('maxpalavras'.$request->modalidadeEditId) <= $request->input('minpalavras'.$request->modalidadeEditId)) {
            return redirect()->back()->withErrors(['comparacaopalavras' => 'Limite máximo de palavras é menor que limite minimo. Corrija!'])->withInput($request->all());;
        }

        // Condição para opção de caracteres escolhida
        if ($request->input('limit'.$request->modalidadeEditId) == 'limit-option1') {
            // Verifica se um campo foi deixado em branco
            if ($request->input('mincaracteres'.$request->modalidadeEditId) == null || $request->input('maxcaracteres'.$request->modalidadeEditId) == null) {
                return redirect()->back()->withErrors(['semcaractere' => 'A opção caractere foi escolhida, porém nenhum ou um dos valores não foi passado'])->withInput($request->all());;
            }
            $caracteres = true;
            $palavras = false;
            $modalidadeEdit->maxcaracteres = $request->input('maxcaracteres'.$request->modalidadeEditId);
            $modalidadeEdit->mincaracteres = $request->input('mincaracteres'.$request->modalidadeEditId);
            $modalidadeEdit->minpalavras = null;
            $modalidadeEdit->maxpalavras = null;
        }
        // Condição para opção de palavras escolhida
        if ($request->input('limit'.$request->modalidadeEditId) == 'limit-option2') {
            // Verifica se um campo foi deixado em branco
            if ($request->input('minpalavras'.$request->modalidadeEditId) == null || $request->input('maxpalavras'.$request->modalidadeEditId) == null) {
                return redirect()->back()->withErrors(['sempalavra' => 'A opção palavra foi escolhida, porém nenhum ou um dos valores não foi passado'])->withInput($request->all());;
            }
            $caracteres = false;
            $palavras = true;
            $modalidadeEdit->maxcaracteres = null;
            $modalidadeEdit->mincaracteres = null;
            $modalidadeEdit->minpalavras = $request->input('minpalavras'.$request->modalidadeEditId);
            $modalidadeEdit->maxpalavras = $request->input('maxpalavras'.$request->modalidadeEditId);
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
        if ($request->input('arquivoEdit'.$request->modalidadeEditId) == true) {
            if ($request->input('pdf'.$request->modalidadeEditId) == null && $request->input('jpg'.$request->modalidadeEditId) == null && $request->input('jpeg'.$request->modalidadeEditId) == null && $request->input('png'.$request->modalidadeEditId) == null && $request->input('docx'.$request->modalidadeEditId) == null && $request->input('odt'.$request->modalidadeEditId) == null && $request->input('odt'.$request->modalidadeEditId) == null && $request->input('svg'.$request->modalidadeEditId) == null && $request->input('mp4'.$request->modalidadeEditId) == null && $request->input('mp3'.$request->modalidadeEditId) == null) {
                return redirect()->back()->withErrors(['marcarextensao' => 'O campo arquivo foi selecionado, mas nenhuma extensão foi selecionada.'])->withInput($request->all());;
            }

            $modalidadeEdit->pdf = $request->input('pdf'.$request->modalidadeEditId);
            $modalidadeEdit->jpg = $request->input('jpg'.$request->modalidadeEditId);
            $modalidadeEdit->jpeg = $request->input('jpeg'.$request->modalidadeEditId);
            $modalidadeEdit->png = $request->input('png'.$request->modalidadeEditId);
            $modalidadeEdit->docx = $request->input('docx'.$request->modalidadeEditId);
            $modalidadeEdit->odt = $request->input('odt'.$request->modalidadeEditId);
            $modalidadeEdit->zip = $request->input('zip'.$request->modalidadeEditId);
            $modalidadeEdit->svg = $request->input('svg'.$request->modalidadeEditId);
            $modalidadeEdit->mp4 = $request->input('mp4'.$request->modalidadeEditId);
            $modalidadeEdit->mp3 = $request->input('mp3'.$request->modalidadeEditId);
        } else {
            $modalidadeEdit->pdf = false;
            $modalidadeEdit->jpg = false;
            $modalidadeEdit->jpeg = false;
            $modalidadeEdit->png = false;
            $modalidadeEdit->docx = false;
            $modalidadeEdit->odt = false;
            $modalidadeEdit->zip = false;
            $modalidadeEdit->svg = false;
            $modalidadeEdit->mp4 = false;
            $modalidadeEdit->mp3 = false;
        }

        if ($request->apresentacao) {
            if (! $request->presencial && ! $request->remoto && ! $request->a_distancia && ! $request->semipresencial) {
                return redirect()->back()->withErrors(['apresentacao' => 'Selecione pelos menos um tipo de apresentação.'])->withInput($request->all());
            }
        }

        $modalidadeEdit->nome = $request->input('nome'.$request->modalidadeEditId);
        $modalidadeEdit->inicioSubmissao = $request->input('inícioSubmissão'.$request->modalidadeEditId);
        $modalidadeEdit->fimSubmissao = $request->input('fimSubmissão'.$request->modalidadeEditId);
        $modalidadeEdit->inicioRevisao = $request->input('inícioRevisão'.$request->modalidadeEditId);
        $modalidadeEdit->fimRevisao = $request->input('fimRevisão'.$request->modalidadeEditId);
        $modalidadeEdit->inicioCorrecao = $request->input('inícioCorreção'.$request->modalidadeEditId);
        $modalidadeEdit->fimCorrecao = $request->input('fimCorreção'.$request->modalidadeEditId);
        $modalidadeEdit->inicioValidacao = $request->input('inícioValidação'.$request->modalidadeEditId);
        $modalidadeEdit->fimValidacao = $request->input('fimValidação'.$request->modalidadeEditId);
        $modalidadeEdit->inicioResultado = $request->input('resultado'.$request->modalidadeEditId);
        $modalidadeEdit->texto = $request->input('texto'.$request->modalidadeEditId);
        $modalidadeEdit->arquivo = $request->input('arquivoEdit'.$request->modalidadeEditId);
        $modalidadeEdit->caracteres = $caracteres;
        $modalidadeEdit->palavras = $palavras;
        $modalidadeEdit->apresentacao = $request->apresentacao ? true : false;

        // dd($request->file('arquivoRegras'.$request->modalidadeEditId));
        if ($request->file('arquivoRegras'.$request->modalidadeEditId) != null) {
            $path = $modalidadeEdit->regra;
            Storage::delete($path);

            $fileRegras = $request->file('arquivoRegras'.$request->modalidadeEditId);

            $pathRegras = 'regras/'.$modalidadeEdit->nome.'/';
            $nomeRegras = $request->file('arquivoRegras'.$request->modalidadeEditId)->getClientOriginalName();

            Storage::putFileAs($pathRegras, $fileRegras, $nomeRegras);

            $modalidadeEdit->regra = $pathRegras.$nomeRegras;

            $modalidadeEdit->save();
        }

        if ($request->input('deleteregra') != null) {
            $path = $modalidadeEdit->regra;
            if (Storage::disk()->exists($modalidadeEdit->regra)) {
                Storage::delete($path);
            }
            $modalidadeEdit->regra = null;
        }

        if ($request->file('arquivoInstrucoes'.$request->modalidadeEditId) != null) {
            $path = $modalidadeEdit->instrucoes;
            if (Storage::disk()->exists($path)) {
                Storage::delete($path);
            }

            $modalidadeEdit->instrucoes = $request->file('arquivoInstrucoes'.$request->modalidadeEditId)->store('instrucoes/'.$modalidadeEdit->nome);
            $modalidadeEdit->save();
        }

        if ($request->input('deleteinstrucoes') != null) {
            $path = $modalidadeEdit->instrucoes;
            if (Storage::disk()->exists($path)) {
                Storage::delete($path);
            }
            $modalidadeEdit->instrucoes = null;
            $modalidadeEdit->save();
        }

        if ($request->file('arquivoTemplates'.$request->modalidadeEditId)) {
            $path = $modalidadeEdit->template;
            Storage::delete($path);

            $fileTemplates = $request->file('arquivoTemplates'.$request->modalidadeEditId);
            $pathTemplates = 'templates/'.$modalidadeEdit->nome.'/';
            $nomeTemplates = $request->file('arquivoTemplates'.$request->modalidadeEditId)->getClientOriginalName();

            Storage::putFileAs($pathTemplates, $fileTemplates, $nomeTemplates);

            $modalidadeEdit->template = $pathTemplates.$nomeTemplates;

            $modalidadeEdit->save();
        }

        if ($request->input('deletetemplate') != null) {
            $path = $modalidadeEdit->template;
            if (Storage::disk()->exists($modalidadeEdit->template)) {
                Storage::delete($path);
            }
            $modalidadeEdit->template = null;
        }

        if ($request->file('arquivoModelos'.$request->modalidadeEditId)) {
            $path = $modalidadeEdit->modelo_apresentacao;
            Storage::delete($path);

            $fileModelos = $request->file('arquivoModelos'.$request->modalidadeEditId);
            $pathModelos = 'modelos/'.$modalidadeEdit->nome.'/';
            $nomeModelos = $request->file('arquivoModelos'.$request->modalidadeEditId)->getClientOriginalName();

            Storage::putFileAs($pathModelos, $fileModelos, $nomeModelos);

            $modalidadeEdit->modelo_apresentacao = $pathModelos.$nomeModelos;

            $modalidadeEdit->save();
        }

        if ($request->input('deleteapresentacao') != null) {
            $path = $modalidadeEdit->modelo_apresentacao;
            if (Storage::disk()->exists($modalidadeEdit->modelo_apresentacao)) {
                Storage::delete($path);
            }
            $modalidadeEdit->modelo_apresentacao = null;
        }

        if ($request->apresentacao) {
            $presencial = $modalidadeEdit->tiposApresentacao()->where('tipo', "Presencial")->first();
            $remoto = $modalidadeEdit->tiposApresentacao()->where('tipo', "Remoto")->first();
            $a_distancia = $modalidadeEdit->tiposApresentacao()->where('tipo', "À distância")->first();
            $semipresencial = $modalidadeEdit->tiposApresentacao()->where('tipo', "Semipresencial")->first();

            if ($request->presencial && is_null($presencial)) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Presencial";
                $tipo->modalidade_id = $modalidadeEdit->id;
                $tipo->save();
            } elseif (! $request->presencial && ! is_null($presencial)) {
                $presencial->delete();
            }

            if ($request->remoto  && is_null($remoto)) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Remoto";
                $tipo->modalidade_id = $modalidadeEdit->id;
                $tipo->save();
            } elseif (! $request->remoto && ! is_null($remoto)) {
                $remoto->delete();
            }

            if ($request->a_distancia  && is_null($a_distancia)) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "À distância";
                $tipo->modalidade_id = $modalidadeEdit->id;
                $tipo->save();
            } elseif (! $request->a_distancia && ! is_null($a_distancia)) {
                $a_distancia->delete();
            }

            if ($request->semipresencial  && is_null($semipresencial)) {
                $tipo = new TipoApresentacao();
                $tipo->tipo = "Semipresencial";
                $tipo->modalidade_id = $modalidadeEdit->id;
                $tipo->save();
            } elseif (! $request->semipresencial && ! is_null($semipresencial)) {
                $semipresencial->delete();
            }
        }
        $this->editMidiaExtras($request, $modalidadeEdit);

        $modalidadeEdit->save();
        // dd($modalidadeEdit);

        return redirect()->back()->with(['mensagem' => 'Modalidade salva com sucesso!']);
    }

    private function editMidiaExtras(Request $request, Modalidade $modalidade)
    {
        if($request->docsID != null){
            $docsEditados = MidiaExtra::whereIn('id', $request->docsID)->get();
            $indice = count($request->docsID);
        }else{
            $docsEditados = collect();
            $indice = 0;
        }
        $docsExcluidos = $modalidade->midiasExtra->diff($docsEditados);
        if($request->documentosExtra != null){
            if(count($request->documentosExtra) - $docsEditados->count() != 0){
                $docsNovos = array_slice($request->documentosExtra, -(count($request->documentosExtra) - $docsEditados->count()));
            }else{
                $docsNovos = collect();
            }
        }else{
            $docsNovos = collect();
            $indice = 0;
        }

        //novos documentos

        foreach($docsNovos as $i => $doc){
            $documento = new MidiaExtra();
            $documento->modalidade_id = $modalidade->id;
            if (count($doc) <= 1) {
                return redirect()->back()->withErrors(['documentosExtra'.$indice => 'Selecione pelos menos um tipo de extensão.'])->withInput($request->all());
            }
            foreach ($doc as $indice => $parametro) {
                if ($indice != 0) {
                    $documento->$parametro = true;
                } else {
                    $documento->nome = $parametro;
                }
            }
            $documento->save();
        }

        //Excluindo docs
        if ($docsExcluidos != null && $docsExcluidos->count() > 0) {
            foreach ($docsExcluidos as $doc) {
                $doc->delete();
            }
        }

        //Editando docs
        if ($docsEditados != null && $docsEditados->count() > 0) {
            foreach($request->docsID as $i => $id) {
                $doc = MidiaExtra::find($id);
                if ($doc) {
                    if (count($request->documentosExtra[$i]) <= 1) {
                        return redirect()->back()->withErrors(['documentosExtra'.$i => 'Selecione pelos menos um tipo de extensão.'])->withInput($request->all());
                    }
                    foreach ($request->documentosExtra[$i] as $indice => $parametro) {
                        if ($indice != 0) {
                            $doc->$parametro = true;
                        } else {
                            $doc->nome = $parametro;
                        }
                    }
                    $doc->update();
                }

            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modalidade  $modalidade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modalidade = Modalidade::find($id);
        $evento = $modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        if (count($modalidade->revisores) > 0) {
            return redirect()->back()->withErrors(['excluirModalidade' => 'Não é possível excluir, existem revisores ligados a essa modalidade.']);
        }

        if (count($modalidade->trabalho) > 0) {
            return redirect()->back()->withErrors(['excluirModalidade' => 'Não é possível excluir, existem trabalhos submetidos ligados a essa modalidade.']);
        }

        $modalidade->delete();

        return redirect()->back()->with(['mensagem' => 'Modalidade excluida com sucesso!']);
    }

    public function downloadRegras($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->regra)) {
            $file = Storage::get($modalidade->regra);
            $tipo = Storage::mimeType($modalidade->regra);

            $response = Response::make($file, 200, [
                'Content-Type' => $tipo,
                'Content-Disposition' => 'inline; filename='.$modalidade->nome.' regras.pdf',
            ]);

            return $response;
        }

        return abort(404);
    }

    public function downloadInstrucoes(Modalidade $modalidade)
    {
        if (Storage::exists($modalidade->instrucoes)) {
            return Storage::download($modalidade->instrucoes);
        }

        return abort(404);
    }

    public function downloadModelos($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->modelo_apresentacao)) {
            return Storage::download($modalidade->modelo_apresentacao, 'Modelos.'.explode('.', $modalidade->modelo_apresentacao)[1]);
        }

        return abort(404);
    }

    public function downloadTemplate($id)
    {
        $modalidade = Modalidade::find($id);

        if (Storage::disk()->exists($modalidade->template)) {
            return Storage::download($modalidade->template, 'Template.'.explode('.', $modalidade->template)[1]);
        }

        return abort(404);
    }
}
