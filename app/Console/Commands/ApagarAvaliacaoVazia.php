<?php

namespace App\Console\Commands;

use App\Models\Submissao\Opcao;
use App\Models\Submissao\Paragrafo;
use App\Models\Submissao\Resposta;
use App\Models\Submissao\Trabalho;
use Illuminate\Console\Command;
use LDAP\Result;

class ApagarAvaliacaoVazia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apagar-avaliacao-vazia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trabalhosSemRespostas = Resposta::doesntHave('opcoes')->doesntHave('paragrafo')->pluck('trabalho_id')->unique()->all();
        $trabalhos = Trabalho::whereIn('id', $trabalhosSemRespostas)
            ->with('atribuicoes', fn($query) => $query->where('parecer', 'avaliado'))
            ->get();
        foreach ($trabalhos as $trabalho) {
            foreach ($trabalho->atribuicoes as $revisor) {
                $revisor->pivot->update(['parecer' => 'processando']);
                $respostasId = Resposta::where([['revisor_id', $revisor->id], ['trabalho_id', $trabalho->id]])->pluck('id')->all();
                Opcao::whereIn('resposta_id', $respostasId)->delete();
                Paragrafo::whereIn('resposta_id', $respostasId)->delete();
                Resposta::destroy($respostasId);
            }
        }
    }
}
