<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    protected $fillable = [
        'evento_id', 'valor'
    ];

    public function evento() {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function lotes() {
        return $this->hasMany('App\Models\Inscricao\Lote', 'promocao_id');
    }

    public function atividades() {
        return $this->belogsToMany('App\Atividade', 'atividades_promocaos', 'promocao_id', 'atividade_id');
    }
}
