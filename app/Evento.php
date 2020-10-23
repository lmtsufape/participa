<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome', 'descricao', 'tipo', 'dataInicio', 'dataFim',
      'possuiTaxa', 'valorTaxa', 'fotoEvento',
      'enderecoId', 'coordenadorId',
      'numMaxTrabalhos', 'numMaxCoautores', 'hasResumo',
  ];

  public function endereco(){
      return $this->belongsTo('App\Endereco', 'enderecoId');
  }

  public function atividade(){
      return $this->hasMany('App\Atividade', 'eventoId');
  }

  public function areas(){
      return $this->hasMany('App\Area', 'eventoId');
  }

  public function coordenador(){
      return $this->belongsTo('App\User', 'coordenadorId');
  }


  public function coordComissaoCientifica(){
      return $this->belongsTo('App\CoordComissaoCientifica');
  }

  // public function revisors(){
  //     return $this->belongsToMany('App\Revisor', 'evento_revisor', 'evento_id', 'revisor_id');
  // }

  public function revisors() {
    return $this->hasMany('App\Revisor', 'evento_id');
  }

  function usuariosDaComissao(){
    return $this->belongsToMany('App\User','comissao_eventos','evento_id','user_id');
  }  

  function formEvento() {
    return $this->hasOne('App\FormEvento', 'eventoId');
  }

  function formSubTrab() {
    return $this->hasOne('App\FormSubmTraba', 'eventoId');
  }

  function trabalhos() {
    return $this->hasMany('App\Trabalho', 'eventoId');
  }
  // public function revisores(){
  //   return $this->belongsToMany('App\Revisor', 'evento_revisors', 'evento_id', 'revisor_id')->withPivot('convite_aceito')->withTimestamps();
  // }
}
