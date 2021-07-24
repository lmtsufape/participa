<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class ArquivoAvaliacao extends Model
{
    protected $fillable = [
        'nome', 'versao', 'versaoFinal', 'data', 'revisorId', 'trabalhoId',
    ];

    public function trabalho(){
        return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalhoId');
    }
}
