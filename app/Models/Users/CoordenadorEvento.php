<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class CoordenadorEvento extends Model
{
    public function evento()
    {
        return $this->hasMany('App\Models\Submissao\Evento', 'coordenadorId');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }
}
