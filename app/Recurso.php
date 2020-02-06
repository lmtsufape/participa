<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tituloRecurso', 'corpoRecurso', 'statusAvaliacao', 'trabalhoId', 'comissaoId',
  ];

  public function trabalho(){
      $this->belongsTo('App\Trabalho', 'trabalhoId');
  }

  public function user(){
      $this->belongsTo('App\User', 'comissaoId');
  }
}
