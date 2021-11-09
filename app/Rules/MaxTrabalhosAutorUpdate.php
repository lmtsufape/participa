<?php

namespace App\Rules;

use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class MaxTrabalhosAutorUpdate implements Rule
{
    private $numTrabalhos;
    private $value;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($numTrabalhos)
    {
        $this->numTrabalhos = $numTrabalhos;
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
        Log::info('attribute autor '.$attribute);
        $user = User::where('email', $value)->first();
        if($user != null && $this->numTrabalhos != null) {
            $this->value = $value;
            $trabalho = Trabalho::find(request()->id);
            $qtd = Trabalho::where('eventoId', $trabalho->evento->id)->where('autorId', $user->id)->where('status', '!=','arquivado' )->count();
            if(Trabalho::where('eventoId', $trabalho->evento->id)->where('autorId', $user->id)->where('status', '!=','arquivado' )->get()->contains($trabalho)){
                return $qtd <= $this->numTrabalhos;
            }
            return $qtd < $this->numTrabalhos;
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
        return 'O autor '. $this->value .', já atingiu o número máximo de trabalhos em que pode ser autor.';
    }
}
