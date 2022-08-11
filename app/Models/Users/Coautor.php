<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Coautor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ordem', 'id', 'trabalhoId', 'eventos_id', 'autorId',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'autorId');
    }

    public function trabalhos()
    {
        return $this->belongsToMany('App\Models\Submissao\Trabalho', 'coautor_trabalho', 'coautor_id', 'trabalho_id');
    }

    public function eventos()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }
}
