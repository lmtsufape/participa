<?php

namespace App\Rules;

use App\Models\Submissao\Evento;
use Illuminate\Contracts\Validation\Rule;

class NaoESubEvento implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina se o evento não é um subevento
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Evento::find($value)->eventoPai == null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Não é permitido criar um subevento de outro subevento';
    }
}
