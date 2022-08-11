<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoComissao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome', 'evento_id',
    ];

    public function membros()
    {
        return $this->belongsToMany('App\Models\Users\User')->withPivot('isCoordenador');
    }

    public function evento()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }
}
