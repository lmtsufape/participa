<?php

namespace App\Models\Submissao;

use App\Models\Users\CoordEixoTematico;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'eventoId', 'resumo', 'resumo_en', 'resumo_es', 'nome_en', 'nome_es','ordem'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordem');
    }

    public function modalidade()
    {
        return $this->hasMany('App\Models\Submissao\Modalidade', 'areaId');
    }

    public function trabalho()
    {
        return $this->hasMany('App\Models\Submissao\Trabalho', 'areaId');
    }

    public function pertence()
    {
        return $this->hasMany('App\Models\Submissao\Pertence', 'areaId');
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
    }

    public function revisor()
    {
        return $this->hasMany('App\Models\Users\Revisor', 'areaId');
    }

    public function mensagensParecer()
    {
        return $this->hasMany('App\Models\Submissao\MensagemParecer');
    }

    public function coordEixosTematicos(){
        return $this->hasMany(CoordEixoTematico::class);
    }
}
