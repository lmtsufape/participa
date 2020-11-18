<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class ValorCategoria extends Model
{
    protected $fillable = [
        'porcentagem', 'valor', 'categoria_participante_id', 'lote_id',
    ];

    public function categoria() {
        return $this->belongsTo('App\Models\Inscricao\CategoriaParticipante', 'categoria_participante_id');
    }

    public function lote() {
        return $this->hasOne('App\Models\Inscricao\Lote', 'lote_id');
    }
}
