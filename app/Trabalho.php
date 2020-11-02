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
      'titulo', 'autores', 'data', 'modalidadeId', 'areaId', 'autorId', 'eventoId', 'resumo', 'avaliado',
      'campoextra1simples', 'campoextra2simples', 'campoextra3simples', 'campoextra4simples',
      'campoextra5simples', 'campoextra1grande', 'campoextra2grande', 'campoextra3grande',
      'campoextra4grande', 'campoextra5grande',
  ];

  public function recurso(){
      return $this->hasMany('App\Recurso', 'trabalhoId');
  }

  public function arquivo(){
      return $this->hasOne('App\Arquivo', 'trabalhoId');
  }

  public function modalidade(){
      return $this->belongsTo('App\Modalidade', 'modalidadeId');
  }

  public function area(){
      return $this->belongsTo('App\Area', 'areaId');
  }

  public function autor(){
      return $this->belongsTo('App\User', 'autorId');
  }

  public function coautor(){
      return $this->hasMany('App\Coautor', 'trabalhoId');
  }

  public function pareceres(){
      return $this->hasMany('App\Parecer', 'trabalhoId');
  }

  public function atribuicoes(){
      return $this->belongsToMany('App\Revisor', 'atribuicaos', 'trabalho_id', 'revisor_id')->withPivot('confirmacao', 'parecer')->withTimestamps();
  }

  public function evento(){
      return $this->belongsTo('App\Evento', 'eventoId');
  }

  public function avaliacoes() {
    return $this->hasMany('App\Avaliacao', 'trabalho_id');
  }
}
