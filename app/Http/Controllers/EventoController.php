<?php

namespace App\Http\Controllers;

use App\Area;
use App\Evento;
use App\Coautor;
use App\Criterio;
use App\Revisor;
use App\Atribuicao;
use App\Modalidade;
use App\ComissaoEvento;
use App\User;
use App\Trabalho;
use App\AreaModalidade;
use App\FormEvento;
use App\FormSubmTraba;
use App\RegraSubmis;
use App\TemplateSubmis;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Endereco;
use App\Mail\EventoCriado;
use Illuminate\Support\Facades\Mail;


class EventoController extends Controller
{
    public function index()
    {
        //
        $eventos = Evento::all();
        // dd($eventos);
        return view('coordenador.home',['eventos'=>$eventos]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('evento.criarEvento');
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

        // dd($request);
        // validar datas nulas antes, pois pode gerar um bug

        if(
          $request->dataInicio == null      ||
          $request->dataFim == null
          // $request->inicioSubmissao == null ||
          // $request->fimSubmissao == null    ||
          // $request->inicioRevisao == null   ||
          // $request->fimRevisao == null      ||
          // $request->inicioResultado == null ||
          // $request->fimResultado == null
        ){
          $validatedData = $request->validate([
            'nome'                => ['required', 'string'],
            // 'numeroParticipantes' => ['required', 'integer', 'gt:0'],
            'descricao'           => ['required', 'string'],
            'tipo'                => ['required', 'string'],
            'dataInicio'          => ['required', 'date','after:'. $yesterday],
            'dataFim'             => ['required', 'date'],
            // 'inicioSubmissao'     => ['required', 'date'],
            // 'fimSubmissao'        => ['required', 'date'],
            // 'inicioRevisao'       => ['required', 'date'],
            // 'fimRevisao'          => ['required', 'date'],
            // 'inicioResultado'     => ['required', 'date'],
            // 'fimResultado'        => ['required', 'date'],
            // 'valorTaxa'           => ['required', 'integer'],
            'fotoEvento'          => ['file', 'mimes:png'],
          ]);
        }

        // validacao normal

        $validatedData = $request->validate([
          'nome'                => ['required', 'string'],
          // 'numeroParticipantes' => ['required', 'integer', 'gt:0'],
          'descricao'           => ['required', 'string'],
          'tipo'                => ['required', 'string'],
          'dataInicio'          => ['required', 'date', 'after:' . $yesterday],
          'dataFim'             => ['required', 'date', 'after:' . $request->dataInicio],
          // 'inicioSubmissao'     => ['required', 'date', 'after:' . $yesterday],
          // 'fimSubmissao'        => ['required', 'date', 'after:' . $request->inicioSubmissao],
          // 'inicioRevisao'       => ['required', 'date', 'after:' . $yesterday],
          // 'fimRevisao'          => ['required', 'date', 'after:' . $request->inicioRevisao],
          // 'inicioResultado'     => ['required', 'date', 'after:' . $yesterday],
          // 'fimResultado'        => ['required', 'date', 'after:' . $request->inicioResultado],
          // 'valorTaxa'           => ['required', 'integer'],
          'fotoEvento'          => ['file', 'mimes:png'],
        ]);

        // validar endereco

        $validatedData = $request->validate([
          'rua'                 => ['required', 'string'],
          'numero'              => ['required', 'string'],
          'bairro'              => ['required', 'string'],
          'cidade'              => ['required', 'string'],
          'uf'                  => ['required', 'string'],
          'cep'                 => ['required', 'string'],
        ]);

        $endereco = Endereco::create([
          'rua'                 => $request->rua,
          'numero'              => $request->numero,
          'bairro'              => $request->bairro,
          'cidade'              => $request->cidade,
          'uf'                  => $request->uf,
          'cep'                 => $request->cep,
        ]);

        $evento = Evento::create([
          'nome'                => $request->nome,
          // 'numeroParticipantes' => $request->numeroParticipantes,
          'descricao'           => $request->descricao,
          'tipo'                => $request->tipo,
          'dataInicio'          => $request->dataInicio,
          'dataFim'             => $request->dataFim,
          // 'inicioSubmissao'     => $request->inicioSubmissao,
          // 'fimSubmissao'        => $request->fimSubmissao,
          // 'inicioRevisao'       => $request->inicioRevisao,
          // 'fimRevisao'          => $request->fimRevisao,
          // 'inicioResultado'     => $request->inicioResultado,
          // 'fimResultado'        => $request->fimResultado,
          // 'possuiTaxa'          => $request->possuiTaxa,
          // 'valorTaxa'           => $request->valorTaxa,
          'enderecoId'          => $endereco->id,
          'coordenadorId'       => Auth::user()->id,
        ]);

        // se o evento tem foto

        if($request->fotoEvento != null){
          $file = $request->fotoEvento;
          $path = 'public/eventos/' . $evento->id;
          $nome = '/logo.png';
          Storage::putFileAs($path, $file, $nome);
          $evento->fotoEvento = 'eventos/' . $evento->id . $nome;
          $evento->save();
        }

        // se vou me tornar coordenador do Evento

        // if($request->isCoordenador == true){
        //   $evento->coordenadorId = Auth::user()->id;
        //   $evento->save();
        // }

        $evento->coordenadorId = Auth::user()->id;
        $evento->publicado = false;
        $evento->save();

        $user = Auth::user();
        $subject = "Evento Criado";
        Mail::to($user->email)
            ->send(new EventoCriado($user, $subject));
        
        // Passando dados default para a edição das etiquetas
        // dos campos do card de eventos.

        $FormEvento = FormEvento::create([
          'etiquetanomeevento'             => 'Nome',
          'etiquetatipoevento'             => 'Tipo',
          'etiquetadescricaoevento'        => 'Descrição',
          'etiquetadatas'                  => 'Realização',
          'etiquetasubmissoes'             => 'Submissões',
          'etiquetabaixarregra'            => 'Regras',
          'etiquetabaixartemplate'         => 'Template',
          'etiquetaenderecoevento'         => 'Endereço',
          'etiquetamoduloinscricao'        => 'Inscrições',
          'etiquetamoduloprogramacao'      => 'Programação',
          'etiquetamoduloorganizacao'      => 'Organização',
          'eventoId'                       => $evento->id,
        ]);

        // Passando dados default para a edição das etiquetas
        // dos campos da submissão de trabalhos.
        $FormSubmTraba = FormSubmTraba::create([
          'etiquetatitulotrabalho'         => 'Titulo',
          'etiquetaautortrabalho'          => 'Autor',
          'etiquetacoautortrabalho'        => 'Co-Autor',
          'etiquetaresumotrabalho'         => 'Resumo',
          'etiquetaareatrabalho'           => 'Área',
          'etiquetauploadtrabalho'         => 'Upload de Trabalho',
          'etiquetacampoextra1'            => 'Campo Extra',
          'etiquetacampoextra2'            => 'Campo Extra',
          'etiquetacampoextra3'            => 'Campo Extra',
          'etiquetacampoextra4'            => 'Campo Extra',
          'etiquetacampoextra5'            => 'Campo Extra',
          'ordemCampos'                    => 'etiquetatitulotrabalho,etiquetaautortrabalho,etiquetacoautortrabalho,etiquetaresumotrabalho,etiquetaareatrabalho,etiquetauploadtrabalho,checkcampoextra1,etiquetacampoextra1,select_campo1,checkcampoextra2,etiquetacampoextra2,select_campo2,checkcampoextra3,etiquetacampoextra3,select_campo3,checkcampoextra4,etiquetacampoextra4,select_campo4,checkcampoextra5,etiquetacampoextra5,select_campo5',
          'eventoId'                       => $evento->id,
        ]);
        
        return redirect()->route('coord.home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evento = Evento::find($id);
        $hasTrabalho = false;
        $hasTrabalhoCoautor = false;
        $hasFile = false;
        $trabalhos = Trabalho::where('autorId', Auth::user()->id)->get();
        $trabalhosCount = Trabalho::where('autorId', Auth::user()->id)->count();
        $trabalhosId = Trabalho::where('eventoId', $evento->id)->select('id')->get();
        $trabalhosIdCoautor = Coautor::whereIn('trabalhoId', $trabalhosId)->where('autorId', Auth::user()->id)->select('trabalhoId')->get();
        $coautorCount = Coautor::whereIn('trabalhoId', $trabalhosId)->where('autorId', Auth::user()->id)->count();
        $trabalhosCoautor = Trabalho::whereIn('id', $trabalhosIdCoautor)->get();
        $modalidades = Modalidade::where('eventoId', $evento->id)->get();
        
        if($trabalhosCount != 0){
          $hasTrabalho = true;
          $hasFile = true;
        }
        if($coautorCount != 0){
          $hasTrabalhoCoautor = true;
          $hasFile = true;
        }

        $mytime = Carbon::now('America/Recife');
        $etiquetas = FormEvento::where('eventoId',$evento->id)->first();

        $formSubTraba = FormSubmTraba::all();

        return view('evento.visualizarEvento', [
                                                'evento'              => $evento,
                                                'trabalhos'           => $trabalhos,
                                                'trabalhosCoautor'    => $trabalhosCoautor,
                                                'hasTrabalho'         => $hasTrabalho,
                                                'hasTrabalhoCoautor'  => $hasTrabalhoCoautor,
                                                'hasFile'             => $hasFile,
                                                'mytime'              => $mytime,
                                                'etiquetas'           => $etiquetas,
                                                'modalidades'         => $modalidades,
                                                'formSubTraba'        => $formSubTraba,
                                               ]);
    }

    public function showNaoLogado($id)
    {
        $evento = Evento::find($id);
        $hasTrabalho = false;
        $hasTrabalhoCoautor = false;
        $hasFile = false;
        $trabalhos = null;
        $trabalhosCoautor = null;

        $mytime = Carbon::now('America/Recife');
        // dd(false);
        return view('evento.visualizarEvento', [
                                                'evento'              => $evento,
                                                'trabalhos'           => $trabalhos,
                                                'trabalhosCoautor'    => $trabalhosCoautor,
                                                'hasTrabalho'         => $hasTrabalho,
                                                'hasTrabalhoCoautor'  => $hasTrabalhoCoautor,
                                                'hasFile'             => $hasFile,
                                                'mytime'              => $mytime
                                               ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $evento = Evento::find($id);
        $endereco = Endereco::find($evento->enderecoId);
        return view('evento.editarEvento',['evento'=>$evento,'endereco'=>$endereco]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $mytime = Carbon::now('America/Recife');
        $evento = Evento::find($id);
        // dd($request);
        // validar datas nulas antes, pois pode gerar um bug

        if($request->dataInicio == null || $request->dataFim == null){
          $validatedData = $request->validate([
            'nome'                => ['required', 'string'],
            'descricao'           => ['required', 'string'],
            'tipo'                => ['required', 'string'],
            'dataInicio'          => ['required', 'date', 'after_or_equal:'. $evento->dataInicio],
            'dataFim'             => ['required', 'date', 'after:'. $request->dataInicio],
            'fotoEvento'          => ['file', 'mimes:png'],
          ]);
        }

        // validacao normal

        $validatedData = $request->validate([
          'nome'                => ['required', 'string'],
          'descricao'           => ['required', 'string'],
          'tipo'                => ['required', 'string'],
          'dataInicio'          => ['required', 'date', 'after_or_equal:' . $evento->dataInicio],
          'dataFim'             => ['required', 'date', 'after:' . $request->dataInicio],
          'fotoEvento'          => ['file', 'mimes:png'],
        ]);

        // validar endereco

        $validatedData = $request->validate([
          'rua'                 => ['required', 'string'],
          'numero'              => ['required', 'string'],
          'bairro'              => ['required', 'string'],
          'cidade'              => ['required', 'string'],
          'uf'                  => ['required', 'string'],
          'cep'                 => ['required', 'string'],
        ]);
        
        $endereco = Endereco::find($evento->enderecoId);

        $evento->nome                 = $request->nome;
        $evento->descricao            = $request->descricao;
        $evento->tipo                 = $request->tipo;
        $evento->dataInicio           = $request->dataInicio;
        $evento->dataFim              = $request->dataFim;
        $evento->enderecoId           = $endereco->id;
        
        // se a foto for diferente de nula apaga a foto existente e salva a nova
        if($request->fotoEvento != null){
          if(Storage::disk()->exists('public/'.$evento->fotoEvento)) {
            Storage::delete($evento->fotoEvento);
          }
          $file = $request->fotoEvento;
          $path = 'public/eventos/' . $evento->id;
          $nome = '/logo.png';
          Storage::putFileAs($path, $file, $nome);
          $evento->fotoEvento = 'eventos/' . $evento->id . $nome;
        }
        $evento->save();

        $endereco->rua                = $request->rua;
        $endereco->numero             = $request->numero;
        $endereco->bairro             = $request->bairro;
        $endereco->cidade             = $request->cidade;
        $endereco->uf                 = $request->uf;
        $endereco->cep                = $request->cep;
        $endereco->save();

        // $eventos = Evento::all();
        return redirect( route('home') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);
        // dd($id);
        $endereco = Endereco::find($evento->enderecoId);
        $evento->delete();
        $endereco->delete();

        return redirect()->back();
    }

    public function detalhes(Request $request){
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenador', $evento);

        $ComissaoEvento = ComissaoEvento::where('eventosId',$evento->id)->get();
        // dd($ComissaoEventos);
        $ids = [];
        foreach($ComissaoEvento as $ce){
          array_push($ids,$ce->userId);
        }
        $users = User::find($ids);

        $areas = Area::where('eventoId', $evento->id)->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        $trabalhosId = Trabalho::whereIn('areaId', $areasId)->select('id')->get();
        $revisores = Revisor::where('eventoId', $evento->id)->get();
        $modalidades = Modalidade::where('eventoId', $evento->id)->get();
        $areaModalidades = AreaModalidade::whereIn('areaId', $areasId)->get();        
        $trabalhos = Trabalho::whereIn('areaId', $areasId)->orderBy('id')->get();
        $trabalhosEnviados = Trabalho::whereIn('areaId', $areasId)->count();
        $trabalhosPendentes = Trabalho::whereIn('areaId', $areasId)->where('avaliado', 'processando')->count();
        $trabalhosAvaliados = Atribuicao::whereIn('trabalhoId', $trabalhosId)->where('parecer', '!=', 'processando')->count();

        $numeroRevisores = Revisor::where('eventoId', $evento->id)->count();
        $numeroComissao = ComissaoEvento::where('eventosId',$evento->id)->count();
        // $atribuicoesProcessando
        // dd($trabalhosEnviados);
        $revs = Revisor::where('eventoId', $evento->id)->with('user')->get();
        $etiquetas = FormEvento::where('eventoId', $evento->id)->first(); //etiquetas do card de eventos
        $etiquetasSubTrab = FormSubmTraba::where('eventoId', $evento->id)->first();

        // Criterios por modalidades
        $criteriosModalidade = [];
        foreach ($modalidades as $indice) {
          $criterios = Criterio::where("modalidadeId", $indice->id)->get();
          for ($i=0; $i < count($criterios); $i++) {
            if (!in_array($criterios[$i],$criteriosModalidade)) {
              array_push($criteriosModalidade, $criterios[$i]);
            }
          }
        }
        
        return view('coordenador.detalhesEvento', [
                                                    'evento'                  => $evento,
                                                    'areas'                   => $areas,
                                                    'revisores'               => $revisores,
                                                    'revs'                    => $revs,
                                                    'users'                   => $users,
                                                    'modalidades'             => $modalidades,
                                                    'areaModalidades'         => $areaModalidades,
                                                    'trabalhos'               => $trabalhos,
                                                    'trabalhosEnviados'       => $trabalhosEnviados,
                                                    'trabalhosAvaliados'      => $trabalhosAvaliados,
                                                    'trabalhosPendentes'      => $trabalhosPendentes,
                                                    'numeroRevisores'         => $numeroRevisores,
                                                    'numeroComissao'          => $numeroComissao,
                                                    'etiquetas'               => $etiquetas,
                                                    'etiquetasSubTrab'        => $etiquetasSubTrab,
                                                    'criterios'               => $criteriosModalidade,
                                                  ]);
    }

    public function numTrabalhos(Request $request){
      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);
      $validatedData = $request->validate([
        'eventoId'                => ['required', 'integer'],
        'trabalhosPorAutor'       => ['required', 'integer'],
        'numCoautor'              => ['required', 'integer']
      ]);

      $evento->numMaxTrabalhos = $request->trabalhosPorAutor;
      $evento->numMaxCoautores = $request->numCoautor;
      $evento->save();

      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function setResumo(Request $request){
      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);
      $validatedData = $request->validate([
        'eventoId'                => ['required', 'integer'],
        'hasResumo'               => ['required', 'string']
      ]);
      if($request->hasResumo == 'true'){
        $evento->hasResumo = true;
      }
      else{
        $evento->hasResumo = false;
      }

      $evento->save();
      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function setFotoEvento(Request $request){
      $evento = Evento::find($request->eventoId);
      $this->authorize('isCoordenador', $evento);
      // dd($request);
      $validatedData = $request->validate([
        'eventoId'                => ['required', 'integer'],
        'fotoEvento'              => ['required', 'file', 'mimes:png']
      ]);

      $file = $request->fotoEvento;
      $path = 'public/eventos/' . $evento->id;
      $nome = '/logo.png';
      Storage::putFileAs($path, $file, $nome);
      $evento->fotoEvento = $path . $nome;
      $evento->save();
      return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    public function habilitar($id) {
      $evento = Evento::find($id);
      $evento->publicado = true;
      $evento->update();
      return redirect()->back()->with('mensagem', 'O evento foi exposto ao público.');
    }

    public function desabilitar($id) {
      $evento = Evento::find($id);
      $evento->publicado = false;
      $evento->update();
      return redirect()->back()->with('mensagem', 'O evento foi ocultado ao público.');
    }

    public function downloadFotoEvento($id) {
      $evento = Evento::find($id);
      if (Storage::disk()->exists('public/'.$evento->fotoEvento)) {
        return Storage::download('public/'.$evento->fotoEvento);
      }
      return abort(404);
    }
}
