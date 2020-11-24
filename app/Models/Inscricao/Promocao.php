<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    protected $fillable = [
        'evento_id', 'valor', 'identificador', 'descricao'
    ];

    public function evento() {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function lotes() {
        return $this->hasMany('App\Models\Inscricao\Lote', 'promocao_id');
    }

    public function atividades() {
        return $this->belongsToMany('App\Atividade', 'atividades_promocaos', 'promocao_id', 'atividade_id');
    }

    public function categorias() {
        return $this->belongsToMany('App\Models\Inscricao\CategoriaParticipante', 'exibir_promocaos', 'promocao_id', 'categoria_participante_id');
    }
}
