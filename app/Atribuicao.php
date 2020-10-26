<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atribuicao extends Pivot
{
  use SoftDeletes;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = true;
//   protected $fillable = [
//       'confirmacao', 'parecer','revisorId', 'trabalhoId',
//   ];

//   public function revisor(){
//       return $this->belongsTo('App\Revisor', 'revisorId');
//   }

//   public function trabalho(){
//       return $this->belongsTo('App\Trabalho', 'trabalhoId');
//   }
}
