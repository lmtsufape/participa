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
        'dataNascimento',
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
        'associadoAba',
        'receberInfoAba',
        'vinculoInstitucional',
        'participacaoOrganizacao',
        'nomeOrganizacao',
        'userId',
    ];

    protected $casts = [
        'necessidadesEspeciais' => 'array',
        'raca' => 'array',
    ];


    public function setAttributes($data)
    {
        $this->nomeSocial = $data['nomeSocial'] ?? null;
        $this->dataNascimento = $data['dataNascimento'] ?? null;
        $this->genero = $data['genero'] ?? null;
        $this->outroGenero = $data['outroGenero'] ?? null;
        $this->raca = !empty($data['raca']) ? $data['raca'] : [];
        $this->outraRaca = $data['outraRaca'] ?? null;
        $this->comunidadeTradicional = $data['comunidadeTradicional'] ?? null;
        $this->nomeComunidadeTradicional = $data['nomeComunidadeTradicional'] ?? null;
        $this->lgbtqia = $data['lgbtqia'] ?? null;
        $this->deficienciaIdoso = $data['deficienciaIdoso'] ?? null;
        $this->necessidadesEspeciais = !empty($data['necessidadesEspeciais']) ? $data['necessidadesEspeciais'] : ['nenhuma'];
        $this->outraNecessidadeEspecial = $data['outraNecessidadeEspecial'] ?? null;
        $this->associadoAba = $data['associadoAba'] ?? null;
        $this->receberInfoAba = $data['receberInfoAba'] ?? null;
        $this->vinculoInstitucional = $data['vinculoInstitucional'] ?? null;
        $this->participacaoOrganizacao = $data['participacaoOrganizacao'] ?? null;
        $this->nomeOrganizacao = $data['nomeOrganizacao'] ?? null;
    }

    public function editAttributes($data)
    {
        $this->nomeSocial = $data['nomeSocial'] ?? $this->nomeSocial;
        $this->dataNascimento = $data['dataNascimento'] ?? $this->dataNascimento;
        $this->genero = $data['genero'] ?? $this->genero;
        $this->outroGenero = $data['outroGenero'] ?? $this->outroGenero;
        $this->raca = $data['raca'] ?? $this->raca;
        $this->outraRaca = $data['outraRaca'] ?? $this->outraRaca;
        $this->comunidadeTradicional = $data['comunidadeTradicional'] ?? $this->comunidadeTradicional;
        $this->nomeComunidadeTradicional = $data['nomeComunidadeTradicional'] ?? $this->nomeComunidadeTradicional;
        $this->lgbtqia = $data['lgbtqia'] ?? $this->lgbtqia;
        $this->deficienciaIdoso = $data['deficienciaIdoso'] ?? $this->deficienciaIdoso;
        $this->necessidadesEspeciais = !empty($data['necessidadesEspeciais']) ? $data['necessidadesEspeciais'] : $this->necessidadesEspeciais;
        $this->outraNecessidadeEspecial = $data['outraNecessidadeEspecial'] ?? $this->outraNecessidadeEspecial;
        $this->associadoAba = $data['associadoAba'] ?? $this->associadoAba;
        $this->receberInfoAba = $data['receberInfoAba'] ?? $this->receberInfoAba;
        $this->vinculoInstitucional = $data['vinculoInstitucional'] ?? $this->vinculoInstitucional;
        $this->participacaoOrganizacao = $data['participacaoOrganizacao'] ?? $this->participacaoOrganizacao;
        $this->nomeOrganizacao = $data['nomeOrganizacao'] ?? $this->nomeOrganizacao;
    }

}
