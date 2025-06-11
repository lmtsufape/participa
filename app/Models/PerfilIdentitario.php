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
    ];


    public function setAttributes($data)
    {
        $this->nomeSocial = $data['nomeSocial'];
        $this->dataNascimento = $data['dataNascimento'];
        $this->genero = $data['genero'];
        $this->outroGenero = $data['outroGenero'];
        $this->raca = $data['raca'];
        $this->outraRaca = $data['outraRaca'];
        $this->comunidadeTradicional = $data['comunidadeTradicional'];
        $this->nomeComunidadeTradicional = $data['nomeComunidadeTradicional'];
        $this->lgbtqia = $data['lgbtqia'];
        $this->deficienciaIdoso = $data['deficienciaIdoso'];
        $this->necessidadesEspeciais = !empty($data['necessidadesEspeciais']) ? $data['necessidadesEspeciais'] : ['nenhuma'];
        $this->outraNecessidadeEspecial = $data['outraNecessidadeEspecial'];
        $this->associadoAba = $data['associadoAba'];
        $this->receberInfoAba = $data['receberInfoAba'];
        $this->vinculoInstitucional = $data['vinculoInstitucional'];
        $this->participacaoOrganizacao = $data['participacaoOrganizacao'];
        $this->nomeOrganizacao = $data['nomeOrganizacao'];
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
