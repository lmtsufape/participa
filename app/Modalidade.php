<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nome', 'inicioSubmissao', 'fimSubmissao', 'inicioRevisao', 'fimRevisao', 
      'inicioResultado', 'eventoId', 'texto', 'arquivo', 'caracteres', 'mincaracteres',
      'maxcaracteres', 'palavras', 'minpalavras', 'maxpalavras', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'odt',
      'regra', 'template',
  ];

  public function trabalho(){
      return $this->hasMany('App\Trabalho', 'modalidadeId');
  }
}
