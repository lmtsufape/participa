<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'titulo', 'vagas', 'valor', 'descricao', 'local', 'carga_horaria', 'tags', 
      'data_inicio', 'data_fim', 'tipo_id', 'inscricao_id',
  ];

  public function evento(){
      return $this->belongsTo('App\Evento', 'eventoId');
  }
}
