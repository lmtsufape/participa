<?php

namespace App\Http\Controllers;

use App\Trabalho;
use App\Coautor;
use App\Evento;
use App\User;
use App\AreaModalidade;
use App\Area;
use App\Arquivoextra;
use App\Revisor;
use App\Modalidade;
use App\Atribuicao;
use App\Arquivo;
use App\FormTipoSubm;
use App\FormSubmTraba;
use App\RegraSubmis;
use App\ComissaoEvento;
use App\TemplateSubmis;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\SubmissaoTrabalho;

class TrabalhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $idModalidade)
    {
        $evento = Evento::find($id);
        // $areas = Area::where('eventoId', $evento->id)->get();
        $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        // $revisores = Revisor::where('eventoId', $evento->id)->get();
        // $modalidades = Modalidade::all();        
        $areaModalidades = AreaModalidade::whereIn('areaId', $areasId)->get();        
        // $areasEnomes = Area::wherein('id', $areasId)->get();
        $modalidadesIDeNome = [];
        foreach ($areaModalidades as $key) {
          array_push($modalidadesIDeNome,['areaId' => $key->area->id,
                                          'modalidadeId' => $key->modalidade->id,
                                          'modalidadeNome' => $key->modalidade->nome]);
        }

        // $trabalhos = Trabalho::where('autorId', Auth::user()->id)->whereIn('areaId', $areasId)->get();
        
        // $formtiposubmissao é um vetor com os dados que serão ou
        // não exibidos no formulario de submissão de trabalho. 
        // $formtiposubmissao = FormTipoSubm::where('modalidadeId', $idModalidade)->first();

        // Pegando apenas as areas que possuem relação com a modalidade selecionada
        // ao clciar no botão "submeter trabalho".
        $areaPorModalidade = AreaModalidade::where('modalidadeId', $idModalidade)->select('areaId')->get();
        $areasEspecificas = Area::wherein('id', $areaPorModalidade)->get();

        $formSubTraba = FormSubmTraba::where('eventoId', $evento->id)->first();

        $regra = RegraSubmis::where('modalidadeId', $idModalidade)->first();
        $template = TemplateSubmis::where('modalidadeId', $idModalidade)->first();

        $modalidade = Modalidade::find($idModalidade);
        // dd($formSubTraba);
        return view('evento.submeterTrabalho',[
                                              'evento'                 => $evento,
                                              // 'areas'                  => $areas,
                                              // 'revisores'              => $revisores,
                                              // 'modalidades'            => $modalidades,
                                              // 'areaModalidades'        => $areaModalidades,
                                              // 'trabalhos'              => $trabalhos,
                                              // 'areasEnomes'            => $areasEnomes,
                                              'modalidadesIDeNome'     => $modalidadesIDeNome,
                                              // 'regrasubarq'            => $formtiposubmissao,
                                              'areasEspecificas'       => $areasEspecificas,
                                              // 'modalidadeEspecifica'   => $idModalidade,
                                              'formSubTraba'           => $formSubTraba,
                                              'regras'                 => $regra,
                                              'templates'              => $template,
                                              'modalidade'             => $modalidade,
                                            ]);
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

    public function findResumo(Request $request)
    {
      $trabalhoResumo = Trabalho::find($request->trabalhoId);
      return $trabalhoResumo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $modalidadeId){
      
      //Obtendo apenas os tipos de extensões selecionadas
      $tiposExtensao = Modalidade::find($modalidadeId);
      
      $mytime = Carbon::now('America/Recife');
      $mytime = $mytime->toDateString();
      $evento = Evento::find($request->eventoId);
      if($evento->inicioSubmissao > $mytime){
        if($mytime >= $evento->fimSubmissao){
            return redirect()->route('home');
        }
      }

      $validatedData = $request->validate([
        'nomeTrabalho'      => ['required', 'string',],
        'areaId'            => ['required', 'integer'],
        'modalidadeId'      => ['required', 'integer'],
        'eventoId'          => ['required', 'integer'],
        'resumo'            => ['nullable','string'],
        'nomeCoautor.*'     => ['string'],
        'emailCoautor.*'    => ['string'],
        'arquivo'           => ['nullable', 'file', 'max:2000000'],
        'campoextra1arquivo' => ['nullable', 'file', 'max:2000000'],
        'campoextra2arquivo' => ['nullable', 'file', 'max:2000000'],
        'campoextra3arquivo' => ['nullable', 'file', 'max:2000000'],
        'campoextra4arquivo' => ['nullable', 'file', 'max:2000000'],
        'campoextra5arquivo' => ['nullable', 'file', 'max:2000000'],
        'campoextra1simples' => ['nullable', 'string'],
        'campoextra2simples' => ['nullable', 'string'],
        'campoextra3simples' => ['nullable', 'string'],
        'campoextra4simples' => ['nullable', 'string'],
        'campoextra5simples' => ['nullable', 'string'],
        'campoextra1grande' => ['nullable', 'string'],
        'campoextra2grande' => ['nullable', 'string'],
        'campoextra3grande' => ['nullable', 'string'],
        'campoextra4grande' => ['nullable', 'string'],
        'campoextra5grande' => ['nullable', 'string'],
      ]);
      
      if($tiposExtensao->arquivo == true){

        $tiposcadastrados = [];
        if($tiposExtensao->pdf == true){
          array_push($tiposcadastrados, "pdf");
        }
        if($tiposExtensao->jpg == true){
          array_push($tiposcadastrados, "jpg");
        }
        if($tiposExtensao->jpeg == true){
          array_push($tiposcadastrados, "jpeg");
        }
        if($tiposExtensao->png == true){
          array_push($tiposcadastrados, "png");
        }
        if($tiposExtensao->docx == true){
          array_push($tiposcadastrados, "docx");
        }
        if($tiposExtensao->odt == true){
          array_push($tiposcadastrados, "odt");
        }

        $extensao = $request->arquivo->getClientOriginalExtension();
        if(!in_array($extensao, $tiposcadastrados)){
          return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
          Verifique no formulário, quais os tipos permitidos.']);
        }
      }
      
      $autor = Auth::user();
      $trabalhosDoAutor = Trabalho::where('eventoId', $request->eventoId)->where('autorId', Auth::user()->id)->count();
      $areaModalidade = AreaModalidade::where('areaId', $request->areaId)->where('modalidadeId', $request->modalidadeId)->first();
      Log::debug('Numero de trabalhos' . $evento);
      if($trabalhosDoAutor >= $evento->numMaxTrabalhos){
        return redirect()->back()->withErrors(['numeroMax' => 'Número máximo de trabalhos permitidos atingido.']);
      }

      if($request->emailCoautor != null){
        $i = 0;
        foreach ($request->emailCoautor as $key) {
          $i++;
        }
        if($i > $evento->numMaxCoautores){
          return redirect()->back()->withErrors(['numeroMax' => 'Número de coautores deve ser menor igual a '.$evento->numMaxCoautores]);
        }
      }

      if($request->emailCoautor != null){
        $i = 0;
        foreach ($request->emailCoautor as $key) {
          $userCoautor = User::where('email', $key)->first();
          if($userCoautor == null){
            $passwordTemporario = Str::random(8);
            Mail::to($key)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Coautor', $evento->nome, $passwordTemporario));
            $usuario = User::create([
              'email' => $key,
              'password' => bcrypt($passwordTemporario),
              'usuarioTemp' => true,
              'name' => $request->nomeCoautor[$i],
            ]);
          }
          $i++;
        }
      }
      
      $trabalho = Trabalho::create([
        'titulo' => $request->nomeTrabalho,
        'resumo' => $request->resumo,
        'modalidadeId'  => $areaModalidade->modalidade->id,
        'areaId'  => $areaModalidade->area->id,
        'autorId' => $autor->id,
        'eventoId'  => $evento->id,
        'avaliado' => 'nao',
      ]);

      if(isset($request->campoextra1simples)){
        $trabalho->campoextra1simples          = $request->campoextra1simples;
      }
      if(isset($request->campoextra1grande)){
        $trabalho->campoextra1grande           = $request->campoextra1grande;
      }
      if(isset($request->campoextra2simples)){
        $trabalho->campoextra2simples          = $request->campoextra2simples;
      }
      if(isset($request->campoextra2grande)){
        $trabalho->campoextra2grande           = $request->campoextra2grande;
      }
      if(isset($request->campoextra3simples)){
        $trabalho->campoextra3simples          = $request->campoextra3simples;
      }
      if(isset($request->campoextra3grande)){
        $trabalho->campoextra3grande           = $request->campoextra3grande;
      }
      if(isset($request->campoextra4simples)){
        $trabalho->campoextra4simples          = $request->campoextra4simples;
      }
      if(isset($request->campoextra4grande)){
        $trabalho->campoextra4grande           = $request->campoextra4grande;
      }
      if(isset($request->campoextra5simples)){
        $trabalho->campoextra5simples          = $request->campoextra5simples;
      }
      if(isset($request->campoextra5grande)){
        $trabalho->campoextra5grande           = $request->campoextra5grande;
      }

      $trabalho->save();

      if($request->emailCoautor != null){
        foreach ($request->emailCoautor as $key) {
          $userCoautor = User::where('email', $key)->first();
          Coautor::create([
            'ordem' => '-',
            'autorId' => $userCoautor->id,
            'trabalhoId'  => $trabalho->id,
          ]);
        }
      }

      if(isset($request->arquivo)){
        
        $file = $request->arquivo;
        $path = 'trabalhos/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = "1.pdf";
        Storage::putFileAs($path, $file, $nome);

        $arquivo = Arquivo::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
          'versaoFinal' => true,
        ]);
      }

      if(isset($request->campoextra1arquivo)){
        
        $file = $request->campoextra1arquivo;
        $path = 'arquivosextra/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->campoextra1arquivo->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        $arquivoExtra1 = Arquivoextra::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
        ]);
      }

      if(isset($request->campoextra2arquivo)){
        
        $file = $request->campoextra2arquivo;
        $path = 'arquivosextra/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->campoextra2arquivo->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        $arquivoExtra2 = Arquivoextra::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
        ]);
      }

      if(isset($request->campoextra3arquivo)){
        
        $file = $request->campoextra3arquivo;
        $path = 'arquivosextra/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->campoextra3arquivo->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        $arquivoExtra3 = Arquivoextra::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
        ]);
      }

      if(isset($request->campoextra4arquivo)){
        
        $file = $request->campoextra4arquivo;
        $path = 'arquivosextra/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->campoextra4arquivo->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        $arquivoExtra4 = Arquivoextra::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
        ]);
      }

      if(isset($request->campoextra5arquivo)){
        
        $file = $request->campoextra5arquivo;
        $path = 'arquivosextra/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->campoextra5arquivo->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        $arquivoExtra5 = Arquivoextra::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
        ]);
      }
      
      $subject = "Submissão de Trabalho";
      Mail::to($autor->email)
            ->send(new SubmissaoTrabalho($autor, $subject));
      if($request->emailCoautor != null){
        foreach ($request->emailCoautor as $key) {
          $userCoautor = User::where('email', $key)->first();
          Mail::to($userCoautor->email)
            ->send(new SubmissaoTrabalho($userCoautor, $subject));
        }
      }

      return redirect()->route('evento.visualizar',['id'=>$request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function show(Trabalho $trabalho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function edit(Trabalho $trabalho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trabalho $trabalho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trabalho $trabalho)
    {
        //
    }

    public function novaVersao(Request $request){
      $mytime = Carbon::now('America/Recife');
      $mytime = $mytime->toDateString();
      $evento = Evento::find($request->eventoId);
      if($evento->inicioSubmissao > $mytime){
        if($mytime >= $evento->fimSubmissao){
            return redirect()->route('home');
        }
      }
      $validatedData = $request->validate([
        'arquivo' => ['required', 'file', 'mimes:pdf'],
        'eventoId' => ['required', 'integer'],
        'trabalhoId' => ['required', 'integer'],
      ]);

      $trabalho = Trabalho::find($request->trabalhoId);

      if(Auth::user()->id != $trabalho->autorId){
        return redirect()->route('home');
      }

      $arquivos = $trabalho->arquivo;
      $count = 1;
      foreach ($arquivos as $key) {
        $key->versaoFinal = false;
        $key->save();
        $count++;
      }

      $file = $request->arquivo;
      $path = 'trabalhos/' . $request->eventoId . '/' . $trabalho->id .'/';
      $nome = $count . ".pdf";
      Storage::putFileAs($path, $file, $nome);

      $arquivo = Arquivo::create([
        'nome'  => $path . $nome,
        'trabalhoId'  => $trabalho->id,
        'versaoFinal' => true,
      ]);

      return redirect()->route('evento.visualizar',['id'=>$request->eventoId]);
    }

    public function detalhesAjax(Request $request){
      $validatedData = $request->validate([
        'trabalhoId' => ['required', 'integer']
      ]);

      $trabalho = Trabalho::find($request->trabalhoId);
      $revisores = $trabalho->atribuicoes;
      $revisoresAux = [];
      foreach ($revisores as $key) {
        if($key->user->name != null){
          array_push($revisoresAux, [
            'id' => $key->id,
            'nomeOuEmail'  => $key->user->name
          ]);
        }
        else{
          array_push($revisoresAux, [
            'id' => $key->id,
            'nomeOuEmail'  => $key->user->email
          ]);
        }
      }
      $evento = Evento::find($trabalho->eventoId);
      $revisoresDisponeis = $evento->revisores()->where('areaId', $trabalho->areaId)->get();
      $revisoresAux1 = [];
      foreach ($revisoresDisponeis as $key) {
        //verificar se ja é um revisor deste trabalhos
        $revisorNaoExiste = true;
        foreach ($revisoresAux as $key1) {
          if($key->id == $key1['id']){
            $revisorNaoExiste = false;
          }
        }
        //
        if($revisorNaoExiste){
          if($key->user->name != null){
            array_push($revisoresAux1, [
              'id' => $key->id,
              'nomeOuEmail'  => $key->user->name
            ]);
          }
          else{
            array_push($revisoresAux1, [
              'id' => $key->id,
              'nomeOuEmail'  => $key->user->email
            ]);
          }
        }
      }
      return response()->json([
                               'titulo' => $trabalho->titulo,
                               'resumo'  => $trabalho->resumo,
                               'revisores' => $revisoresAux,
                               'revisoresDisponiveis' => $revisoresAux1
                              ], 200);
    }

    //função para download do arquivo do trabalho
    public function downloadArquivo($id) {
      $trabalho = Trabalho::find($id);
      $revisor = Revisor::where([['eventoId', '=', $trabalho->eventoId], ['user_id', '=', auth()->user()->id]])->first();
      $user = User::find(auth()->user()->id);

      /*
        O usuário só tera permissão para baixar o arquivo se for revisor do trabalho
        ou se for coordenador do evento, coordenador da comissão, se pertencer a comissão
        do evento ou se for autor do trabalho.        
      */
      if ($revisor != null) {
        $permissao = Atribuicao::where([['trabalhoId' ,'=', $id], ['revisorId', '=', $revisor->id]]);

        if ($permissao != null) {
          if (Storage::disk()->exists('app/'.$trabalho->arquivo->nome)) {
            return response()->download(storage_path('app/'.$trabalho->arquivo->nome));
          }
          return abort(404);
        }
        return abort(403);
      
      
      } else if ($trabalho->evento->coordenadorId == auth()->user()->id || $trabalho->evento->coordComissaoId == auth()->user()->id || $user->membroComissaoEvento != null && $user->membroComissaoEvento == $trabalho->eventoId || $trabalho->autorId == auth()->user()->id) {
        if (Storage::disk()->exists('app/'.$trabalho->arquivo->nome)) {
          return response()->download(storage_path('app/'.$trabalho->arquivo->nome));
        }
        return abort(404);
      }
      return abort(403);
    }

    public function resultados($id) {
      $evento = Evento::find($id);
      $trabalhos = Trabalho::where('eventoId', $id)->get();
      $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();

      return view('coordenador.trabalhos.resultados')->with(['trabalhos' => $trabalhos,
                                                             'evento' => $evento,
                                                             'areas' => $areas]);
    }
    
    public function pesquisaAjax(Request $request) {
      if ($request->areaId != null) {
        $area_id = $request->areaId;
      } else {
        $area_id = 1;
      }

      if ($request->texto != null) {
        $texto = $request->texto;
      } else {
        $texto = "";
      }

      $trabalhos = Trabalho::where([['areaId', $area_id], ['titulo', 'ilike', '%'. $texto .'%']])->get();

      $trabalhoJson = collect();

      foreach ($trabalhos as $trab) {
        $trabalho = [
          'id'          => $trab->id,
          'titulo'      => $trab->titulo,
          'nome'        => $trab->autor->name,
          'area'        => $trab->area->nome,
          'modalidade'   => $trab->modalidade->nome,
        ];
        $trabalhoJson->push($trabalho);
      }

      return response()->json($trabalhoJson);
    }
}
