<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Memoria extends Model
{
    protected $fillable = ['titulo', 'arquivo', 'link', 'evento_id', 'ordem'];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }
}
