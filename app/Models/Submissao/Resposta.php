<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $fillable = ['pergunta_id', 'revisor_id', 'trabalho_id'];

    public function pergunta()
    {
        return $this->belongsTo('App\Models\Submissao\Pergunta');
    }

    public function opcoes()
    {
        return $this->hasMany('App\Models\Submissao\Opcao');
    }

    public function paragrafo()
    {
        return $this->hasOne('App\Models\Submissao\Paragrafo');
    }

    public function revisor()
    {
        return $this->belongsTo('App\Models\Users\Revisor');
    }

    public function trabalho()
    {
        return $this->belongsTo('App\Models\Submissao\Trabalho');
    }
}
