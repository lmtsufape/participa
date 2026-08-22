<?php

namespace App\Rules;

use App\Models\Submissao\Trabalho;
use App\Models\Users\User;
use Illuminate\Contracts\Validation\Rule;

class MaxTrabalhosCoautor implements Rule
{
    private $numMaxCoautores;
    private $value;

    public function __construct($numMaxCoautores)
    {
        $this->numMaxCoautores = $numMaxCoautores;
    }

    public function passes($attribute, $value)
    {
        if (is_null($this->numMaxCoautores) || $this->numMaxCoautores <= 0) {
            return true;
        }

        if ($attribute === 'emailCoautor.0') {
            return true;
        }

        if (empty($value)) {
            return true;
        }

        $user = User::where('email', $value)->first();

        if (!$user) {
            return true;
        }

        $eventoId = request()->input('eventoId');

        $qtdTrabalhosComoCoautor = Trabalho::where('eventoId', $eventoId)
            ->where('status', '!=', 'arquivado')
            ->where('autorId', '!=', $user->id)
            ->whereHas('coautors', function ($query) use ($user) {
                $query->where('autorId', $user->id);
            })
            ->count();

        if ($qtdTrabalhosComoCoautor >= $this->numMaxCoautores) {
            $this->value = $value;
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'O coautor ' . $this->value . ' já atingiu o número máximo de trabalhos permitidos neste evento (' . $this->numMaxCoautores . ').';
    }
}