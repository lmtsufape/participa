<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coautor extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'ordem','autorId', 'trabalhoId',
  ];

  public function user(){
      $this->belongsTo('App\User', 'autorId');
  }

  public function trabalho(){
      $this->belongsTo('App\Trabalho', 'trabalhoId');
  }
}
