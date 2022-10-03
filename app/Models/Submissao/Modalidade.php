<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'inicioSubmissao', 'fimSubmissao', 'inicioRevisao', 'fimRevisao',
        'inicioResultado', 'eventoId', 'texto', 'arquivo', 'caracteres', 'mincaracteres',
        'maxcaracteres', 'palavras', 'minpalavras', 'maxpalavras', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'zip', 'svg',
        'regra', 'template', 'modelo_apresentacao',
    ];

    public function trabalho()
    {
        return $this->hasMany('App\Models\Submissao\Trabalho', 'modalidadeId');
    }

    public function criterios()
    {
        return $this->hasMany('App\Models\Submissao\Criterio', 'modalidadeId');
    }

    public function revisores()
    {
        return $this->hasMany('App\Models\Users\Revisor', 'modalidadeId');
    }

    public function forms()
    {
        return $this->hasMany('App\Models\Submissao\Form', 'modalidadeId');
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'evento_id');
    }

    public function mensagensParecer()
    {
        return $this->hasMany('App\Models\Submissao\MensagemParecer');
    }

    public function tiposApresentacao()
    {
        return $this->hasMany('App\Models\Submissao\TipoApresentacao');
    }
}
