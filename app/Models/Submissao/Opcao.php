<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Opcao extends Model
{
    protected $fillable = ['titulo', 'tipo', 'check'];

    public function resposta()
    {
        return $this->belongsTo('App\Models\Submissao\Resposta');
    }
}
