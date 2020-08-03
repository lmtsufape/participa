<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Criterio;

class CriteriosController extends Controller
{
    public function store(Request $request)
    {   

        $validatedData = $request->validate([
            'nomeCriterio.*'       => ['string'],
            'pesoCriterio.*'       => ['integer'],
            'modalidade'           => ['integer'],
        ]);

        for ($i=0; $i < count($request->nomeCriterio); $i++) { 
            $criterio = Criterio::create([
                'nome' => $request->nomeCriterio[$i],
                'peso' => $request->pesoCriterio[$i],
                'modalidadeId' => $request->modalidade, 
            ]);
        }

        return redirect()->route('coord.detalhesEvento', ["eventoId" => $request->eventoId]);
    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'nomeCriterioUpdate' => ['string'],
            'pesoCriterioUpdate' => ['integer'],
        ]);

        $criterio = Criterio::find($request->modalidadeId);
        
        $criterio->nome = $request->nomeCriterioUpdate;
        $criterio->peso = $request->pesoCriterioUpdate;

        $criterio->save();

        return redirect()->route('coord.detalhesEvento', ["eventoId" => $request->eventoId]);
    }
    public function findCriterio(Request $request)
    {
        $criterio = Criterio::find($request->criterioId);
        return $criterio;
    }
}
