<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Convidado extends Model
{
    protected $fillable = [
        'nome', 'email', 'funcao',
    ];

    public function atividade(){
        return $this->belongsTo('App\Models\Submissao\Atividade', 'atividade_id');
    }
}
