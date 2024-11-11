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
            $usuario = User::whereRaw("LOWER(email) = LOWER(?)", [$value])->firstOr(function () use ($fail) {
                $this->lancarErro($fail);
            });
            if (! Inscricao::where([['evento_id', $this->evento->id], ['user_id', $usuario->id], ['finalizada', true]])->exists()) {
                $this->lancarErro($fail);
            }
        }
    }

    private function lancarErro($fail) {
        $fail('usuarioInscritoNoEvento')->translate();
    }
}
