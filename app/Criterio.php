<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'nome', 'peso', 'modalidadeId',
    ];

    public function modalidade(){
        return $this->belongsTo('App\Modalidade', 'modalidadeId');
    }

    public function opcoes() {
        return $this->hasMany('App\OpcoesCriterio', 'criterio_id');
    }
}
