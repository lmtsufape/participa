<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome', 'modalidadeId', 'eventoId',
  ];

  public function modalidade(){
      $this->belongsTo('App\Modalidade', 'modalidadeId');
  }

  public function trabalho(){
      $this->hasMany('App\Trabalho', 'areaId');
  }

  public function pertence(){
      $this->hasMany('App\Pertence', 'areaId');
  }

  public function evento(){
      $this->belongsTo('App\Evento', 'eventoId');
  }
}
