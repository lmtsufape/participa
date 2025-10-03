<?php

namespace App\Models\Inscricao;

use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscricaoEstudante extends Model
{
    use HasFactory;
    protected $table = 'inscricoes_estudantes';

    protected $fillable = [
        'user_id',
        'evento_id',
        'comprovante_path',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
