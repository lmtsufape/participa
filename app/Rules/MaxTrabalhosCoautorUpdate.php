<?php

namespace App\Rules;

use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\User;
use Illuminate\Contracts\Validation\Rule;

class MaxTrabalhosCoautorUpdate implements Rule
{
    private $numCoautores;

    private $value;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($numCoautores)
    {
        $this->numCoautores = $numCoautores;
    }

    /**
     * Determina se a regra de validação passa. Se for o primeiro valor do array de coautores, ou seja o autor,
     * pula a validação. Se não existir usuário para o email informado, também pula a validação já que este é
     * o primeiro trabalho ao qual o usuário está sendo associado. Se o número de coautores ainda não foi
     * definido, o número de trabalhos não tem limite, logo pula a validação. Também é avaliado se o coautor
     * já estava associado ao trabalho.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (str_ends_with($attribute, '0')) {
            return true;
        }
        $user = User::where('email', $value)->first();
        if ($user != null && $this->numCoautores != null && Coautor::where('autorId', $user->id)->first() != null) {
            $this->value = $value;
            $qtd = Coautor::where('autorId', $user->id)->first()->trabalhos()->where('status', '!=', 'arquivado')->where('eventoId', Trabalho::find(request()->id)->evento->id)->count();
            if (Coautor::where('autorId', $user->id)->first()->trabalhos->contains(Trabalho::find(request()->id))) {
                return $qtd <= $this->numCoautores;
            }

            return $qtd < $this->numCoautores;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O coautor '.$this->value.', já atingiu o número máximo de trabalhos em que pode ser coautor.';
    }
}
