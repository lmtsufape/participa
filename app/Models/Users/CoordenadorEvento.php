<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Users\User;
use App\Models\Submissao\Evento;

class CoordenadorEvento extends Pivot
{
    protected $table = 'coordenador_eventos';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'eventos_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(User::class, 'eventos_id');
    }
}
