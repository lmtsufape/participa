<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class TipoComissao extends Model
{
    protected $fillable = [
        'nome', 'evento_id'
    ];

    public function membros(){
        return $this->belongsToMany('App\Models\Users\User');
    }

    public function evento() {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }
}
