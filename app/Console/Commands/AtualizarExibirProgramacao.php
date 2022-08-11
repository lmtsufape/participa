<?php

namespace App\Console\Commands;

use App\Models\Submissao\Evento;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class AtualizarExibirProgramacao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manutencao:programacaoexibicao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Evento::whereHas('formEvento', function (Builder $q) {
            $q->where('exibir_calendario_programacao', false);
        })->update(['exibir_pdf' =>  true]);
    }
}
