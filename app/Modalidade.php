<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome',
  ];

  public function area(){
      $this->hasOne('App\Area', 'modalidadeId');
  }

  public function trabalho(){
      $this->hasMany('App\Trabalho', 'trabalhoId');
  }
}
