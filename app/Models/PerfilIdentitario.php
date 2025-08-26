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
        'associadoCPFreire',
        'receberInfoCPFreire',
        'participacaoOrganizacao',
        'nomeOrganizacao',
        'estudoPedagogiaFreiriana',
        'pedagogiasFreirianas',
        'participarEstudoPedagogiaFreiriana',
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
        $this->associadoCPFreire = $data['associadoCPFreire'];
        $this->receberInfoCPFreire = $data['receberInfoCPFreire'];
        $this->participacaoOrganizacao = $data['participacaoOrganizacao'];
        $this->nomeOrganizacao = $data['nomeOrganizacao'];
        $this->estudoPedagogiaFreiriana = $data['estudoPedagogiaFreiriana'];
        $this->pedagogiasFreirianas = $data['pedagogiasFreirianas'];
        $this->participarEstudoPedagogiaFreiriana = $data['participarEstudoPedagogiaFreiriana'];
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
        $this->associadoCPFreire = $data['associadoCPFreire'] ?? $this->associadoCPFreire;
        $this->receberInfoCPFreire = $data['receberInfoCPFreire'] ?? $this->receberInfoCPFreire;
        $this->participacaoOrganizacao = $data['participacaoOrganizacao'] ?? $this->participacaoOrganizacao;
        $this->nomeOrganizacao = $data['nomeOrganizacao'] ?? $this->nomeOrganizacao;
        $this->estudoPedagogiaFreiriana = $data['estudoPedagogiaFreiriana'] ?? $this->estudoPedagogiaFreiriana;
        $this->pedagogiasFreirianas = $data['pedagogiasFreirianas'] ?? $this->pedagogiasFreirianas;
        $this->participarEstudoPedagogiaFreiriana = $data['participarEstudoPedagogiaFreiriana'] ?? $this->participarEstudoPedagogiaFreiriana;
    }
}
