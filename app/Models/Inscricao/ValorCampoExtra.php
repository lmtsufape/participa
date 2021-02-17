<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ValorCampoExtra extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'inscricao_id',
        'campo_formulario_id',
        'valor',
    ];
}
