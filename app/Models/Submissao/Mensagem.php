<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'conteudo', 'data', 'categoriaRemetente', 'comissaoId',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'comissaoId');
    }
}
