<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome', 'eventoId',
  ];

  public function modalidade(){
      return $this->hasMany('App\Models\Submissao\Modalidade', 'areaId');
  }

  public function trabalho(){
      return $this->hasMany('App\Models\Submissao\Trabalho', 'areaId');
  }

  public function pertence(){
      return $this->hasMany('App\Models\Submissao\Pertence', 'areaId');
  }

  public function evento(){
      return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
  }

  public function revisor(){
      return $this->hasMany('App\Models\Users\Revisor', 'areaId');
  }

  function mensagensParecer() {
    return $this->hasMany('App\Models\Submissao\MensagemParecer');
  }
}
