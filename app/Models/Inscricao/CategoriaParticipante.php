<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends Model
{
    protected $fillable = [
        'nome', 'valor_total', 'evento_id',
    ];

    public function evento() {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function valores() {
        return $this->hasMany('App\Models\Inscricao\ValorCategoria', 'categoria_participante_id');
    }

    public function camposNecessarios() {
        return $this->belongsToMany('App\Models\Inscricao\CampoFormulario', 'campo_necessarios', 'categoria_participante_id', 'campo_formulario_id');
    }

}
