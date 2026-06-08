<?php

namespace App\Models\Submissao;

use App\Enums\EstadoBrasileiro;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'rua', 'numero', 'bairro', 'cidade', 'uf', 'cep', 'complemento', 'pais',
])]
class Endereco extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'uf' => EstadoBrasileiro::class,
        ];
    }

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
