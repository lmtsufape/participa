<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Parecer extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'resultado', 'revisorId', 'trabalhoId', 'parecer_final', 'justificativa'
  ];

  public function revisor(){
      return $this->belongsTo('App\Models\Users\Revisor', 'revisorId');
  }

  public function trabalho(){
      return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalhoId');
  }
}
