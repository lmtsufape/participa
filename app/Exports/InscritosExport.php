<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use App\Models\PerfilIdentitario; 
use App\Models\User; 
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class InscritosExport implements FromQuery, WithHeadings, WithMapping 
{
    protected $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return $this->evento->inscricaos()->with([
            'user.endereco',
            'user.perfilIdentitario', 
            'categoria',
            'camposPreenchidos.campoFormulario'
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $camposBase = [
            '#', 'Status', 'Pagamento Confirmado', 'Nome Completo', 'Nome Social', 'E-mail', 'CPF/CNPJ/Passaporte',
            'Data de Nascimento', 'Gênero', 'Raça/Cor', 'Comunidade Tradicional', 'LGBTQIA+',
            'Pessoa Idosa ou com Deficiência', 'Necessidades Especiais', 'Associado à ABA',
            'Instituição', 'Celular', 'País', 'Estado', 'Cidade', 'Bairro', 'Rua', 'Número', 'CEP', 'Complemento',
            'Categoria', 'Valor (R$)',
        ];

        $camposExtras = $this->evento->camposFormulario()->pluck('titulo')->all();

        return array_merge($camposBase, $camposExtras);
    }

    /**
     * @param mixed $inscricao
     * @return array
     */
    public function map($inscricao): array
    {
        $user = $inscricao->user;

        if (!$user) {
            return [
                $inscricao->id,
                'Erro: Usuário não encontrado para esta inscrição.',
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' 
            ];
        }

        $categoria = $inscricao->categoria;
        
        $perfil = $user->perfilIdentitario;

        $valor = $categoria ? number_format($categoria->valor_total, 2, ',', '.') : 'N/A';
        $documento = $user->cpf ?? ($user->cnpj ?? $user->passaporte);
        
        $generoValue = optional($perfil)->genero;
        $genero = $generoValue === 'outro' ? optional($perfil)->outroGenero : ucfirst($generoValue ?? '');

        $racaArray = [];
        if ($perfil && !empty($perfil->raca)) {
            $racaData = is_array($perfil->raca) ? $perfil->raca : json_decode($perfil->raca, true);
            if (is_array($racaData)) {
                $racaArray = $racaData;
            } elseif ($perfil->raca) {
                $racaArray = [$perfil->raca];
            }
        }
        $raca = implode(', ', array_map(fn($item) => ucfirst(str_replace('_', ' ', $item)), $racaArray));
        if ($perfil && str_contains($raca, 'Outra raca')) {
            $raca = 'Outra: ' . ($perfil->outraRaca ?? '');
        }

        $necessidadesArray = [];
        if ($perfil && !empty($perfil->necessidadesEspeciais)) {
            $necessidadesData = is_array($perfil->necessidadesEspeciais) ? $perfil->necessidadesEspeciais : json_decode($perfil->necessidadesEspeciais, true);
            if (is_array($necessidadesData)) {
                $necessidadesArray = $necessidadesData;
            } elseif ($perfil->necessidadesEspeciais) {
                $necessidadesArray = [$perfil->necessidadesEspeciais];
            }
        }
        $necessidades = implode(', ', $necessidadesArray);

        $valoresBase = [
            $inscricao->id,
            $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
            $inscricao->finalizada ? 'Sim' : 'Não',
            $user->name,
            optional($perfil)->nomeSocial ?? '',
            $user->email,
            $documento,
            $perfil && $perfil->dataNascimento ? Carbon::parse($perfil->dataNascimento)->format('d/m/Y') : '',
            $genero,
            $raca,
            (optional($perfil)->comunidadeTradicional ?? false) ? optional($perfil)->nomeComunidadeTradicional : 'Não',
            (optional($perfil)->lgbtqia ?? false) ? 'Sim' : 'Não',
            (optional($perfil)->deficienciaIdoso ?? false) ? 'Sim' : 'Não',
            $necessidades,
            (optional($perfil)->associadoAba ?? false) ? 'Sim' : 'Não',
            $user->instituicao,
            $user->celular,
            optional($user->endereco)->pais ?? '',
            optional($user->endereco)->uf ?? '',
            optional($user->endereco)->cidade ?? '',
            optional($user->endereco)->bairro ?? '',
            optional($user->endereco)->rua ?? '',
            optional($user->endereco)->numero ?? '',
            optional($user->endereco)->cep ?? '',
            optional($user->endereco)->complemento ?? '',
            optional($categoria)->nome ?? 'Não definida',
            $valor,
        ];

        $camposExtrasValores = [];
        $camposFormulario = $this->evento->camposFormulario;
        foreach ($camposFormulario as $campo) {
            $campoPreenchido = $inscricao->camposPreenchidos->firstWhere('id', $campo->id);
            $camposExtrasValores[] = $campoPreenchido ? $campoPreenchido->pivot->valor : '';
        }

        return array_merge($valoresBase, $camposExtrasValores);
    }
}