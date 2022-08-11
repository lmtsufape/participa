<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class ArquivoCorrecao extends Model
{
    protected $fillable = [
        'caminho', 'trabalhoId',
    ];

    public function trabalho()
    {
        return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalhoId');
    }
}
