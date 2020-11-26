<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class TipoAtividade extends Model
{   
    protected $fillable = [
        'descricao',
    ];

    public function atividade(){
        return $this->hasOne('App\Models\Submissao\Atividade', 'tipo_id');
    }
}
