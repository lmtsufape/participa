<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class TipoApresentacao extends Model
{
    protected $fillable = [
        'tipo',
        'modalidade_id',
    ];
}
