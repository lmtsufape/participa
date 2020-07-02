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
        'etiquetaareatrabalho', 'etiquetauploadtrabalho', 'indicedecampos' , 'etiquetacampoextra1',
        'etiquetacampoextra2', 'etiquetacampoextra3', 'etiquetacampoextra4', 'etiquetacampoextra5', 'tipocampo1', 'tipocampo2',
        'tipocampo3', 'tipocampo4', 'tipocampo5', 'checkcampoextra1', 'checkcampoextra2',
        'checkcampoextra3', 'checkcampoextra4', 'checkcampoextra5', 'eventoId',
    ];

    public function evento(){
        return $this->belongsTo('App\Evento', 'eventoId');
    }
}
