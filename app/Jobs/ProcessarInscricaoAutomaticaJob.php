<?php

namespace App\Jobs;

use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Notifications\InscricaoEvento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessarInscricaoAutomaticaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dados;
    protected $eventoId;
    protected $categoriaId;
    protected $jobId;

    public function __construct($dados, $eventoId, $categoriaId, $jobId)
    {
        $this->dados = $dados;
        $this->eventoId = $eventoId;
        $this->categoriaId = $categoriaId;
        $this->jobId = $jobId;
    }

    public function handle()
    {
        Log::info("Iniciando processamento do job {$this->jobId} com " . count($this->dados) . " registros");
        
        $evento = Evento::find($this->eventoId);
        $categoria = CategoriaParticipante::find($this->categoriaId);
        
        $usuariosNaoCadastrados = [];
        $usuariosJaInscritos = [];
        $usuariosInscritosComSucesso = [];
        $erros = [];

        $total = count($this->dados);
        $processados = 0;

        // Processar em lotes de 10
        $lotes = array_chunk($this->dados, 10);
        
        foreach ($lotes as $loteIndex => $lote) {
            foreach ($lote as $index => $linha) {
                if (empty($linha[0]) || empty($linha[1])) {
                    continue;
                }

                $nome = $linha[0];
                $cpf = $this->normalizarCpf($linha[1]);
                $email = $linha[2] ?? '';

                try {
                    // Verificar se o usuário existe pelo CPF
                    $usuario = User::where('cpf', $cpf)->first();

                    if (!$usuario) {
                        $usuariosNaoCadastrados[] = [
                            'nome' => $nome,
                            'cpf' => $cpf,
                            'email' => $email,
                            'status' => 'Usuário não cadastrado'
                        ];
                        continue;
                    }

                    // Verificar se já está inscrito
                    $inscricaoExistente = Inscricao::where('user_id', $usuario->id)
                        ->where('evento_id', $evento->id)
                        ->where('finalizada', true)
                        ->first();

                    if ($inscricaoExistente) {
                        $usuariosJaInscritos[] = [
                            'nome' => $nome,
                            'cpf' => $cpf,
                            'email' => $email,
                            'status' => 'Já estava inscrito'
                        ];
                        continue;
                    }

                    // Cancelar pré-inscrição se existir
                    $this->cancelarPreInscricao($usuario->id, $evento->id);

                    // Criar nova inscrição
                    $inscricao = new Inscricao();
                    $inscricao->user_id = $usuario->id;
                    $inscricao->evento_id = $evento->id;
                    $inscricao->categoria_participante_id = $categoria->id;
                    $inscricao->finalizada = true;
                    $inscricao->save();

                    // Enviar notificação (apenas para os primeiros 10 do lote)
                    if ($index < 10) {
                        try {
                            $usuario->notify(new InscricaoEvento($evento));
                        } catch (\Exception $e) {
                            Log::error("Erro ao enviar notificação para usuário {$usuario->id}: " . $e->getMessage());
                        }
                    }

                    $usuariosInscritosComSucesso[] = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'status' => 'Inscrito com sucesso'
                    ];

                } catch (\Exception $e) {
                    $erros[] = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'status' => 'Erro: ' . $e->getMessage()
                    ];
                }

                $processados++;
            }

            // Atualizar progresso
            $progresso = ($processados / $total) * 100;
            Cache::put("inscricao_progress_{$this->jobId}", [
                'progresso' => $progresso,
                'processados' => $processados,
                'total' => $total,
                'usuariosNaoCadastrados' => $usuariosNaoCadastrados,
                'usuariosJaInscritos' => $usuariosJaInscritos,
                'usuariosInscritosComSucesso' => $usuariosInscritosComSucesso,
                'erros' => $erros
            ], 3600); // Cache por 1 hora

            // Aguardar 15 segundos entre lotes (exceto no último lote)
            if ($loteIndex < count($lotes) - 1) {
                sleep(15);
            }
        }

        // Marcar como concluído
        Cache::put("inscricao_completed_{$this->jobId}", true, 3600);
    }

    private function normalizarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . 
                   substr($cpf, 3, 3) . '.' . 
                   substr($cpf, 6, 3) . '-' . 
                   substr($cpf, 9, 2);
        }

        return $cpf;
    }

    private function cancelarPreInscricao($user_id, $evento_id)
    {
        $preInscricao = Inscricao::where('user_id', $user_id)
                                 ->where('evento_id', $evento_id)
                                 ->where('finalizada', false)
                                 ->first();

        if ($preInscricao) {
            $preInscricao->delete();
            return true;
        }

        return false;
    }
}
