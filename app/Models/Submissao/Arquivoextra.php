<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Arquivoextra extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'trabalhoId',
    ];

    public function trabalho()
    {
        return $this->belongsTo('App\Models\Submissao\Trabalho', 'trabalhoId');
    }
}
