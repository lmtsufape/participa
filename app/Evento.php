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
      'nome', 'numeroParticipantes', 'tipo', 'dataInicio', 'dataFim',
      'inicioSubmissao', 'fimSubmissao', 'inicioRevisao', 'fimRevisao',
      'inicioResultado', 'fimResultado', 'possuiTaxa', 'valorTaxa', 'fotoEvento',
      'enderecoId', 'coordenadorId',
  ];

  public function endereco(){
      $this->belongsTo('App\Endereco', 'enderecoId');
  }

  public function atividade(){
      $this->hasOne('App\Atividade', 'eventoId');
  }

  public function area(){
      $this->hasOne('App\Area', 'eventoId');
  }

  public function coordenador(){
      $this->belongsTo('App\User');
  }
}
