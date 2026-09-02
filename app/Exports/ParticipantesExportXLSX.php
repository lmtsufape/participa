<?php

namespace App\Exports;

use App\Models\Submissao\Evento;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantesExportXLSX implements FromCollection, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct(private Evento $evento) { }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->evento->inscritos();
    }

    public function headings(): array
    {
        return [
            'nome',
            'cpf',
            'e-mail',
            'carga horária',
        ];
    }

    public function map($inscrito): array
    {
        return [
            $inscrito->user->name,
            $this->formatarCPF($inscrito->user->cpf),
            $inscrito->user->email,
            '0',
        ];
    }

    private function formatarCPF(?string $cpf): ?string
    {
        // Verifica se o CPF é nulo ou vazio
        if (is_null($cpf) || trim($cpf) === '') {
            return null;
        }

        // Remove qualquer caractere que não seja número
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica se o CPF tem exatamente 11 dígitos
        if (strlen($cpf) !== 11) {
            return null; // Retorna null se o CPF não tiver o formato esperado
        }

        // Formata o CPF no padrão 000.000.000-00
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }
}
