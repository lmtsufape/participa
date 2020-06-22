<?php

namespace App;

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
        'modinscricao', 'modprogramacao', 'modorganizacao', 'modsubmissao', 'eventoId',
    ];

    public function evento(){
        return $this->belongsTo('App\Evento', 'eventoId');
    }
}
