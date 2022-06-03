<?php

namespace App\Console\Commands;

use App\Models\Submissao\Evento;
use App\Models\Users\CoordComissaoCientifica;
use App\Models\Users\CoordComissaoOrganizadora;
use Illuminate\Console\Command;

class AtualizarCoordenadoresDasComissoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coord:atualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os coordenadores das comisses, cientifica e organizadora, para poderem utilizar multiplos coordenadores';

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
        $eventos = Evento::whereNotNull('coord_comissao_cientifica_id')->get();
        foreach ($eventos as $evento) {
            CoordComissaoCientifica::firstOrCreate(['user_id' => $evento->coord_comissao_cientifica_id, 'eventos_id' => $evento->id]);
        }
        $eventos = Evento::whereNotNull('coord_comissao_organizadora_id')->get();
        foreach ($eventos as $evento) {
            CoordComissaoOrganizadora::firstOrCreate(['user_id' => $evento->coord_comissao_organizadora_id, 'eventos_id' => $evento->id]);
        }
    }
}
