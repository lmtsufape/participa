<?php

namespace App\Models\Users;

use App\Models\Submissao\Area;
use App\Models\Submissao\Evento;
use Illuminate\Database\Eloquent\Model;

class CoordEixoTematico extends Model
{
    protected $table = 'coordenadores_eixos_tematicos';

    protected $fillable = [
        'user_id',
        'evento_id',
        'area_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function area()//eixo tematico
    {
        return $this->belongsTo(Area::class);
    }
}
