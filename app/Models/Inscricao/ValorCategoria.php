<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class ValorCategoria extends Model
{
    protected $fillable = [
        'porcentagem', 'valor', 'inicio_prazo', 'fim_prazo', 'categoria_participante_id',
    ];

    public function categoria()
    {
        return $this->belongsTo('App\Models\Inscricao\CategoriaParticipante', 'categoria_participante_id');
    }
}
