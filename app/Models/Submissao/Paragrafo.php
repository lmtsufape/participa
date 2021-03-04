<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Paragrafo extends Model
{
    protected $fillable = ['resposta', 'resposta_id'];

    public function respostaModel()
    {
        return $this->belongsTo('App\Models\Submissao\Resposta');
    }
}
