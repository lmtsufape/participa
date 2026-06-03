<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class FormEvento extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'etiquetanomeevento', 'etiquetatipoevento', 'etiquetadescricaoevento', 'etiquetadatas',
        'etiquetasubmissoes', 'etiquetabaixarregra', 'etiquetabaixartemplate', 'etiquetaenderecoevento', 'etiquetamoduloinscricao', 'etiquetamoduloprogramacao', 'etiquetamoduloorganizacao',
        'modinscricao', 'modinscricaopcd', 'modprogramacao', 'modorganizacao', 'modsubmissao', 'eventoId',
        'etiquetaarquivo', 'etiquetabaixarinstrucoes',
    ];

    protected $casts = [
        'modinscricao' => 'boolean',
        'modinscricaopcd' => 'boolean',
        'modvalidarinscricao' => 'boolean',
        'modprogramacao' => 'boolean',
        'modorganizacao' => 'boolean',
        'modsubmissao' => 'boolean',
        'modinscritonoevento' => 'boolean',
        'modinscritonaplataforma' => 'boolean',
    ];

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
    }
}
