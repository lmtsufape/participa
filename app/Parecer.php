<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parecer extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'resultado', 'revisorId', 'trabalhoId',
  ];

  public function user(){
      $this->belongsTo('App\User', 'revisorId');
  }

  public function trabalho(){
      $this->belongsTo('App\Trabalho', 'trabalhoId');
  }
}
