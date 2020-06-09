<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmTraba extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    
    protected $fillable = [
        'etiquetatitulotrabalho', 'etiquetaautortrabalho', 'etiquetacoautortrabalho', 'etiquetaresumotrabalho',
        'etiquetaareatrabalho', 'etiquetauploadtrabalho', 'etiquetabaixarregra', 'etiquetabaixartemplate', 'campoextra1', 'campoextra2', 'campoextra3', 'campoextra4', 'campoextra5', 'campoextra6', 'campoextra7',
        'campoextra8', 'campoextra9', 'campoextra10', 'campoextr11', 'campoextra12', 'campoextra13',
        'eventoId',
    ];

    public function evento(){
        return $this->belongsTo('App\Evento', 'eventoId');
    }
}
