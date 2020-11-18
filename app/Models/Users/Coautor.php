<?php

namespace App\Models\Users;

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
      return $this->belongsTo('App\Models\Users\User');
  }

  public function trabalho(){
      return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalhoId');
  }

  public function eventos(){
      return $this->belongsTo('App\Models\Submissao\Evento');
  }
}
