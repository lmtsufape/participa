<?php

namespace App\Rules;

use App\Models\Users\Coautor;
use App\Models\Users\User;
use Illuminate\Contracts\Validation\Rule;

class MaxTrabalhosCoautor implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where('email', $value)->first();
        if($user != null && $this->numCoautores != null) {
            $this->value = $value;
            $qtd = Coautor::where('autorId', $user->id)->first()->trabalhos()->where('status', '!=','arquivado' )->where('eventoId', 18)->count();
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
        return 'O coautor '. $this->value .', já atingiu o número máximo de trabalhos em que pode ser coautor.';
    }
}
