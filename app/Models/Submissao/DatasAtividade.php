<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class DatasAtividade extends Model
{
    protected $fillable = [
        'data', 'hora_inicio', 'hora_fim',
    ];

    public function atividade()
    {
        return $this->belongsTo('App\Models\Submissao\Atividade', 'atividade_id');
    }
}
