<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Submissao\Modalidade;

class MaxCoautoresNaModalidade implements Rule
{
    protected $modalidade;

    public function __construct(Modalidade $modalidade)
    {
        $this->modalidade = $modalidade;
    }

    public function passes($attribute, $value)
    {
        if ($this->modalidade->numMaxCoautores === null) {
            return true;
        }

        return count($value) <= $this->modalidade->numMaxCoautores + 1; // +1 pq o autor principal entra na contagem
    }

    public function message()
    {
        return 'Número máximo de coautores para a modalidade "' .$this->modalidade->nome . '" é ' . $this->modalidade->numMaxCoautores . '.';
    }
}
