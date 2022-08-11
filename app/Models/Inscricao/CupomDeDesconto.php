<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CupomDeDesconto extends Model
{
    protected $fillable = [
        'identificador', 'valor', 'porcentagem', 'quantidade_aplicacao', 'inicio', 'fim', 'evento_id',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function inscricaos()
    {
        return $this->hasMany('App\Models\Inscricao\Inscricao');
    }
}
