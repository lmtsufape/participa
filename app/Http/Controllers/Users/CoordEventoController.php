<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CoordEventoController extends Controller
{
    public function index()
    {
        $eventos = QueryBuilder::for(Evento::class)
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
        ->latest()
        ->paginate(request('per_page', 15))
        ->withQueryString();

        return view('coordenador.index', ['eventos' => $eventos]);
    }

    public function listaEventos()
    {
        $eventos = Evento::all();

        return view('coordenador.lista_eventos', ['eventos' => $eventos]);
    }
}
