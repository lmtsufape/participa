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
    'titulo', 'vagas', 'valor', 'descricao', 'local', 
    'carga_horaria', 'palavras_chave', 'visibilidade_participante',
    'eventoId', 'tipo_id', 
  ];

  public function evento(){
      return $this->belongsTo('App\Evento', 'eventoId');
  }

  public function tipoAtividade(){
    return $this->belongsTo('App\TipoAtividade', 'tipo_id');
  }

  public function convidados() {
    return $this->hasMany('App\Convidado', 'atividade_id');
  }

  public function datasAtividade() {
    return $this->hasMany('App\DatasAtividade', 'atividade_id');
  }
}
