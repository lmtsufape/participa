<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Anuidade extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'anuidades';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pagamento_id',
        'tipo', // 'profissional' ou 'estudante'
        'ano_referencia',
        'validade',
        'status', // 'approved', 'pending', 'rejected'
    ];

    /**
     * Atributos que devem ser convertidos para tipos nativos (Casts).
     *
     * @var array
     */
    protected $casts = [
        'validade' => 'datetime',
        'ano_referencia' => 'integer',
    ];

    /**
     * Relacionamento: Uma anuidade pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para filtrar apenas anuidades aprovadas e dentro do prazo de validade.
     * 
     * Uso: Anuidade::ativas()->get();
     */
    public function scopeAtivas($query)
    {
        return $query->where('status', 'approved')
                     ->where('validade', '>=', now());
    }

    /**
     * Acessório para verificar rapidamente se a anuidade expirou.
     */
    public function getEstaExpiradaAttribute()
    {
        return $this->validade->isPast();
    }
}