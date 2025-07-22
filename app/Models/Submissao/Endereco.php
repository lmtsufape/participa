<?php

namespace App\Models\Submissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;
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

    public function getEnderecoFormatado()
    {
        return "{$this->rua}, {$this->numero}, {$this->bairro}, {$this->cidade} - {$this->uf}, {$this->cep}, {$this->pais}";
    }
}
