<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormTipoSubm extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    
    protected $fillable = [
        'texto', 'arquivo', 'caracteres', 'mincaracteres',
        'maxcaracteres', 'palavras', 'minpalavras', 'maxpalavras', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt', 'modalidadeId',
    ];

    public function modalidade(){
        return $this->belongsTo('App\Modalidade', 'modalidadeId');
    }
}
