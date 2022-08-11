<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class OpcoesCriterio extends Model
{
    protected $fillable = [
        'nome_opcao', 'criterio_id',
    ];

    public function criterio()
    {
        return $this->belongsTo('App\Models\Submissao\Criterio', 'criterio_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany('App\Models\Submissao\Avaliacao', 'opcao_criterio_id');
    }
}
