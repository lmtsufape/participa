<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class MensagemParecer extends Model
{
    protected $fillable = [
        'texto',
        'parecer',
        'evento_id',
        'modalidade_id',
        'area_id',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }

    public function modalidade()
    {
        return $this->belongsTo('App\Models\Submissao\Modalidade');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Submissao\Area');
    }
}
