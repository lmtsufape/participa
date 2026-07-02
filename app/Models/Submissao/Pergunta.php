<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = ['pergunta', 'form_id', 'visibilidade', 'ordem'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function respostasPadrao()
    {
        return $this->hasMany(Resposta::class)
            ->whereNull('trabalho_id')
            ->whereNull('revisor_id');
    }

    public function respostasRevisores()
    {
        return $this->hasMany(Resposta::class)
            ->whereNotNull('trabalho_id')
            ->whereNotNull('revisor_id');
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class)->orderBy('created_at');
    }
}
