<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
	protected $fillable = [
        'user_id'
    ];
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function eventos(){
        return $this->belongsTo('App\Evento');
    }
}
