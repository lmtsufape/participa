<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome', 'versao', 'versaoFinal', 'data', 'eventoId',
  ];

  public function evento(){
      $this->belongsTo('App\Evento', 'eventoId');
  }
}
