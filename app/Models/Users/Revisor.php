<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Revisor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prazo', 'trabalhosCorrigidos', 'correcoesEmAndamento', 'user_id', 'evento_id', 'areaId', 'modalidadeId',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'user_id');
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Submissao\Area', 'areaId');
    }

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidadeId');
    }

    public function trabalhosAtribuidos()
    {
        return $this->belongsToMany('App\Models\Submissao\Trabalho', 'atribuicaos', 'revisor_id', 'trabalho_id')->withPivot('confirmacao', 'parecer')->withTimestamps();
    }

    public function avaliacoes()
    {
        return $this->hasMany('App\Models\Submissao\Avaliacao', 'trabalho_id');
    }

    public function respostas()
    {
        return $this->hasMany('App\Models\Submissao\Resposta');
    }
}
