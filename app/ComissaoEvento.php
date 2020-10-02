<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComissaoEvento extends Model
{
    //
    protected $fillable = [
        'especProfissional',
        'eventosId',
        'userId',
    ];

    function users(){
        return $this->belongsToMany('App\User','comissao_eventos','userId');
    }
}
