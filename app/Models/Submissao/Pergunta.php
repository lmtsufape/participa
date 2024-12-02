<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = ['pergunta', 'form_id', 'visibilidade'];

    public function form()
    {
        return $this->belongsTo('App\Models\Submissao\Form');
    }

    public function respostas()
    {
        return $this->hasMany('App\Models\Submissao\Resposta')->orderBy('created_at');
    }
}
