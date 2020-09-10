<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAtividade extends Model
{   
    protected $fillable = [
        'descricao',
    ];

    public function atividade(){
        return $this->hasOne('App\Atividade', 'tipo_id');
    }
}
