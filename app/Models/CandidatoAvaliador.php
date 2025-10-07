<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Area;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidatoAvaliador extends Model
{
    use softDeletes;
    protected $table = 'candidatos_avaliadores';
    protected $fillable = [
        'user_id',
        'evento_id',
        'area_id',
        'link_lattes',
        'resumo_lattes',
        'ja_avaliou',
        'disponibilidade_idiomas',
        'aprovado',
        'em_analise'
    ];

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
