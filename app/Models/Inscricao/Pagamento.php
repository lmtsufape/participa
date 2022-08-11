<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        'valor', 'descricao', 'reference', 'pagseguro_code', 'pagseguro_status', 'tipo_pagamento_id',
    ];

    public function inscricaos()
    {
        return $this->hasOne('App\Models\Inscricao\Inscricao');
    }

    public function tipo_pagamento()
    {
        return $this->hasOne('App\Models\Inscricao\TipoPagamento');
    }
}
