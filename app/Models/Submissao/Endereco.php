<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rua', 'numero', 'bairro', 'cidade', 'uf', 'cep', 'complemento', 'pais',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\Users\User', 'enderecoId');
    }

    public function evento()
    {
        return $this->hasOne('App\Models\Submissao\Evento', 'enderecoId');
    }
}
