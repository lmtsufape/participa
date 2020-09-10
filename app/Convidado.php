<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Convidado extends Model
{
    protected $fillable = [
        'nome', 'email', 'funcao',
    ];

    public function atividade(){
        return $this->belongsTo('App\Atividade', 'atividade_id');
    }
}
