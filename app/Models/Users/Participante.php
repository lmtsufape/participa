<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function eventos()
    {
        return $this->belongsTo('App\Models\Submissao\Evento');
    }
}
