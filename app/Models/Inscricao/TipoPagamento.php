<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class TipoPagamento extends Model
{
    public function pagamento()
    {
        return $this->belongsTo('App\Models\Inscricao\Pagamento');
    }
}
