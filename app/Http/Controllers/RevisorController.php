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
    public function indexListarTrabalhos()
    { 
        $revisores = Revisor::where("user_id", Auth::user()->id)->get();
        $atribuicoes = [];
        foreach ($revisores as $revisor) {
          $temp = Atribuicao::where("revisorId", $revisor->id)->get();
          for ($i=0; $i < count($temp); $i++) { 
            array_push($atribuicoes, $temp[$i]);  
          }
        }
        $trabalhos = [];
        foreach ($atribuicoes as $atribuicao) {
          array_push($trabalhos, Trabalho::where("id", $atribuicao->trabalhoId)->first());  
        }


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
          'nomeRevisor'        => 'required|string|max:255',
          'areaRevisor'        => ['required', 'integer'],
          'modalidadeRevisor'  => ['required', 'integer'],
        ]);

        $usuario = User::where('email', $request->emailRevisor)->first();
        $evento = Evento::find($request->eventoId);

        if($usuario == null){
          $passwordTemporario = Str::random(8);
          Mail::to($request->emailRevisor)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Revisor', $evento->nome, $passwordTemporario));
          $usuario = User::create([
            'email' => $request->emailRevisor,
            'name' => $request->nomeRevisor,
            'password' => bcrypt($passwordTemporario),
            'usuarioTemp' => true,
          ]);

          
        }

        $revisor = Revisor::create([
          'trabalhosCorrigidos'   => 0,
          'correcoesEmAndamento'  => 0,
          'eventoId'              => $request->eventoId,
          'user_id'               => $usuario->id,
          'areaId'                => $request->areaRevisor,
          'modalidadeId'          => $request->modalidadeRevisor,
        ]);

        $usuario->revisor()->save($revisor);
        $usuario->revisor->eventos()->attach($evento);



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
}
