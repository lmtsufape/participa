<?php

namespace App\Console\Commands;

use App\Models\Submissao\Trabalho;
use App\Models\Users\Revisor;
use Illuminate\Console\Command;

class ArrumarDistribuicao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:arrumar-distribuicao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Arruma a distribuição automática dos revisores nos trabalhos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trabalhos = Trabalho::whereHas('atribuicoes', fn ($query) => $query->whereColumn('trabalhos.modalidadeId', '!=', 'revisors.modalidadeId'))
            ->with(['atribuicoes.modalidade'])
            ->get();
        foreach ($trabalhos as $trabalho) {
            $atribuicoes = $trabalho->atribuicoes;
            foreach ($atribuicoes as $revisor) {
                if ($revisor->modalidadeId != $trabalho->modalidadeId) {
                    $revisorCerto = Revisor::where([['modalidadeId', $trabalho->modalidadeId], ['areaId', $trabalho->areaId], ['user_id', $revisor->user_id]])->first();
                    if ($revisorCerto) {
                        $alterados = $trabalho->respostas()->where('revisor_id', $revisor->id)->update(['revisor_id' => $revisorCerto->id]);
                        $parecer = $alterados > 0 ? 'avaliado' : 'processando';
                        $trabalho->atribuicoes()->attach($revisorCerto->id, ['parecer' => $parecer, 'confirmacao' => false]);
                        $trabalho->atribuicoes()->detach($revisor->id);
                    } else {
                        $this->error('O avaliador '.$revisor->user->name.' não é avaliador da modalidade '.$trabalho->modalidade->nome.' por isso a correção para o trabalho '.$trabalho->id.': '.$trabalho->titulo.' não foi possível.');
                    }
                }
            }
        }
    }
}
