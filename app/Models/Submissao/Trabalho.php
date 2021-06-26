<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trabalho extends Model
{
    use SoftDeletes;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  // status = ['rascunho','submetido', 'avaliado', 'corrigido','aprovado','reprovado', 'arquivado']
  protected $fillable = [
      'titulo', 'autores', 'data', 'modalidadeId', 'areaId', 'autorId', 'eventoId', 'resumo', 'avaliado',
      'campoextra1simples', 'campoextra2simples', 'campoextra3simples', 'campoextra4simples',
      'campoextra5simples', 'campoextra1grande', 'campoextra2grande', 'campoextra3grande',
      'campoextra4grande', 'campoextra5grande', 'status'
  ];

  public function recurso(){
      return $this->hasMany('App\Models\Submissao\Recurso', 'trabalhoId');
  }

  public function arquivo(){
      return $this->hasMany('App\Models\Submissao\Arquivo', 'trabalhoId');
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
      return $this->belongsToMany('App\Models\Users\Coautor', 'coautor_trabalho', 'trabalho_id', 'coautor_id');
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

  public function respostas() {
    return $this->hasMany('App\Models\Submissao\Resposta');
  }
}
