<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;

class Participante extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function eventos()
    {
        return $this->belongsTo(Evento::class);
    }
}
