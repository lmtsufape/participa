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
      'ordem','id', 'trabalhoId', 'eventos_id', 'autorId'
  ];

  public function user(){
      return $this->belongsTo('App\Models\Users\User');
  }

  public function trabalhos(){
      return $this->belongsToMany('App\Models\Submissao\Trabalho');
  }

  public function eventos(){
      return $this->belongsTo('App\Models\Submissao\Evento');
  }
}
