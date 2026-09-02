<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Submissao\Evento;

class InscricaoPCD extends Model
{
    use HasFactory;

    protected $table = 'inscricoes_pcd';

    protected $fillable = [
        'user_id',
        'evento_id',
        'comprovante_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
