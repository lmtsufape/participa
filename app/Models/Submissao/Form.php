<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['titulo', 'modalidadeId', 'instrucoes'];

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidadeId');
    }

    public function perguntas()
    {
        return $this->hasMany('App\Models\Submissao\Pergunta')->orderBy('id');
    }
}
