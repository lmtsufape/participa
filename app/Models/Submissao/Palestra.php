<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Palestra extends Model
{
    protected $fillable = [
        'titulo', 'evento_id',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }

    public function palestrantes()
    {
        return $this->hasMany('App\Models\Submissao\Palestrante');
    }
}
