<?php

namespace App\Console\Commands;

use App\Models\Submissao\Opcao;
use App\Models\Submissao\Pergunta;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('formularios:normalizar-ordem')]
#[Description('Command description')]
class NormalizarOrdemFormulario extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::transaction(function(){
                $this->normalizarOrdemPerguntas();
                $this->normalizarOrdemOpcoes();
            });

            $this->info('Ordem das perguntas e opções normalizadas.');

        } catch (\Throwable $th) {
            $this->error($th->getMessage());

            return self::FAILURE;
        }

    }
    private function normalizarOrdemPerguntas()
    {
        $agrupado = Pergunta::orderBy('form_id')->orderBy('id')->get()->groupBy('form_id');

        foreach($agrupado as $perguntas){
            foreach($perguntas->values() as $index => $pergunta){
                $pergunta->update(['ordem' => $index + 1]);
            }
        }

        $this->info('Ordem das perguntas normalizada.');

    }

    private function normalizarOrdemOpcoes()
    {
        $agrupado = Opcao::orderBy('resposta_id')->orderBy('id')->get()->groupBy('resposta_id');

        foreach($agrupado as $opcoes){
            foreach($opcoes->values() as $index => $opcao){
                $opcao->update(['ordem' => $index + 1]);
            }
        }

        $this->info('Ordem das opções normalizada.');
    }
}
