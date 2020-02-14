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

  public function user(){
      return $this->belongsTo('App\User', 'revisorId');
  }

  public function trabalho(){
      return $this->belongsTo('App\Trabalho', 'trabalhoId');
  }
}
