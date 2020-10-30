<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Criterio;
use App\Modalidade;
use App\OpcoesCriterio;

class CriteriosController extends Controller
{
    public function store(Request $request)
    {   
        // dd($request);
        $quantDeCriterios = (count($request->all())- 3) / 4;
        // dd($quantDeCriterios);
        $c = 0;
        while ($quantDeCriterios != 0) {
            $criterioCadastrado         = false;
            $opcoesCriterioCadastrado   = false;

            if ($request->input('nomeCriterio'.$c) != null && $request->input('pesoCriterio'.$c) != null) {
                // $validatedData = $request->validate([
                //     'nomeCriterio'.$c       => ['string'],
                //     'pesoCriterio'.$c       => ['string'],
                //     'valor_real_opcao_'.$c  => ['string'],
                //     'modalidade'            => ['string'],
                // ]);
                
                $criterio = new Criterio();
                $criterio->nome             = $request->input('nomeCriterio'.$c);
                $criterio->peso             = $request->input('pesoCriterio'.$c);
                $criterio->modalidadeId     = $request->modalidade;
                $criterio->save();

                $criterioCadastrado = true;
            }

            if ($request->input('opcaoCriterio_'.$c) != null && $request->input('valor_real_opcao_'.$c) != null) {
                for ($i = 0; $i < count($request->input('opcaoCriterio_'.$c)); $i++) {
                    $opCriterio = new OpcoesCriterio();
                    $opCriterio->nome_opcao     = $request->input('opcaoCriterio_'.$c)[$i];
                    $opCriterio->criterio_id    = $criterio->id;
                    $opCriterio->valor_real     = $request->input('valor_real_opcao_'.$c)[$i];
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
        $modalidade = Modalidade::find($request->modalidade);
        return redirect()->back()->with(['mensagem' => 'Critério salvo para a modalidade ' . $modalidade->nome . '.']);
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
            $opcao->valor_real = $request->valor_real_criterio[$i];
            $opcao->update();
        }

        $criterio->update();

        return redirect()->back()->with(['mensagem' => 'Critério salvo com sucesso!']);
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

        return redirect()->back()->with(['mensagem' => 'Critério deletado com sucesso!']);
    }
}
