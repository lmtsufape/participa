<?php

namespace App\Jobs;

use App\Mail\EmailLembretePrazoAvaliacao;
use App\Models\Submissao\Trabalho;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;


class EnviarLembretesRevisoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('Job EnviarLembretesRevisoresJob iniciado.');

        $trabalhosPendentes = Trabalho::whereHas('atribuicoes', function ($q) {
            $q->where('parecer', '!=', 'avaliado')
              ->whereNotNull('prazo_correcao')
              ->where('prazo_correcao', '>', now());
        })->with(['atribuicoes.user', 'evento'])->get();

        Log::info('Trabalhos pendentes recuperados.', ['total' => $trabalhosPendentes->count()]);

        $revisoresNotificados = collect();
        $enviados = 0;

        foreach ($trabalhosPendentes as $trabalho) {
            Log::info('Processando trabalho', ['id' => $trabalho->id, 'titulo' => $trabalho->titulo]);
            foreach ($trabalho->atribuicoes as $atribuicao) {
                Log::info('Verificando atribuição', ['revisor_id' => $atribuicao->id, 'parecer' => $atribuicao->pivot->parecer]);
                if ($atribuicao->pivot->parecer === 'avaliado') {
                    Log::info('Atribuição já avaliada, pulando.', ['revisor_id' => $atribuicao->id]);
                    continue;
                }

                $prazo = Carbon::parse($atribuicao->pivot->prazo_correcao);
                $dias  = now()->diffInDays($prazo, false);
                Log::info('Dias restantes para o prazo', ['dias' => $dias, 'prazo' => $prazo->toDateString()]);

                // Envia lembretes a cada 5 dias (1, 5, 10, 15 dias antes do prazo)
                if ($dias > 0 && ($dias % 5 === 0 || $dias === 1)) {
                    Log::info('Condição para envio de lembrete atendida.', ['dias' => $dias]);
                    $revisor = $atribuicao;
                    $user = $revisor->user;

                    $chave = "{$user->id}_" . now()->toDateString();
                    if ($revisoresNotificados->contains($chave)) {
                        Log::info('Revisor já notificado hoje.', ['user_id' => $user->id]);
                        continue;
                    }

                    $trabsMail = '';
                    $dataLimite = '';

                    $trabalhosPendentesRevisor = $trabalho->evento->trabalhos()
                        ->whereHas('atribuicoes', function ($query) use ($revisor) {
                            $query->where('revisor_id', $revisor->id)
                                  ->where('parecer', '!=', 'avaliado')
                                  ->whereNotNull('prazo_correcao')
                                  ->where('prazo_correcao', '>', now());
                        })->get();

                    Log::info('Trabalhos pendentes para o revisor recuperados.', ['total' => $trabalhosPendentesRevisor->count(), 'revisor_id' => $revisor->id]);

                    foreach ($trabalhosPendentesRevisor as $trabPendente) {
                        $atribuicaoPendente = $trabPendente->atribuicoes()
                            ->where('revisor_id', $revisor->id)
                            ->first();

                        if ($atribuicaoPendente) {
                            $trabsMail = $trabsMail . $trabPendente->titulo . ', ';
                            $dataLimite = $atribuicaoPendente->pivot->prazo_correcao;
                            Log::info('Adicionando trabalho ao lembrete.', ['trabalho_id' => $trabPendente->id, 'titulo' => $trabPendente->titulo]);
                        }
                    }

                    if (!empty($trabsMail)) {
                        $trabsMail = rtrim($trabsMail, ', ');
                        Log::info('Preparando envio de email.', ['email' => $user->email, 'trabalhos' => $trabsMail, 'data_limite' => $dataLimite]);
                        try {
                            Mail::to($user->email)
                                ->send(new EmailLembretePrazoAvaliacao(
                                    $user,
                                    config('app.name') . ' - Lembrete de prazo de avaliação',
                                    $trabsMail,
                                    $dataLimite,
                                    $trabalho->evento,
                                    $trabalho->evento->coordenador,
                                    $dias
                                ));

                            $revisoresNotificados->push($chave);
                            $enviados++;
                            Log::info("Lembrete enviado com sucesso.", ['email' => $user->email, 'dias_restantes' => $dias]);
                        } catch (\Throwable $e) {
                            Log::error("Falha ao enviar lembrete.", ['email' => $user->email, 'erro' => $e->getMessage()]);
                        }
                    } else {
                        Log::info('Nenhum trabalho pendente para enviar no lembrete.', ['user_id' => $user->id]);
                    }
                } else {
                    Log::info('Condição para envio de lembrete NÃO atendida.', ['dias' => $dias]);
                }
            }
        }

        Log::info("Processo concluído! {$enviados} lembretes enviados.");
    }
}
