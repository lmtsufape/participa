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
        $this->nomeSocial = $data['nomeSocial'];
        $this->dataNascimento = $data['dataNascimento'];
        $this->genero = $data['genero'];
        $this->outroGenero = $data['outroGenero'];
        $this->raca = !empty($data['raca']) ? $data['raca'] : [];
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
}
