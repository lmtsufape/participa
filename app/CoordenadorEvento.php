<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoordenadorEvento extends Model
{

	public function evento(){
        return $this->hasMany('App\Evento', 'coordenadorId');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
