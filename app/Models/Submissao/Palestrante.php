<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Palestrante extends Model
{
    protected $fillable = [
        'nome', 'email', 'palestra_id'
    ];

    public function palestra()
    {
        return $this->belongsTo('App\Models\Submissao\Palestra');
    }
}
