<?php

namespace App\Http\Controllers;

use App\Revisor;
use App\User;
use App\Area;
use App\Arquivo;
use App\Atribuicao;
use App\Trabalho;
use App\Evento;
use App\Modalidade;
use Illuminate\Http\Request;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Mail\EmailLembrete;
use App\Mail\EmailConviteRevisor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

 
class RevisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $idsEventos = Revisor::where('user_id', auth()->user()->id)->groupBy('evento_id')->select('evento_id')->get();
      $eventosComoRevisor = Evento::whereIn('id', $idsEventos)->get();
      // dd($eventosComoRevisor);
      return view('revisor.index')->with(['eventos' => $eventosComoRevisor]);
    }


    public function indexListarTrabalhos()
    { 
        $revisor = Revisor::where("user_id", Auth::user()->id)->first();
        $trabalhos = $revisor->trabalhosAtribuidos;

        return view('revisor.listarTrabalhos', [
          "trabalhos" => $trabalhos,]);
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
        $validatedData = $request->validate([
          'emailRevisor'       => ['required', 'string', 'email', 'max:255'],
          // 'nomeRevisor'        => 'required|string|max:255',
          'areaRevisor'        => ['required', 'integer'],
          'modalidadeRevisor'  => ['required', 'integer'],
        ]);

        $usuario = User::where('email', $request->emailRevisor)->first();
        $evento = Evento::find($request->eventoId);
        $revisor;
        if($usuario == null){
          $passwordTemporario = Str::random(8);
          Mail::to($request->emailRevisor)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Revisor', $evento->nome, $passwordTemporario));
          
          $usuario = new User();
          $usuario->email       = $request->emailRevisor;
          // $usuario->name        = $request->nomeRevisor;
          $usuario->password    = bcrypt($passwordTemporario);
          $usuario->usuarioTemp = true;
          $usuario->save();
          

          $revisor = new Revisor();
          $revisor->trabalhosCorrigidos   = 0;
          $revisor->correcoesEmAndamento  = 0;
          $revisor->user_id               = $usuario->id;
          $revisor->areaId                = $request->areaRevisor;
          $revisor->modalidadeId          = $request->modalidadeRevisor;
          $revisor->evento_id             = $evento->id;
          $revisor->save();

        } else {
          $revisor = new Revisor();
          $revisor->trabalhosCorrigidos   = 0;
          $revisor->correcoesEmAndamento  = 0;
          $revisor->user_id               = $usuario->id;
          $revisor->areaId                = $request->areaRevisor;
          $revisor->modalidadeId          = $request->modalidadeRevisor;
          $revisor->evento_id             = $evento->id;
          $revisor->save();
        }        
        

        return redirect()->route('coord.detalhesEvento', ['eventoId' => $request->eventoId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function show(Revisor $revisor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function edit(Revisor $revisor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revisor $revisor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revisor $revisor)
    {
        //
    }

    public function numeroDeRevisoresAjax(Request $request){
      $validatedData = $request->validate([
        'areaId' => ['required', 'string'],
      ]);

      $numeroRevisores = Revisor::where('areaId', $request->areaId)->count();

      return response()->json($numeroRevisores, 200);
    }

    public function enviarEmailRevisor(Request $request){
        $subject = "Lembrete ";

        $user = json_decode($request->input('user'));
        //Log::debug('Revisores ' . gettype($user));
        //Log::debug('Revisores ' . $request->input('user'));

        Mail::to($user->email)
            ->send(new EmailLembrete($user, $subject));

        return redirect()->back();
    }
    public function enviarEmailTodosRevisores(Request $request){
        $subject = "Lembrete ";
        
        $revisores = json_decode($request->input('revisores')) ;

        foreach ($revisores as $revisor) {
            $user = $revisor->user;
            Mail::to($user->email)
            ->send(new EmailLembrete($user, $subject));                
        }

        return redirect()->back();
    }

    public function listarRevisores($id) {
      $evento = Evento::find($id);
      $areas = Area::orderBy('nome')->get();
      $revisores = Revisor::all();

      // dd($revisores[0]);
      
      return view('coordenador.revisores.revisoresCadastrados')->with(['evento'    => $evento,
                                                                       'revisores' => $revisores,
                                                                       'areas'     => $areas]);
    }

    public function conviteParaEvento(Request $request, $id) {
      $subject = "Evento - Convinte para revisor";
      $evento = Evento::find($id);
      
      $user = User::find($request->id);

      if ($user->revisor->eventosComoRevisor()->where([['evento_id', $id], ['convite_aceito', null]])->first() != null) {
        return redirect()->back()->with(['error' => 'Há um convite pendente para esse usuário']);
      }

      if ($user->revisor->eventosComoRevisor()->where([['evento_id', $id], ['convite_aceito', true]])->first() != null) {
        return redirect()->back()->with(['error' => 'Esse usuário já aceitou o convite!']);
      }

      $evento->revisores()->attach($user->revisor->id, ['convite_aceito'=> null]);

      //Log::debug('Revisores ' . gettype($user));
      //Log::debug('Revisores ' . $request->input('user'));

      Mail::to($user->email)
          ->send(new EmailConviteRevisor($user, $evento, $subject));

      return redirect()->back()->with(['mensagem' => 'Convite enviado']);
    }

    public function revisoresPorAreaAjax($id) {
      $revisores = Revisor::where('areaId', $id)->get();

      $revsPorArea = collect();

      foreach ($revisores as $revisor) {
        $revisor = [
          'id'    => $revisor->user->id,
          'email' => $revisor->user->email,
          'area' => $revisor->area->nome,
          'emAndamento' => $revisor->correcoesEmAndamento,
          'concluido'   => $revisor->trabalhosCorrigidos
        ];

        $revsPorArea->push($revisor);
      }

      return response()->json($revsPorArea);
    }

    public function trabalhosDoEvento($id) {
      $evento = Evento::find($id);
      $revisores = Revisor::where([['user_id', auth()->user()->id],['evento_id', $id]])->get();
      $trabalhos = collect();
      foreach ($revisores as $revisor) {
        $trabalhos->push($revisor->trabalhosAtribuidos()->orderBy('titulo')->get());
      }
      // dd($trabalhos);
      return view('revisor.listarTrabalhos')->with(['evento' => $evento,'trabalhosPorRevisor' => $trabalhos]);
      // $trabalhos = Atribuicao::where('eventoId', $id);
    }
}
