<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Evento;


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
        return $this->belongsTo(User::class, 'autorId');
    }

    public function trabalhos()
    {
        return $this->belongsToMany(Trabalho::class, 'coautor_trabalho', 'coautor_id', 'trabalho_id');
    }

    public function eventos()
    {
        return $this->belongsTo(Evento::class); 
    }
}
