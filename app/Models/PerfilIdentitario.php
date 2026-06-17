<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilIdentitario extends Model
{
    use HasFactory;

    protected $table = 'perfil_identitarios';

    protected $fillable = [
        'nomeSocial',
        'genero',
        'outroGenero',
        'raca',
        'outraRaca',
        'comunidadeTradicional',
        'nomeComunidadeTradicional',
        'lgbtqia',
        'deficienciaIdoso',
        'necessidadesEspeciais',
        'outraNecessidadeEspecial',
        'vinculoInstitucional',
        'participacaoOrganizacao',
        'nomeOrganizacao',
        'user_id',
    ];

    protected $casts = [
        'necessidadesEspeciais' => 'array',
        'raca' => 'array',
    ];
}
