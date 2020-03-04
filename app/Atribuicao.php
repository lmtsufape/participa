<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atribuicao extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'confirmacao', 'parecer','revisorId', 'trabalhoId',
  ];

  public function revisor(){
      return $this->belongsTo('App\Revisor', 'revisorId');
  }

  public function trabalho(){
      return $this->belongsTo('App\Trabalho', 'trabalhoId');
  }
}
