<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Area;

class CandidatoAvaliador extends Model
{
    protected $table = 'candidatos_avaliadores';
    protected $fillable = [
        'user_id',
        'evento_id',
        'area_id',
        'link_lattes',
        'resumo_lattes',
        'ja_avaliou_cba',
        'disponibilidade_idiomas',
        'aprovado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
