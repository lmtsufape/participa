<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trabalho extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'titulo', 'autores', 'data', 'modalidadeId', 'areaId', 'autorId',
  ];

  public function recurso(){
      $this->hasMany('App\Recurso', 'trabalhoId');
  }

  public function arquivo(){
      $this->hasMany('App\Arquivo', 'trabalhoId');
  }

  public function modalidade(){
      $this->belongsTo('App\Modalidade', 'modalidadeId');
  }

  public function area(){
      $this->belongsTo('App\Area', 'areaId');
  }

  public function autor(){
      $this->belongsTo('App\User', 'autorId');
  }

  public function coautor(){
      $this->hasMany('App\Coautor', 'trabalhoId');
  }

  public function parecer(){
      $this->hasMany('App\Parecer', 'trabalhoId');
  }

  public function atribuicao(){
      $this->hasMany('App\Atribuicao', 'trabalhoId');
  }
}
