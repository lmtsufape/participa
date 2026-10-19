<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CoordEventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::where('coordenadorId', Auth::user()->id)->latest()->withCount([
                'inscricaos as inscricoes_finalizadas_count' => function ($query) {
                        $query->where('finalizada', true);
                    },
                'modalidades'
            ])->paginate(15);

        return view('coordenador.index', ['eventos' => $eventos]);
    }

    public function listaEventos()
    {
        $eventos = Evento::all();

        return view('coordenador.lista_eventos', ['eventos' => $eventos]);
    }
}
