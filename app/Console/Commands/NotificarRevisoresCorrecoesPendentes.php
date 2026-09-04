<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submissao\Trabalho;
use App\Mail\EmailCorrecaoTrabalho;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificarRevisoresCorrecoesPendentes extends Command
{
    protected $signature = 'trabalhos:notificar-correcoes {--evento= : ID específico do evento}';
    protected $description = 'Envia e-mails para os avaliadores de trabalhos que já submeteram a correção corretamente';

    public function handle()
    {
        $eventoId = $this->option('evento');

        $query = Trabalho::with(['evento', 'atribuicoes.user', 'arquivoCorrecao', 'modalidade'])
            ->where('permite_correcao', true);

        if ($eventoId) {
            $query->where('eventoId', $eventoId);
        }

        $trabalhos = $query->get();
        $totalNotificados = 0;

        $this->info("Analisando {$trabalhos->count()} trabalhos...");

        foreach ($trabalhos as $trabalho) {
            // Usa o método que você já tem no Model Trabalho.php
            if ($trabalho->temCorrecaoSubmetida()) {
                
                // Se ainda não tiver a data marcada, salva o timestamp
                if (is_null($trabalho->data_correcao_submetida)) {
                    $trabalho->data_correcao_submetida = $trabalho->updated_at ?? now();
                    $trabalho->save();
                }

                foreach ($trabalho->atribuicoes as $revisor) {
                    if ($revisor->user && $revisor->user->email) {
                        try {
                            Mail::to($revisor->user->email)->send(
                                new EmailCorrecaoTrabalho($trabalho->evento, $trabalho, $revisor)
                            );
                            $totalNotificados++;
                            $this->line("E-mail enviado para: {$revisor->user->email} - Trabalho #{$trabalho->id}");
                        } catch (\Exception $e) {
                            Log::error("Erro ao enviar e-mail para {$revisor->user->email}: " . $e->getMessage());
                            $this->error("Falha ao enviar para {$revisor->user->email}");
                        }
                    }
                }
            }
        }

        $this->info("Concluído! Total de e-mails disparados: {$totalNotificados}");
    }
}