<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revisor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prazo', 'trabalhosCorrigidos', 'correcoesEmAndamento','user_id','evento_id', 'areaId', 'modalidadeId',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function evento(){
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function area(){
        return $this->belongsTo('App\Area', 'areaId');
    }

    public function trabalhosAtribuidos(){
        return $this->belongsToMany('App\Trabalho', 'atribuicaos', 'revisor_id', 'trabalho_id')->withPivot('confirmacao', 'parecer')->withTimestamps();
    }
}
