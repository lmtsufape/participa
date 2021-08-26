<?php

namespace App\Http\Controllers\Submissao;

use Auth;
use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Users\Coautor;
use App\Models\Users\Revisor;
use App\Models\Submissao\Area;
use App\Mail\SubmissaoTrabalho;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Arquivo;
use App\Models\Submissao\ArquivoCorrecao;
use App\Models\Submissao\Parecer;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Avaliacao;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Modalidade;
use Illuminate\Support\Facades\Mail;
use App\Models\Submissao\RegraSubmis;
use App\Models\Submissao\Arquivoextra;
use App\Models\Submissao\FormTipoSubm;
use App\Models\Submissao\FormSubmTraba;
use Illuminate\Support\Facades\Storage;
use App\Models\Submissao\AreaModalidade;
use App\Models\Submissao\ComissaoEvento;
use App\Models\Submissao\TemplateSubmis;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Mail\EmailParecerDisponivel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubmissaoTrabalhoNotification;

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
        $areas = $areas->sortBy('nome', SORT_NATURAL)->values()->all();
        $formSubTraba = FormSubmTraba::where('eventoId', $evento->id)->first();
        $regra = RegraSubmis::where('modalidadeId', $idModalidade)->first();
        $template = TemplateSubmis::where('modalidadeId', $idModalidade)->first();
        $ordemCampos = explode(",", $formSubTraba->ordemCampos);
        $modalidade = Modalidade::find($idModalidade);

        $mytime = Carbon::now('America/Recife');
        if($mytime > $modalidade->fimSubmissao){
            $this->authorize('isCoordenadorOrComissao', $evento);
        }
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

      try {
        $mytime = Carbon::now('America/Recife');
        //$mytime = $mytime->toDateString();
        $evento = Evento::find($request->eventoId);
        if($mytime > $modalidade->fimSubmissao){
            $this->authorize('isCoordenadorOrComissao', $evento);
        }
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
      //   dd($request->all());

        if ($this->validarTipoDoArquivo($request->arquivo, $modalidade)) {
          return redirect()->back()->withErrors(['tipoExtensao' => 'Extensão de arquivo enviado é diferente do permitido.
          Verifique no formulário, quais os tipos permitidos.'])->withInput($validatedData);
        }

      //   $autor = User::where('email', $request->emailCoautor[0])->first();
        $autor = Auth::user();

        $trabalhosDoAutor = Trabalho::where('eventoId', $request->eventoId)->where('autorId', Auth::user()->id)->where('status', '!=','arquivado' )->count();
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
          foreach ($request->emailCoautor as $key => $value) {
            if($value == $autor->email){

            }else{
                $userCoautor = User::where('email', $value)->first();
                if($userCoautor == null){
                  $passwordTemporario = Str::random(8);
                  $coord = User::find($evento->coordenadorId);
                  Mail::to($value)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Coautor', $evento->nome, $passwordTemporario, $value, $coord));
                  $usuario = User::create([
                    'email' => $value,
                    'password' => bcrypt($passwordTemporario),
                    'usuarioTemp' => true,
                    'name' => $request->nomeCoautor[$i],
                  ]);
                }
                $i++;
            }
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
          foreach ($request->emailCoautor as $key => $value) {
              if($value == $autor->email){

              }else{
                  $userCoautor = User::where('email', $value)->first();
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
        Notification::send($autor, new SubmissaoTrabalhoNotification($autor, $subject, $trabalho ));
        if($request->emailCoautor != null){
          foreach ($request->emailCoautor as $key => $value) {
              if($value == $autor->email){

              }else{
                  $userCoautor = User::where('email', $value)->first();
                  Mail::to($userCoautor->email)
                    ->send(new SubmissaoTrabalho($userCoautor, $subject));
              }
          }
        }

        return redirect()->route('evento.visualizar',['id'=>$request->eventoId])
                         ->with(['message' => 'Submissão concluída com sucesso!','class' => 'success']);
      } catch (\Throwable $th) {
          \Log::info("message".$th->getMessage());
        return redirect()->back()->with(['message' => "Submissão não foi concluída!",'class' => 'danger']);
      }


    }

    public function statusTrabalho($id, $status)
    {
        $trabalho = Trabalho::find($id);
        if($trabalho->status == 'avaliado' && $status == 'rascunho'){
            $trabalho->update(['status' => $status]);
            return redirect()->back()->with(['message' => "Encaminhamento desfeito com sucesso!",'class' => 'success']);
        }
        $trabalho->update(['status' => $status]);
        if($status == 'avaliado'){
            Mail::to($trabalho->autor->email)->send(new EmailParecerDisponivel($trabalho->evento, $trabalho));
            return redirect()->back()->with(['message' => "Trabalho encaminhado ao autor com sucesso!",'class' => 'success']);
        }
        return back();
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
    public function edit($id)
    {
        $trabalho = Trabalho::find($id);
        $modalidades = Modalidade::where('evento_id', $trabalho->eventoId)->get();
        $evento = Evento::find($trabalho->eventoId);
        return view('coordenador.trabalhos.trabalho_edit', compact('trabalho', 'modalidades', 'evento'));
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
      //dd($request)

      $validatedData = $request->validate([
        'trabalhoEditId'        => ['required'],
        'nomeTrabalho'.$id      => ['required', 'string',],
        'area'.$id              => ['required', 'integer'],
        'modalidade'.$id      => ['required', 'integer'],
        'resumo'.$id            => ['nullable','string'],
        'nomeCoautor_'.$id.'.*'  => ['string'],
        'emailCoautor_'.$id.'.*' => ['string'],
        'arquivo'.$id           => ['nullable', 'file', 'max:2000000'],
        'campoextra1arquivo'    => ['nullable', 'file', 'max:2000000'],
        'campoextra2arquivo'    => ['nullable', 'file', 'max:2000000'],
        'campoextra3arquivo'    => ['nullable', 'file', 'max:2000000'],
        'campoextra4arquivo'    => ['nullable', 'file', 'max:2000000'],
        'campoextra5arquivo'    => ['nullable', 'file', 'max:2000000'],
        'campoextra1simples'    => ['nullable', 'string'],
        'campoextra2simples'    => ['nullable', 'string'],
        'campoextra3simples'    => ['nullable', 'string'],
        'campoextra4simples'    => ['nullable', 'string'],
        'campoextra5simples'    => ['nullable', 'string'],
        'campoextra1grande'     => ['nullable', 'string'],
        'campoextra2grande'     => ['nullable', 'string'],
        'campoextra3grande'     => ['nullable', 'string'],
        'campoextra4grande'     => ['nullable', 'string'],
        'campoextra5grande'     => ['nullable', 'string'],
      ]);

      $trabalho = Trabalho::find($id);
      $evento = $trabalho->evento;
      $arquivo = $request->file('arquivo'.$id);
      if ($arquivo != null && $this->validarTipoDoArquivo($arquivo, $trabalho->modalidade)) {
        return redirect()->back()->withErrors(['arquivo'.$id => 'Extensão de arquivo enviado é diferente do permitido.
                                                                  Verifique no formulário, quais os tipos permitidos.'])->withInput($validatedData);
      }

      if($request->input('emailCoautor_'.$id) != null){
        $i = 0;
        foreach ($request->input('emailCoautor_'.$id) as $key) {
          $i++;
        }
        if($i > $evento->numMaxCoautores){
          return redirect()->back()->withErrors(['numeroMax'.$id => 'Número de coautores deve ser menor igual a '.$evento->numMaxCoautores])->withInput($validatedData);
        }
      } else {
        return redirect()->back()->withErrors(['numeroMax'.$id => 'O trabalho deve conter pelo menos o seu autor'])->withInput($validatedData);
      }

      $trabalho->titulo = $request->input('nomeTrabalho'.$id);
      $trabalho->resumo = $request->input('resumo'.$id);
      $trabalho->areaId = $request->input('area'.$id);
      if($request->input('modalidade'.$id) != $trabalho->modalidadeId && $trabalho->avaliado == 'Avaliado'){
        return redirect()->back()->withErrors(['modalidadeError'.$id => 'Não é possível alterar a modalidade de um trabalho avaliado.'])->withInput($validatedData);
      }else if ($request->input('modalidade'.$id) != $trabalho->modalidadeId && $trabalho->atribuicoes->count() > 0) {
        return redirect()->back()->withErrors(['modalidadeError'.$id => 'Não é possível alterar a modalidade de um trabalho com revisores atribuídos.'])->withInput($validatedData);
      }else{
        $trabalho->modalidadeId = $request->input('modalidade'.$id);
      }


      $coautores = collect();
      foreach ($trabalho->coautors as $coautor_id) {
        $coautores->push(User::find($coautor_id->autorId));
      }

      $coautoresExcluidos = collect();
      // TODO Checando a mudança do autor do trabalho, a inclusão e exclusão de coautores
      foreach ($request->input('emailCoautor_'.$id) as $i => $email) {
        $coautor = User::where('email', $email)->first();
        // Chegando se existe do usuário cadastrado no sistema
        // Se existir é checado se o usario já é coautor do trabalho e adicionado nos coautores
        // excluidos para diff futuro. Se o usuário é existente no sistema mas não é coautor do trabalho
        // é checado se ele é coautor de algum trabalho, se for coautor o trabalho é adicionado no relacionamento,
        // se não é criado um coautor com o id do user e o trabalho é adicionado


        if ($coautor != null) {
          if ($coautores->contains($coautor)) {
            $coautoresExcluidos->contains($coautor);
          } else {
            $coautorExistente = $coautor->coautor;
            if ($coautorExistente != null && $i != 0) {
              $coautorExistente->trabalhos()->attach($trabalho);
            } else if ($i != 0) {
              $coauntorAdicional = Coautor::create([
                'ordem' => '-',
                'autorId' => $coautor->id,
                'eventos_id' => $evento->id
              ]);
              $coauntorAdicional->trabalhos()->attach($trabalho);
            }
          }
        } else if ($coautor == null) {
          // Se o usuário não existir eu crio um usuário temporário um coautor para o usuário criado
          // e relaciono o trabalho a ele

          $passwordTemporario = Str::random(8);
          $coord = User::find($evento->coordenadorId);
          Mail::to($email)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Coautor', $evento->nome, $passwordTemporario, $email, $coord));
          $usuario = User::create([
            'email' => $email,
            'password' => bcrypt($passwordTemporario),
            'usuarioTemp' => true,
            'name' => $request->input('nomeCoautor_'.$id)[$i],
          ]);

          if ($i != 0) {
            $coauntorAdicional = $usuario->coautor;
            if ($coauntorAdicional == null) {
              $coauntorAdicional = Coautor::create([
                'ordem' => '-',
                'autorId' => $usuario->id,
                'eventos_id' => $evento->id
              ]);
            }
            $coauntorAdicional->trabalhos()->attach($trabalho);
          }
        }

        if ($i == 0) {
          // checando se o autor foi alterado
          if ($trabalho->autor->email != $email) {
            $autor = User::where('email', $email)->first();
            $trabalho->autorId = $autor->id;
          }
        }
      }

      // TODO comparando os autores existentes com os excluidos
      // os que restarem são os excluidos do trabalho

      $coautoresExcluidos = $coautores->diff($coautoresExcluidos);
      foreach ($coautoresExcluidos as $coautor) {
        if (!(in_array($coautor->email, $request->input('emailCoautor_'.$id)))) {
          $coautor->coautor->trabalhos()->detach($id);
        }
      }

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


      if($request->file('arquivo'.$id) != null){

        $file = $request->file('arquivo'.$id);
        $path = 'trabalhos/' . $evento->id . '/' . $trabalho->id .'/';
        $nome = $request->file('arquivo'.$id)->getClientOriginalName();
        Storage::putFileAs($path, $file, $nome);

        //É necessário excluir o arquivo da tabela de arquivo também ao editar um trabalho
        //Não só fazer o Storage::delete() do arquivo
        $arquivosTrabalho = $trabalho->arquivo()->where('versaoFinal', true)->get();
        foreach ($arquivosTrabalho as $arquivoTrabalho) {
            if (Storage::disk()->exists($arquivoTrabalho->nome)) {
                Storage::delete($arquivoTrabalho->nome);
            }
            $arquivoTrabalho->delete();
        }

        $arquivo = Arquivo::create([
          'nome'  => $path . $nome,
          'trabalhoId'  => $trabalho->id,
          'versaoFinal' => true,
        ]);

        /*$arquivoAtual = $trabalho->arquivo()->where('versaoFinal', true)->first();
        if (Storage::disk()->exists($arquivoAtual->nome)) {
          Storage::delete($arquivoAtual->nome);
          $arquivoAtual->delete();
        }*/
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

      $trabalho->update();

      return redirect()->back()->with(['mensagem' => $trabalho->titulo . ' editado com sucesso!']);
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

      if ($trabalho->atribuicoes != null && $trabalho->atribuicoes->count() > 0) {
        foreach ($trabalho->atribuicoes as $atrib) {
          $trabalho->atribuicoes()->detach($atrib->revisor_id);
        }
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
        // dd($arquivo);
        if ($arquivo != null && Storage::disk()->exists($arquivo->nome)) {
          return Storage::download($arquivo->nome);
        }
        return abort(404);

      } else if ($revisor != null) {
        if ($revisor->trabalhosAtribuidos->contains($trabalho)) {
          if (Storage::disk()->exists($arquivo->nome)) {
            return Storage::download($arquivo->nome);
          }
          return abort(404);
        }else{
            if (Storage::disk()->exists($arquivo->nome)) {
                return Storage::download($arquivo->nome);
              }
            return abort(404);
        }
      }

      return abort(403);
    }

    public function downloadArquivoAvaliacao(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalhoId);
        $modalidadesRevisor = Revisor::where([['evento_id', '=', $trabalho->eventoId], ['user_id', '=', $request->revisorUserId]])->get();
        if($modalidadesRevisor->count() > 0){
            $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $modalidadesRevisor->first()->id]])->first();
            foreach($modalidadesRevisor as $revisor){
                if($trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first() != null){
                    $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first();
                    break;
                }
            }
            $arquivo = $trabalho->arquivoAvaliacao()->where([['versaoFinal', true], ['revisorId', $revisor->id]])->first();

            if ($trabalho->evento->coordenadorId == auth()->user()->id || $trabalho->evento->coordComissaoId == auth()->user()->id) {
                if ($arquivo != null && Storage::disk()->exists($arquivo->nome)) {
                    return Storage::download($arquivo->nome);
                }
                return abort(404);

            }else if(($revisor != null && $revisor->id == auth()->user()->id) || ($trabalho->status == 'avaliado' && $trabalho->autorId  == auth()->user()->id)) {
                if ($revisor->trabalhosAtribuidos->contains($trabalho) || ($trabalho->autorId  == auth()->user()->id)) {
                    if (Storage::disk()->exists($arquivo->nome)) {
                        return Storage::download($arquivo->nome);
                    }
                return abort(404);
                }
            }
            return abort(403);
        }
        return abort(403);
    }

    public function aprovacaoTrabalho(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalho_id);
        $mensagem = "";

        if ($request->aprovacao == "true") {
            $trabalho->aprovado = true;
            $mensagem = "Trabalho aprovado com sucesso!";
        } else if ($request->aprovacao == "false") {
            $trabalho->aprovado = false;
            $mensagem = "Trabalho reprovado com sucesso!";
        }

        $trabalho->update();

        return redirect()->back()->with(['message' => $mensagem,'class' => 'success']);

    }

    public function correcaoTrabalho(Request $request)
    {
        $trabalho = Trabalho::find($request->trabalhoCorrecaoId);
        $this->authorize('permissaoCorrecao', $trabalho);
        if($request->arquivoCorrecao != null){

            if($this->validarTipoDoArquivo($request->arquivoCorrecao, $trabalho->modalidade)) {
                return redirect()->back()->withErrors(['mensagem' => 'Extensão de arquivo enviado é diferente do permitido.']);
            }

            $validatedData = $request->validate([
                'arquivoCorrecao' => ['required', 'file', 'max:2048'],
            ]);

            $arquivoCorrecao = $trabalho->arquivoCorrecao()->first();
            if($arquivoCorrecao != null){
                if (Storage::disk()->exists($arquivoCorrecao->caminho)) {
                    Storage::delete($arquivoCorrecao->caminho);
                }
                $arquivoCorrecao->delete();
            }

            $file = $request->arquivoCorrecao;
            $path = 'correcoes/' . $trabalho->evento->id . '/' . $trabalho->id .'/';
            $nome = $request->arquivoCorrecao->getClientOriginalName();
            Storage::putFileAs($path, $file, $nome);

            $arquivo = ArquivoCorrecao::create([
            'caminho'  => $path . $nome,
            'trabalhoId'  => $trabalho->id,
            ]);
        }
        return redirect()->back()->with(['mensagem' => 'Correção de '. $trabalho->titulo . ' enviada com sucesso!']);

    }

    public function downloadArquivoCorrecao(Request $request)
    {
        $trabalho = Trabalho::find($request->id);
        $this->authorize('permissaoCorrecao', $trabalho);
        $arquivo = $trabalho->arquivoCorrecao()->first();
        if ($arquivo != null && Storage::disk()->exists($arquivo->caminho)) {
            return Storage::download($arquivo->caminho);
        }else{
            return abort(404);
        }
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

    public function validarTipoDoArquivo($arquivo, $tiposExtensao) {
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

        $extensao = $arquivo->getClientOriginalExtension();
        if(!in_array($extensao, $tiposcadastrados)){
          return true;
        }
        return false;
      }
    }
}
