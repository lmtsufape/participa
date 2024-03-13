<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        'valor', 'descricao', 'reference', 'codigo', 'status', 'tipo_pagamento_id',
    ];

    public function inscricao()
    {
        return $this->hasOne('App\Models\Inscricao\Inscricao');
    }

    public function tipo_pagamento()
    {
        return $this->belongsTo('App\Models\Inscricao\TipoPagamento');
    }
}
