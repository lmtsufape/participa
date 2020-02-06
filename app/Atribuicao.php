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
      'confirmacao', 'revisorId', 'trabalhoId',
  ];

  public function user(){
      $this->belongsTo('App\User', 'revisorId');
  }

  public function trabalho(){
      $this->belongsTo('App\Trabalho', 'trabalhoId');
  }
}
