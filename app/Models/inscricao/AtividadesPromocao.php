<?php

namespace App\Models\inscricao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AtividadesPromocao extends Pivot
{
    public $incrementing = true;
    
    protected $fillable = [
        'promocao_id', 'atividade_id'
    ];
}
