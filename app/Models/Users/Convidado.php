<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Submissao\Atividade;

class Convidado extends Model
{
    protected $fillable = [
        'nome', 'email', 'funcao',
    ];

    public function atividade()
    {
        return $this->belongsTo(Atividade::class, 'atividade_id');
    }
}
