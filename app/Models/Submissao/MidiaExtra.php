<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class MidiaExtra extends Model
{
    protected $fillable = [
        'caminho', 'extensao', 'trabalho_id',
    ];

    public function trabalho()
    {
        return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalho_id');
    }
}
