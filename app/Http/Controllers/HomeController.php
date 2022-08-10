<?php

namespace App\Http\Controllers;

use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Evento;
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $user = Auth::user();
        if ($user->administradors != null) {
            $eventos = Evento::all();

            return view('administrador.index', ['eventos' => $eventos]);
        } elseif (($eventos = $user->coordComissaoCientifica) && $eventos->isNotEmpty()) {
            return view('coordenador.index', ['eventos' => $eventos]);
        } elseif (($eventos = $user->coordComissaoOrganizadora) && $eventos->isNotEmpty()) {
            return view('coordenador.index', ['eventos' => $eventos]);
        } elseif ($user->membroComissao != null) {
            $eventos = $user->membroComissao->eventos;

            return view('coordenador.index', ['eventos' => $eventos]);
        } elseif ($user->revisor != null) {
            $eventos = Evento::all();

            return view('coordenador.index', ['eventos' => $eventos]);
        } elseif ($user->coautor != null) {
            $eventos = $user->coautor->eventos;

            return view('coordenador.index', compact('eventos'));
        } elseif ($user->coordEvento != null) {
            $eventos = Evento::all();

            return view('coordenador.index', compact('eventos'));
        } elseif ($user->participante != null) {
            $eventos = Evento::all();

            return view('coordenador.index', compact('eventos'));
        } else {
            return view('home');
        }
    }

    public function index()
    {
        $eventosDestaque = Inscricao::join('eventos', 'inscricaos.evento_id', '=', 'eventos.id')->select('eventos.id', DB::raw('count(inscricaos.evento_id) as total'))->groupBy('eventos.id')->orderBy('total', 'desc')->where([['dataInicio', '<=', today()], ['dataFim', '>=', today()]])->limit(6)->get();

        $eventos = collect();
        if (count($eventosDestaque) > 0) {
            foreach ($eventosDestaque as $ev) {
                $eventos->push(Evento::find($ev->id));
            }
        } else {
            $eventos = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataInicio', '<=', today()], ['dataFim', '>=', today()]])->get();
        }

        $proximosEventos = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '>=', today()]])->whereNull('evento_pai_id')->get();

        $eventosPassados = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '<', today()]])->whereNull('evento_pai_id')->get();

        $tiposEvento = Evento::where([['publicado', '=', true], ['deletado', '=', false]])->where([['dataInicio', '<=', today()], ['dataFim', '>=', today()]])->selectRaw('DISTINCT tipo')->get();

        return view('index', ['eventos'=>$eventos, 'tipos' => $tiposEvento, 'proximosEventos' => $proximosEventos, 'eventosPassados' => $eventosPassados]);
    }
}
