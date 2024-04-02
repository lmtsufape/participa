<?php

namespace App\Models\Inscricao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksPagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'link', 'valor', 'data_inicio', 'data_fim', 'categoria'
    ];
}
