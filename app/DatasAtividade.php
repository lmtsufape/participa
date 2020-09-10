<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasAtividade extends Model
{   
    protected $fillable = [
        'data', 'hora_inicio', 'hora_fim',
    ];

    public function atividade(){
        return $this->belongsTo('App\Atividade', 'atividade_id');
    }
}
