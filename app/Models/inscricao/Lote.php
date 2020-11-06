<?php

namespace App\Models\inscricao;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'promocao_id', 'inicio_validade', 'fim_validade', 'quantidade_de_aplicacoes'
    ];

    public function promocao() {
        return $this->belongsTo('App\Models\inscricao\Promocao', 'promocao_id');
    }
}
