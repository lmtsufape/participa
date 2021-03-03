<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Paragrafo extends Model
{
    public function resposta()
    {
        return $this->belongsTo('App\Models\Submissao\Resposta');
    }
}
