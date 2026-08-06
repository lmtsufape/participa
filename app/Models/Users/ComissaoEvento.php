<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Users\User;
use App\Models\Submissao\Evento;


class ComissaoEvento extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'evento_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
