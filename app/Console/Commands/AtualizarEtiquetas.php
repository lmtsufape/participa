<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AtualizarEtiquetas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manutencao:atualizaretiquetaseventos';

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
        DB::table('form_subm_trabas')->where('etiquetatitulotrabalho', 'Titulo')->update(['etiquetatitulotrabalho' => 'Título']);
        DB::table('form_subm_trabas')->where('etiquetaautortrabalho', 'Autor')->update(['etiquetaautortrabalho' => 'Autor(a)']);
        DB::table('form_subm_trabas')->where('etiquetacoautortrabalho', 'Co-Autor')->update(['etiquetacoautortrabalho' => 'Coautor(a)']);
        DB::table('form_subm_trabas')->where('etiquetauploadtrabalho', 'Upload de Trabalho')->update(['etiquetauploadtrabalho' => 'Envio de Trabalho']);
    }
}
