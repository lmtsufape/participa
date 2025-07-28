<?php

namespace App\Rules;

use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CoautorInscritoNoEvento implements ValidationRule
{

    public function __construct(private Evento $evento)
    { }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->evento->formEvento->modinscritonoevento) {
            // 1) busca o usuário
            $usuario = User::whereRaw("LOWER(email) = LOWER(?)", [$value])
                        ->first();  // troquei firstOr() por first()

            // 2) se não existir, dispara erro e sai
            if (! $usuario) {
                $this->lancarErro($fail);
                return;
            }

            // 3) verifica inscrição (restante da sua lógica original)
            if (! Inscricao::where([
                    ['evento_id', $this->evento->id],
                    ['user_id',    $usuario->id],
                    ['finalizada', true],
                ])->exists()
            ) {
                $this->lancarErro($fail);
            }
        }
    }


    private function lancarErro($fail) {
        $fail('usuarioInscritoNoEvento')->translate();
    }
}
