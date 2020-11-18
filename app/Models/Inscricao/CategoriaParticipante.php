<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends Model
{
    protected $fillable = [
        'nome', 'evento_id',
    ];

    public function evento() {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function valores() {
        return $this->hasMany('App\Models\Inscricao\ValorCategoria', 'categoria_participante_id');
    }

}
