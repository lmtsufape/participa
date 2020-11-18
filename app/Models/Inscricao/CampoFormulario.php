<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CampoFormulario extends Model
{
    protected $fillable = [
        'titulo', 'tipo', 'obrigatorio', 'evento_id',
    ];

    public function evento() {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function categorias() {
        return $this->belongsToMany('App\Models\Inscricao\CategoriaParticipante', 'campo_necessarios', 'campo_formulario_id', 'categoria_participante_id');
    }
}
