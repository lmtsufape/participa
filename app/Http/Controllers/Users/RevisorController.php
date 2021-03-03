<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\Revisor;
use App\Models\Users\User;
use App\Models\Submissao\Area;
use App\Models\Submissao\Arquivo;
use App\Models\Submissao\Atribuicao;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use Illuminate\Http\Request;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use App\Mail\EmailLembrete;
use App\Mail\EmailConviteRevisor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

 
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
        // dd($request);
        $validatedData = $request->validate([
          'emailRevisor' => ['required', 'string', 'email', 'max:255'],
          'areas'        => ['required'],
          'modalidades'  => ['required'],
        ]);

        $usuario = User::where('email', $request->emailRevisor)->first();
        $evento = Evento::find($request->eventoId);
        // dd(count($usuario->revisor()->where('evento_id', $evento->id)->get()));
        if($usuario == null){
          $passwordTemporario = Str::random(8);
          Mail::to($request->emailRevisor)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', 'Revisor', $evento->nome, $passwordTemporario));

          $usuario = new User();
          $usuario->email       = $request->emailRevisor;
          $usuario->password    = bcrypt($passwordTemporario);
          $usuario->usuarioTemp = true;
          $usuario->save();

          foreach ($request->areas as $area) {
            foreach ($request->modalidades as $modalidade) {
              $revisor = new Revisor();
              $revisor->trabalhosCorrigidos   = 0;
              $revisor->correcoesEmAndamento  = 0;
              $revisor->user_id               = $usuario->id;
              $revisor->areaId                = $area;
              $revisor->modalidadeId          = $modalidade;
              $revisor->evento_id             = $evento->id;
              $revisor->save();
            }
          }

        } else if (count($usuario->revisor()->where('evento_id', $evento->id)->get()) <= 0) {
          foreach ($request->areas as $area) {
            foreach ($request->modalidades as $modalidade) {
              $revisor = new Revisor();
              $revisor->trabalhosCorrigidos   = 0;
              $revisor->correcoesEmAndamento  = 0;
              $revisor->user_id               = $usuario->id;
              $revisor->areaId                = $area;
              $revisor->modalidadeId          = $modalidade;
              $revisor->evento_id             = $evento->id;
              $revisor->save();
            }
          }
        } else {
          return redirect()->back()->withErrors(['errorRevisor' => 'Esse revisor já está cadastrado para o evento.'])->withInput($validatedData);
        }
      
        return redirect()->back()->with(['mensagem' => 'Revisor cadastrado com sucesso!']);
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
    public function update(Request $request)
    {
      $user = User::find($request->editarRevisor);
      $evento = Evento::find($request->eventoId);
      $validatedData = $request->validate([
        'editarRevisor'   => 'required',
        'areasEditadas_'.$user->id => 'required',
        'modalidadesEditadas_'.$user->id => 'required',
      ]);

      $revisores = $user->revisor()->where('evento_id', '=', $evento->id)->get();
      $revisoresRetirados = collect();

      // Checando se o alguma área e modalidade foiram retiradas
      foreach ($revisores as $revisor) {
        foreach ($request->input('areasEditadas_'.$user->id) as $area) {
          foreach ($request->input('modalidadesEditadas_'.$user->id) as $modalidade) {
            if ($revisor->areaId == $area && $revisor->modalidadeId == $modalidade) {
              $revisoresRetirados->push($revisor);
            }
          }
        }
      }

      $revisoresRetirados = $revisores->diff($revisoresRetirados);
      if (count($revisoresRetirados) > 0) {
        foreach ($revisoresRetirados as $revisor) {
          if (count($revisor->trabalhosAtribuidos) > 0) {
            return redirect()->back()->withErrors(['errorRevisor' => 'Existem trabalhos atribuidos para esse revisor na área de ' . $revisor->area->nome . ' na modalidade de ' . $revisor->modalidade->nome . '.']);
          }
        }
      }

      // Deletando os revisores que foram retirados
      if (count($revisoresRetirados) > 0) {
        foreach ($revisoresRetirados as $revisor) {
          $revisor->delete();
        }
      }

      // Adicionando os novos revisores
      foreach ($request->input('areasEditadas_'.$user->id) as $area) {
        $encontrado = false;
        foreach ($request->input('modalidadesEditadas_'.$user->id) as $modalidade) {
          foreach ($revisores as $revisor) {
            if ($revisor->areaId == $area && $revisor->modalidadeId == $modalidade) {
              $encontrado = true;
            }
          }
          if ($encontrado == false) {
            $revisor = new Revisor();
            $revisor->trabalhosCorrigidos   = 0;
            $revisor->correcoesEmAndamento  = 0;
            $revisor->user_id               = $user->id;
            $revisor->areaId                = $area;
            $revisor->modalidadeId          = $modalidade;
            $revisor->evento_id             = $evento->id;
            $revisor->save();
          }
        }
      }
      
      return redirect()->back()->with(['mensagem' => 'Revisor salvo com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revisor  $revisor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $evento_id)
    {
      $user = User::find($id);
      $evento = Evento::find($evento_id);

      foreach ($user->revisor()->where('evento_id', '=', $evento->id)->get() as $revisor) {
        if (count($revisor->trabalhosAtribuidos) > 0) {
          return redirect()->back()->withErrors(['errorRevisor' => 'Não é possível remover o revisor, pois há trabalhos atribuídos para o mesmo.']);
        }
        if (count($revisor->avaliacoes) > 0) {
          return redirect()->back()->withErrors(['errorRevisor' => 'Não é possível remover o revisor, pois há avaliações do mesmo.']);
        }
      }

      foreach ($user->revisor()->where('evento_id', '=', $evento->id)->get() as $revisor) {
        $revisor->delete();
      }
      
      return redirect()->back()->with(['mensagem' => 'Revisor removido com sucesso!']);
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

        return redirect()->back()->with(['mensagem' => 'E-mail de lembrete de revisão enviado para ' . $user->email . '.']);
    }
    public function enviarEmailTodosRevisores(Request $request){
        $subject = "Lembrete ";
        
        $revisores = json_decode($request->input('revisores'));
        foreach ($revisores as $revisor) {
            $user = User::find($revisor->id);
            Mail::to($revisor->email)
            ->send(new EmailLembrete($user, $subject));                
        }

        return redirect()->back()->with(['mensagem' => 'E-mails de lembrete enviados!']);
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
      $this->authorize('isRevisor', $evento);
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
