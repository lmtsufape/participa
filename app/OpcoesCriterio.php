<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpcoesCriterio extends Model
{
    protected $fillable = [
        'nome_opcao','criterio_id',
    ];

    public function criterio() {
        return $this->belongsTo('App\Criterio', 'criterio_id');
    }

    public function avaliacoes() {
        return $this->hasMany('App\Avaliacao', 'opcao_criterio_id');
    }
}
