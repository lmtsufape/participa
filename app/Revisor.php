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
        'prazo', 'trabalhosCorrigidos', 'correcoesEmAndamento','user_id', 'eventoId', 'areaId', 'modalidadeId',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function eventos(){
        return $this->belongsToMany('App\Evento', 'evento_revisor', 'evento_id', 'revisor_id');
    }

    public function area(){
        return $this->belongsTo('App\Area', 'areaId');
    }
}
