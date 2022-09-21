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
        $eventos = collect();
        if ($user->administradors()->exists()) {
            $eventos = $eventos->concat(Evento::all());

            return view('administrador.index', ['eventos' => $eventos]);
        }
        if ($user->coordComissaoCientifica()->exists()) {
            $eventos = $eventos->concat($user->coordComissaoCientifica);
        }
        if ($user->coordComissaoOrganizadora()->exists()) {
            $eventos = $eventos->concat($user->coordComissaoOrganizadora);
            $subeventos = Evento::whereIn('evento_pai_id', ($user->coordComissaoOrganizadora()->pluck('eventos_id')))->get();
            $eventos = $eventos->concat($subeventos);
        }
        if ($user->membroComissaoEvento()->exists()) {
            $eventos = $eventos->concat($user->membroComissaoEvento);
        }
        if ($user->revisor()->exists()) {
            $eventos = Evento::all();
        }
        if ($user->coautor()->exists()) {
            $eventos = $eventos->concat([$user->coautor->eventos]);
        }
        if ($user->coordEvento()->exists()) {
            $eventos = Evento::all();
        }
        if ($user->participante()->exists()) {
            $eventos = Evento::all();
        }

        return view('coordenador.index', ['eventos' => $eventos]);
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
