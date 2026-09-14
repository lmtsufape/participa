<?php

namespace App\Console\Commands;

use App\Models\Submissao\Pergunta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class NormalizarRespostasOpcoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:respostas-opcoes {--simular}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $simular = $this->option('simular');

        if ($simular) {
            $this->info('Executando em modo simulação. Nenhuma alteração será salva.');
        }

        $totalAtualizadas = 0;
        $totalNaoEncontradas = 0;
        $totalAmbiguas = 0;

        DB::beginTransaction();

        try {
            $perguntas = Pergunta::with([
                'respostas.opcoes',
            ])->orderBy('ordem')->get();

            foreach ($perguntas as $pergunta) {
                $respostaPadrao = $pergunta->respostas
                    ->whereNull('revisor_id')
                    ->whereNull('trabalho_id')
                    ->sortBy('ordem')
                    ->first();

                if (!$respostaPadrao) {
                    $this->warn("Pergunta {$pergunta->id} não possui resposta padrão.");
                    continue;
                }

                $opcoesPadrao = $respostaPadrao->opcoes
                    ->whereNull('parent_id');

                $respostasNaoPadrao = $pergunta->respostas->filter(function ($resposta) {
                    return !is_null($resposta->revisor_id)
                        || !is_null($resposta->trabalho_id);
                });

                foreach ($respostasNaoPadrao as $resposta) {
                    foreach ($resposta->opcoes as $opcaoMarcada) {
                        if (!is_null($opcaoMarcada->parent_id)) {
                            continue;
                        }

                        $opcoesCompativeis = $opcoesPadrao->filter(function ($opcaoPadrao) use ($opcaoMarcada) {
                            return $this->normalizarTexto($opcaoPadrao->titulo) === $this->normalizarTexto($opcaoMarcada->titulo)
                                && $opcaoPadrao->tipo === $opcaoMarcada->tipo;
                        });

                        if ($opcoesCompativeis->count() === 0) {
                            $totalNaoEncontradas++;

                            $this->warn(
                                "Sem opção padrão: opção {$opcaoMarcada->id}, pergunta {$pergunta->id}, título '{$opcaoMarcada->titulo}', tipo '{$opcaoMarcada->tipo}'"
                            );

                            continue;
                        }

                        if ($opcoesCompativeis->count() > 1) {
                            $totalAmbiguas++;

                            $this->warn(
                                "Ambígua: opção {$opcaoMarcada->id}, pergunta {$pergunta->id}, título '{$opcaoMarcada->titulo}', tipo '{$opcaoMarcada->tipo}'"
                            );

                            continue;
                        }

                        $opcaoPadrao = $opcoesCompativeis->first();

                        $this->info(
                            "Opção marcada {$opcaoMarcada->id} receberá parent_id {$opcaoPadrao->id}"
                        );

                        if (!$simular) {
                            $opcaoMarcada->update([
                                'parent_id' => $opcaoPadrao->id,
                            ]);
                        }

                        $totalAtualizadas++;
                    }
                }
            }

            if ($simular) {
                DB::rollBack();
            } else {
                DB::commit();
            }

            $this->newLine();
            $this->info("Total atualizadas: {$totalAtualizadas}");
            $this->info("Total sem padrão encontrada: {$totalNaoEncontradas}");
            $this->info("Total ambíguas: {$totalAmbiguas}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function normalizarTexto(string $texto): string
    {
        return Str::of($texto)
            ->trim()
            ->lower()
            ->squish()
            ->toString();
    }


}
