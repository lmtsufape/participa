<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Criterio;
use App\OpcoesCriterio;

class CriteriosController extends Controller
{
    public function store(Request $request)
    {   
        $quantDeCriterios = (count($request->all())- 3) / 3;
        $c = 0;
        while ($quantDeCriterios != 0) {
            $criterioCadastrado         = false;
            $opcoesCriterioCadastrado   = false;

            if ($request->input('nomeCriterio'.$c) != null && $request->input('pesoCriterio'.$c) != null) {
                $validatedData = $request->validate([
                    'nomeCriterio'.$c       => ['string'],
                    'pesoCriterio'.$c       => ['integer'],
                    'modalidade'           => ['integer'],
                ]);
                
                $criterio = Criterio::create([
                    'nome' => $request->input('nomeCriterio'.$c),
                    'peso' => $request->input('pesoCriterio'.$c),
                    'modalidadeId' => $request->modalidade,
                ]);
                $criterioCadastrado = true;
            }

            if ($request->input('opcaoCriterio_'.$c) != null) {
                foreach ($request->input('opcaoCriterio_'.$c) as $opcao) {
                    $opCriterio = new OpcoesCriterio();
                    $opCriterio->nome_opcao     = $opcao;
                    $opCriterio->criterio_id    = $criterio->id;
                    $opCriterio->save();
                }
                $opcoesCriterioCadastrado = true;
            }

            if ($criterioCadastrado && $opcoesCriterioCadastrado) {
                $quantDeCriterios--;
                $c++;
            } else {
                $c++;
            }
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
