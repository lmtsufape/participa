<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksPagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'link', 'valor', 'categoria'
    ];

    protected $casts = [
        'data_fim' => 'datetime',
        'data_inicio' => 'datetime',
    ];
}
