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
                for ($i = 0; $i < count($request->input('opcaoCriterio_'.$c)); $i++) {
                    $opCriterio = new OpcoesCriterio();
                    $opCriterio->nome_opcao     = $request->input('opcaoCriterio_'.$c)[$i];
                    $opCriterio->criterio_id    = $criterio->id;
                    $opCriterio->valor_real     = ($i + 1) * (10 / count($request->input('opcaoCriterio_'.$c)));
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

    public function update(Request $request, $id)
    {
        // dd($request);
        $validatedData = $request->validate([
            'nomeCriterioUpdate' => ['string'],
            'pesoCriterioUpdate' => ['integer'],
        ]);

        $criterio = Criterio::find($id);
        
        $criterio->nome = $request->nomeCriterioUpdate;
        $criterio->peso = $request->pesoCriterioUpdate;

        for ($i = 0; $i < count($request->idOpcaoCriterio); $i++) {
            $opcao = OpcoesCriterio::find($request->idOpcaoCriterio[$i]);
            $opcao->nome_opcao = $request->opcaoCriterio[$i];
            $opcao->update();
        }

        $criterio->update();

        return redirect()->route('coord.detalhesEvento', ["eventoId" => $request->eventoId]);
    }
    public function findCriterio(Request $request)
    {
        $criterio = Criterio::find($request->criterioId);
        return $criterio;
    }

    public function destroy($evento_id, $id) {
        $criterio = Criterio::find($id);
        
        foreach ($criterio->opcoes as $opcao) {
            $opcao->delete();
        }

        $criterio->delete();

        return redirect()->route('coord.detalhesEvento', ["eventoId" => $evento_id]);
    }
}
