<?php

namespace App\Http\Controllers\Submissao;

use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Submissao\Evento;
use App\Models\Users\User;
use App\Models\Submissao\AreaModalidade;
use App\Models\Submissao\Area;
use App\Models\Submissao\Avaliacao;
use App\Models\Submissao\Arquivoextra;
use App\Models\Users\Revisor;
use App\Models\Submissao\Modalidade;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Arquivo;
use App\Models\Submissao\FormTipoSubm;
use App\Models\Submissao\FormSubmTraba;
use App\Models\Submissao\RegraSubmis;
use App\Models\Submissao\Parecer;
use App\Models\Submissao\ComissaoEvento;
use App\Models\Submissao\TemplateSubmis;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\SubmissaoTrabalho;
use App\Http\Controllers\Controller;

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
        $areas = Area::where('eventoId', $evento->id)->orderBy('nome')->get();
        // $areasId = Area::where('eventoId', $evento->id)->select('id')->get();
        // $revisores = Revisor::where('eventoId', $evento->id)->get();
        // $modalidades = Modalidade::all();        
        // $areaModalidades = AreaModalidade::whereIn('areaId', $areasId)->get();        
        // $areasEnomes = Area::wherein('id', $areasId)->get();
        // $modalidadesIDeNome = [];
        // foreach ($areaModalidades as $key) {
        //   array_push($modalidadesIDeNome,['areaId' => $key->area->id,
        //                                   'modalidadeId' => $key->modalidade->id,
        //                                   'modalidadeNome' => $key->modalidade->nome]);
        // }

        // $trabalhos = Trabalho::where('autorId', Auth::user()->id)->whereIn('areaId', $areasId)->get();
        
        // $formtiposubmissao é um vetor com os dados que serão ou
        // não exibidos no formulario de submissão de trabalho. 
        // $formtiposubmissao = FormTipoSubm::where('modalidadeId', $idModalidade)->first();

        // Pegando apenas as areas que possuem relação com a modalidade selecionada
        // ao clciar no botão "submeter trabalho".
        // $areaPorModalidade = AreaModalidade::where('modalidadeId', $idModalidade)->select('areaId')->get();
        // $areasEspecificas = Area::wherein('id', $areaPorModalidade)->get();

        $formSubTraba = FormSubmTraba::where('eventoId', $evento->id)->first();

        $regra = RegraSubmis::where('modalidadeId', $idModalidade)->first();
        $template = TemplateSubmis::where('modalidadeId', $idModalidade)->first();
        $ordemCampos = explode(",", $formSubTraba->ordemCampos);
        $modalidade = Modalidade::find($idModalidade);
        // dd($formSubTraba);
        return view('evento.submeterTrabalho',[
                                              'evento'                 => $evento,
                                              'areas'                  => $areas,
                                              // 'revisores'              => $revisores,
                                              // 'modalidades'            => $modalidades,
                                              // 'areaModalidades'        => $areaModalidades,
                                              // 'trabalhos'              => $trabalhos,
                                              // 'areasEnomes'            => $areasEnomes,
                                              // 'modalidadesIDeNome'     => $modalidadesIDeNome,
                                              // 'regrasubarq'            => $formtiposubmissao,
                                              // 'areasEspecificas'       => $areasEspecificas,
                                              // 'modalidadeEspecifica'   => $idModalidade,
                                              'formSubTraba'           => $formSubTraba,
                                              'ordemCampos'            => $ordemCampos,
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
      $modalidade = Modalidade::find($modalidadeId);
      
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
      // dd($request);
      
      if ($this->validarTipoDoArquivo($request, $modalidade)) {
        return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
        Verifique no formulário, quais os tipos permitidos.'])->withInput($validatedData);
      }
      
      $autor = Auth::user();
      $trabalhosDoAutor = Trabalho::where('eventoId', $request->eventoId)->where('autorId', Auth::user()->id)->count();
      // $areaModalidade = AreaModalidade::where('areaId', $request->araeaId)->where('modalidadeId', $request->modalidadeId)->first();
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
        'modalidadeId'  => $request->modalidadeId,
        'areaId'  => $request->areaId,
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
      // dd($trabalho->id);

      if($request->emailCoautor != null){
        foreach ($request->emailCoautor as $key) {
          $userCoautor = User::where('email', $key)->first();
          $coauntor = $userCoautor->coautor;
          if ($coauntor == null) {
            $coauntor = Coautor::create([
              'ordem' => '-',
              'autorId' => $userCoautor->id,
              // 'trabalhoId'  => $trabalho->id,
              'eventos_id' => $evento->id
            ]);
          }
          $coauntor->trabalhos()->attach($trabalho);
        }
      }

      if(isset($request->arquivo)){
        
        $file = $request->arquivo;
        $path = 'trabalhos/' . $request->eventoId . '/' . $trabalho->id .'/';
        $nome = $request->arquivo->getClientOriginalName();
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
    public function update(Request $request, $id)
    {
      dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trabalho  $trabalho
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $trabalho = Trabalho::find($id);
      $agora = Carbon::now();
      if (auth()->user()->id != $trabalho->autorId || $agora > $trabalho->modalidade->fimSubmissao) {
        return abort(403);
      }
      
      $coautores = $trabalho->coautors;
      foreach ($coautores as $coautor) {
        $coautor->trabalhos()->detach($trabalho->id);
        
        if (count($coautor->trabalhos) <= 0) {
          $coautor->delete();
        }
      }

      if ($trabalho->arquivo != null && Storage::disk()->exists($trabalho->arquivo->nome)) {
        Storage::delete($trabalho->arquivo->nome);
        $trabalho->arquivo->delete();
      }

      $trabalho->delete();
      
      return redirect()->back()->with(['mensagem' => 'Trabalho deletado com sucesso!']);
    }

    public function novaVersao(Request $request){
      $mytime = Carbon::now('America/Recife');
      $mytime = $mytime->toDateString();
      $trabalho = Trabalho::find($request->trabalhoId);
      $evento = $trabalho->evento;
      $modalidade = $trabalho->modalidade;

      $validatedData = $request->validate([
        'arquivo' => ['required', 'file', 'max:2000000'],
        'trabalhoId' => ['required', 'integer'],
      ]);

      // dd($validatedData);
      if($modalidade->inicioSubmissao > $mytime){
        if($mytime >= $modalidade->fimSubmissao){
          return redirect()->back()->withErrors(['error' => 'O periodo de submissão para esse trabalho se encerrou.']);
        }
      }
      
      if($this->validarTipoDoArquivo($request, $trabalho->modalidade)) {
        return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
          Verifique no formulário, quais os tipos permitidos.', 'trabalhoId' => $trabalho->id]);
      }

      dd($modalidade);

      if(Auth::user()->id != $trabalho->autorId){
        return abort(403);
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
      $revisor = Revisor::where([['evento_id', '=', $trabalho->eventoId], ['user_id', '=', auth()->user()->id]])->first();
      $user = User::find(auth()->user()->id);

      /*
        O usuário só tera permissão para baixar o arquivo se for revisor do trabalho
        ou se for coordenador do evento, coordenador da comissão, se pertencer a comissão
        do evento ou se for autor do trabalho.        
      */
      $arquivo = $trabalho->arquivo()->where('versaoFinal', true)->first();

      if ($trabalho->evento->coordenadorId == auth()->user()->id || $trabalho->evento->coordComissaoId == auth()->user()->id || $trabalho->autorId == auth()->user()->id) {
        // dd();
        if (Storage::disk()->exists($arquivo->nome)) {
          return Storage::download($arquivo->nome,  $trabalho->titulo . "." . explode(".", $arquivo->nome)[1]);
        }
        return abort(404);
      
      } else if ($revisor != null) {
        if ($revisor->trabalhosAtribuidos->contains($trabalho)) {
          if (Storage::disk()->exists($arquivo->nome)) {
            return Storage::download($arquivo->nome,  $trabalho->titulo . "." . explode(".", $arquivo->nome)[1]);
          }
          return abort(404);
        } 
      }

      return abort(403);
    }

    public function resultados($id) {
      $evento = Evento::find($id);
      $this->authorize('isCoordenadorOrComissao', $evento);

      $trabalhos = Trabalho::where('eventoId', $id)->orderBy('titulo')->get();
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

      $trabalhos = Trabalho::where([['areaId', $area_id], ['titulo', 'ilike', '%'. $texto .'%']])->orderBy('titulo')->get();

      $trabalhoJson = collect();

      foreach ($trabalhos as $trab) {
        $trabalho = [
          'id'          => $trab->id,
          'titulo'      => $trab->titulo,
          'nome'        => $trab->autor->name,
          'area'        => $trab->area->nome,
          'modalidade'  => $trab->modalidade->nome,
          'rota_download' => !(empty($trab->arquivo->nome)) ? route('downloadTrabalho', ['id' => $trab->id]) : '#',
        ];
        $trabalhoJson->push($trabalho);
      }

      return response()->json($trabalhoJson);
    }

    public function avaliarTrabalho(Request $request, $trabalho_id) {
      // dd($request);
      $exibirValidacao = $request->validate([
        'avaliar_trabalho_id' => 'required',
        'modalidade_id'       => 'required',
        'area_id'             => 'required',
        'evento_id'           => 'required',
      ]);
      
      $modalidade = Modalidade::find($request->modalidade_id);
      $revisor = Revisor::where([['user_id', auth()->user()->id], ['modalidadeId', $request->modalidade_id], ['areaId', $request->area_id], ['evento_id', $request->evento_id]])->first();
      $trabalho = Trabalho::find($trabalho_id);

      // dd($revisor);
      foreach ($modalidade->criterios as $criterio) {
        $validarCriterio = $request->validate([
          'criterio_'.$criterio->id => 'required',
        ]);
      }
      
      $validarParecer = $request->validate([
        'parecer_final' => 'required',
        'justificativa' => 'required',
      ]);

      foreach ($modalidade->criterios as $criterio) {
        $avaliacao = new Avaliacao();
        $avaliacao->revisor_id          = $revisor->id;
        $avaliacao->opcao_criterio_id   = $request->input("criterio_".$criterio->id);
        $avaliacao->trabalho_id         = $trabalho_id;
        $avaliacao->save();
      }

      // Atualizando tabelas
      $atribuicao = $trabalho->atribuicoes()->updateExistingPivot($revisor->id, ['confirmacao'=>true,'parecer'=>'dado']);  
      $trabalho->avaliado = "Avaliado";
      $trabalho->update();
      
      //Atualizando os status do revisor
      $revisor = $trabalho->atribuicoes()->where('revisor_id', $revisor->id)->first();
      $revisor->trabalhosCorrigidos++;
      $revisor->correcoesEmAndamento--;
      $revisor->update();

      // Salvando parecer final
      $parecer = new Parecer();
      $parecer->resultado     = $request->parecer_final;
      $parecer->justificativa = $request->justificativa;
      $parecer->revisorId     = $revisor->id;
      $parecer->trabalhoId    = $trabalho->id;
      $parecer->save();

      return redirect()->back()->with(['mensagem' => 'Avaliação salva']);
    }

    public function validarTipoDoArquivo(Request $request, $tiposExtensao) {
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
        if($tiposExtensao->zip == true) {
          array_push($tiposcadastrados, "zip");
        }
        if($tiposExtensao->svg == true) {
          array_push($tiposcadastrados, "svg");
        }

        $extensao = $request->arquivo->getClientOriginalExtension();
        if(!in_array($extensao, $tiposcadastrados)){
          return true;
        }
        return false;
      }
    }
}
