<?php

namespace App\Models\Submissao;

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
      return $this->hasMany('App\Models\Submissao\Recurso', 'trabalhoId');
  }

  public function arquivo(){
      return $this->hasOne('App\Models\Submissao\Arquivo', 'trabalhoId');
  }

  public function modalidade(){
      return $this->belongsTo('App\Models\Submissao\Modalidade', 'modalidadeId');
  }

  public function area(){
      return $this->belongsTo('App\Models\Submissao\Area', 'areaId');
  }

  public function autor(){
      return $this->belongsTo('App\Models\Users\User', 'autorId');
  }

  public function coautors(){
      return $this->belongsToMany('App\Models\Users\Coautor');
  }

  public function pareceres(){
      return $this->hasMany('App\Models\Submissao\Parecer', 'trabalhoId');
  }

  public function atribuicoes(){
      return $this->belongsToMany('App\Models\Users\Revisor', 'atribuicaos', 'trabalho_id', 'revisor_id')->withPivot('confirmacao', 'parecer')->withTimestamps();
  }

  public function evento(){
      return $this->belongsTo('App\Models\Submissao\Evento', 'eventoId');
  }

  public function avaliacoes() {
    return $this->hasMany('App\Models\Submissao\Avaliacao', 'trabalho_id');
  }
}
