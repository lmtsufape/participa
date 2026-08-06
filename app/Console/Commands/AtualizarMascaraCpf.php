<?php

namespace App\Console\Commands;

use App\Models\Users\User;
use Illuminate\Console\Command;

class AtualizarMascaraCpf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:atualizar-mascara-cpf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a máscara dos CPFs dos usuários no banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando atualização dos CPFs...");

        // Buscar usuários cujo CPF **não está no formato 000.000.000-00**
        // Para PostgreSQL
        $users = User::whereRaw("cpf !~ '^[0-9]{3}\\.[0-9]{3}\\.[0-9]{3}-[0-9]{2}$'")
                     ->get();

        if ($users->isEmpty()) {
            $this->info("Todos os CPFs já estão formatados corretamente.");
            return 0;
        }

        foreach ($users as $user) {
            // Remove qualquer caractere que não seja número
            $cpfNumeros = preg_replace('/\D/', '', $user->cpf);

            if (strlen($cpfNumeros) === 11) {
                // Aplica máscara
                $user->cpf = preg_replace(
                    "/(\d{3})(\d{3})(\d{3})(\d{2})/",
                    "$1.$2.$3-$4",
                    $cpfNumeros
                );
                $user->save();
                $this->info("CPF atualizado: {$user->cpf} (ID {$user->id})");
            } else {
                $this->warn("CPF inválido para ID {$user->id}: {$user->cpf}");
            }
        }

        $this->info("Atualização concluída!");
        return 0;
    }
}
