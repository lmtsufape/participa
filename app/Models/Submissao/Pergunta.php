<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = ['pergunta', 'form_id'];

    public function form()
    {
        return $this->belongsTo('App\Models\Submissao\Form');
    }

    public function respostas()
    {
        return $this->hasMany('App\Models\Submissao\Resposta');
    }
}
