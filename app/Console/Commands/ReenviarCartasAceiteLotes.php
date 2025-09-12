<?php

namespace App\Console\Commands;

use App\Mail\CartaDeAceiteMail;
use App\Models\Submissao\Trabalho;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ReenviarCartasAceiteLotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reenviar-cartas-aceite-lotes
                            {--data=2025-09-01 16:14:00 : Data limite para reenvio (formato: Y-m-d H:i:s)}
                            {--lote=10 : Tamanho do lote (quantos emails por vez)}
                            {--pausa=30 : Pausa entre lotes em segundos}
                            {--dry-run : Apenas simula o processo sem enviar emails}
                            {--continuar : Continua de onde parou (baseado no último ID processado)}
                            {--max-lotes=0 : Máximo de lotes a processar (0 = sem limite)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenvia cartas de aceite em lotes seguros com controle de taxa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dataLimite = $this->option('data');
        $tamanhoLote = (int) $this->option('lote');
        $pausaSegundos = (int) $this->option('pausa');
        $dryRun = $this->option('dry-run');
        $continuar = $this->option('continuar');
        $maxLotes = (int) $this->option('max-lotes');

        try {
            $dataLimiteCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $dataLimite);
        } catch (\Exception $e) {
            $this->error('Formato de data inválido. Use: Y-m-d H:i:s (ex: 2025-09-01 16:14:00)');
            return 1;
        }

        $this->info("=== REENVIO DE CARTAS DE ACEITE EM LOTES ===");
        $this->info("Data limite: {$dataLimiteCarbon->format('d/m/Y H:i:s')}");
        $this->info("Tamanho do lote: {$tamanhoLote} emails");
        $this->info("Pausa entre lotes: {$pausaSegundos} segundos");

        if ($dryRun) {
            $this->warn('MODO SIMULAÇÃO ATIVADO - Nenhum email será enviado');
        }

        if ($maxLotes > 0) {
            $this->info("Máximo de lotes: {$maxLotes}");
        }

        $query = Trabalho::where('aprovado', true)
            ->where('aprovacao_emitida_em', '<', $dataLimiteCarbon)
            ->whereNotNull('aprovacao_emitida_em')
            ->whereNotNull('hash_codigo_aprovacao');

        if ($continuar) {
            $ultimoId = $this->getUltimoIdProcessado();
            if ($ultimoId) {
                $query->where('id', '>', $ultimoId);
                $this->info("Continuando a partir do ID: {$ultimoId}");
            }
        }

        $totalTrabalhos = $query->count();

        if ($totalTrabalhos === 0) {
            $this->info('Nenhum trabalho encontrado para processamento.');
            return 0;
        }

        $totalLotes = ceil($totalTrabalhos / $tamanhoLote);
        $lotesParaProcessar = $maxLotes > 0 ? min($totalLotes, $maxLotes) : $totalLotes;

        $this->info("Total de trabalhos: {$totalTrabalhos}");
        $this->info("Total de lotes: {$totalLotes}");
        $this->info("Lotes a processar: {$lotesParaProcessar}");
        $this->newLine();

        if (!$dryRun && !$this->confirm('Deseja continuar com o processamento?')) {
            $this->info('Operação cancelada pelo usuário.');
            return 0;
        }

        $sucessos = 0;
        $erros = 0;
        $lotesProcessados = 0;

        for ($lote = 1; $lote <= $lotesParaProcessar; $lote++) {
            $this->info("=== PROCESSANDO LOTE {$lote}/{$lotesParaProcessar} ===");

            // Buscar trabalhos do lote atual
            $queryLote = Trabalho::where('aprovado', true)
                ->where('aprovacao_emitida_em', '<', $dataLimiteCarbon)
                ->whereNotNull('aprovacao_emitida_em')
                ->whereNotNull('hash_codigo_aprovacao')
                ->with(['autor', 'coautors.user', 'modalidade', 'evento'])
                ->orderBy('id');

            if ($continuar && $lote === 1) {
                $ultimoId = $this->getUltimoIdProcessado();
                if ($ultimoId) {
                    $queryLote->where('id', '>', $ultimoId);
                }
            }

            $trabalhosLote = $queryLote->limit($tamanhoLote)->get();

            if ($trabalhosLote->isEmpty()) {
                $this->info('Nenhum trabalho encontrado neste lote.');
                break;
            }

            $this->info("Processando {$trabalhosLote->count()} trabalhos...");

            $bar = $this->output->createProgressBar($trabalhosLote->count());
            $bar->start();

            foreach ($trabalhosLote as $trabalho) {
                try {
                    Log::info("Processando trabalho ID: {$trabalho->id}");

                    $novoCodigo = Trabalho::gerarCodigo();
                    $novoHash   = hash('sha256', str_replace('-', '', $novoCodigo));

                    if (!$dryRun) {
                        Mail::to($trabalho->autor->email)->send(new CartaDeAceiteMail($trabalho, $novoCodigo));

                        $trabalho->hash_codigo_aprovacao = $novoHash;
                        $trabalho->aprovacao_emitida_em = now();

                        $trabalho->save();

                        $this->salvarUltimoIdProcessado($trabalho->id);
                    }

                    $sucessos++;
                    $status = $dryRun ? '[SIM]' : '✓';
                    $this->line("\n{$status} ID {$trabalho->id}: {$trabalho->autor->email}");

                } catch (\Exception $e) {
                    $erros++;
                    $this->line("\n✗ Erro ID {$trabalho->id}: " . $e->getMessage());
                }

                $bar->advance();
            }

            $bar->finish();
            $lotesProcessados++;

            $this->newLine();
            $this->info("Lote {$lote} concluído - Sucessos: {$sucessos}, Erros: {$erros}");

            if ($lote < $lotesParaProcessar && $pausaSegundos > 0) {
                $this->info("Aguardando {$pausaSegundos} segundos antes do próximo lote...");
                sleep($pausaSegundos);
            }
        }

        $this->newLine();
        $this->info("=== PROCESSAMENTO CONCLUÍDO ===");
        $this->info("Lotes processados: {$lotesProcessados}");
        $this->info("Total de sucessos: {$sucessos}");
        $this->info("Total de erros: {$erros}");

        return 0;
    }

    private function getUltimoIdProcessado()
    {
        $arquivo = storage_path('app/ultimo_id_cartas_aceite.txt');
        if (file_exists($arquivo)) {
            return (int) trim(file_get_contents($arquivo));
        }
        return null;
    }

    private function salvarUltimoIdProcessado($id)
    {
        $arquivo = storage_path('app/ultimo_id_cartas_aceite.txt');
        file_put_contents($arquivo, $id);
    }
}
