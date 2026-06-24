<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Area;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('candidatos_avaliadores')]
#[Fillable(['user_id',
            'evento_id',
            'area_id',
            'link_lattes',
            'resumo_lattes',
            'ja_avaliou_cba',
            'disponibilidade_idiomas',
            'aprovado',
            'justificativa',
])]
class CandidatoAvaliador extends Model
{
    use softDeletes;

    protected array $dates = ['deleted_at'];

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
