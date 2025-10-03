<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscritosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $evento;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->evento->inscricaos;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $camposBase = [
            '#', 'Status', 'Pagamento Confirmado', 'Nome Completo', 'Nome Social', 'E-mail', 'CPF/CNPJ/Passaporte',
            'Data de Nascimento', 'Instituição', 'Celular', 'País', 'Estado', 'Cidade', 'Bairro',
            'Rua', 'Número', 'CEP', 'Complemento', 'Categoria', 'Valor (R$)',
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
        $categoria = $inscricao->categoria;



        $valor = $categoria ? number_format($categoria->valor_total, 2, ',', '.') : 'N/A';
        $documento = $user->cpf ?? ($user->cnpj ?? $user->passaporte);


        $valoresBase = [
            $inscricao->id,
            $inscricao->finalizada ? 'Inscrito' : 'Pré-inscrito',
            $inscricao->finalizada ? 'Sim' : 'Não',
            $user->name,
            $user->nomeSocial ?? '',
            $user->email,
            $documento,
            Carbon::parse($user->dataNascimento)->format('d/m/Y'),
            $user->instituicao,
            $user->celular,
            $user->endereco->pais ?? '',
            $user->endereco->uf ?? '',
            $user->endereco->cidade ?? '',
            $user->endereco->bairro ?? '',
            $user->endereco->rua ?? '',
            $user->endereco->numero ?? '',
            $user->endereco->cep ?? '',
            $user->endereco->complemento ?? '',
            $categoria->nome ?? 'Não definida',
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
