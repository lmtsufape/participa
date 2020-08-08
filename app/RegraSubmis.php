<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegraSubmis extends Model
{
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'nome', 'modalidadeId',
    ];

    public function modalidade(){
        return $this->belongsTo('App\Modalidade', 'modalidadeId');
    }
}
