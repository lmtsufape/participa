<?php

namespace App\Http\Controllers;

use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Evento;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
    public function home(Request $request)
    {
        $user = Auth::user();
        $eventos = collect();
        if ($user->administrador()->exists()) {
            $eventos = $eventos->concat(Evento::all());

            return view('administrador.index', ['eventos' => $eventos]);
        }
        else if ($user->coordComissaoCientifica()->exists() || $user->coordComissaoOrganizadora()->exists()) {
            $eventos = QueryBuilder::for(Evento::class)
            ->where(function($q) use ($user) {
                $q->whereHas('coordComissaoCientifica', fn($r) => $r->where('user_id', $user->id))
                  ->orWhereHas('coordComissaoOrganizadora', fn($r) => $r->where('user_id', $user->id))
                  ->orWhere('coordenadorId', $user->id);
            })
            ->allowedFilters([
                AllowedFilter::callback('q', function ($query, $value) {
                    $term = trim((string) $value);
                    if ($term === '') return;
                    $query->where(function ($w) use ($term) {
                        if (ctype_digit($term)) {
                            $w->orWhere('id', (int) $term);
                        }
                        $w->orWhere('nome', 'ILIKE', "%{$term}%")
                        ->orWhere('descricao', 'ILIKE', "%{$term}%");
                    });
                }),
            ])
            ->distinct()
            ->paginate(request('per_page', 15))
            ->withQueryString();
        }else{
            $eventos = Evento::whereHas('inscricaos', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });

            if ($request->filled('busca')) {
                $eventos->where('nome', 'ilike', '%' . $request->busca . '%');
            }

            if ($request->filled('ordenar')) {
                switch ($request->ordenar) {
                    case 'nome':
                        $eventos->orderBy('nome');
                        break;
                    case 'data':
                    default:
                        $eventos->orderBy('dataFim', 'desc');
                        break;
                }
            } else {
                $eventos->orderBy('dataFim', 'desc');
            }

            $eventos = $eventos->paginate(9);

            return view('user.areaParticipante', ['eventos' => $eventos]);
        }

        return view('coordenador.index', ['eventos' => $eventos]);
    }

    public function index()
    {
        $eventos_destaques = Evento::where([ ['publicado', '=', 'true'], ['dataFim', '>=', 'today()']])->get();

        $proximosEventos = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '>=', today()]])->whereNull('evento_pai_id')->get();

        $eventos_passados = Evento::where([['publicado', '=', true], ['deletado', '=', false], ['dataFim', '<', today()]])->whereNull('evento_pai_id')->take(6)->get()->sortDesc();

        $tiposEvento = Evento::where([['publicado', '=', true], ['deletado', '=', false]])->where([['dataInicio', '<=', today()], ['dataFim', '>=', today()]])->selectRaw('DISTINCT tipo')->get();

        return view('index',compact('eventos_destaques','tiposEvento','proximosEventos','eventos_passados'));
    }
}
