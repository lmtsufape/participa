<?php

namespace App\Imports;

use App\Models\Users\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ListaPresencaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            //
        ]);
    }

    public function detectarTipoDocumento(?string $v): ?string
    {
        if (!$v) return null;
        $d = preg_replace('/\D+/', '', $v);
        if (strlen($d) === 11) return 'cpf';
        if (strlen($d) === 14) return 'cnpj';
        $alnum = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $v));
        if ($alnum && preg_match('/^(?=.*[A-Z])[A-Z0-9]{5,12}$/', $alnum)) return 'passaporte';
        return null;
    }

    public function normalizarDocumento(?string $v, ?string $t): array
    {
        if (!$v || !$t) return [null, null];
        return match ($t) {
            'cpf'        => [$this->formatarCPF($v), 'cpf'],
            'cnpj'       => [$this->formatarCNPJ($v), 'cnpj'],
            'passaporte' => [strtoupper(preg_replace('/[^A-Z0-9]/i', '', $v)), 'passaporte'],
            default      => [null, null],
        };
    }

    public function normalizarPassaporte(string $p): string { return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $p)); }
    public function onlyDigits(string $v): string { return preg_replace('/\D+/', '', $v); }

    private function formatarCPF(?string $cpf): ?string
    {
        if (is_null($cpf) || trim($cpf) === '') {
            return null;
        }
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11) {
            return null;
        }
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    private function formatarCNPJ(?string $cnpj): ?string
    {
        if (is_null($cnpj) || trim($cnpj) === '') {
            return null;
        }
        $cnpj = preg_replace('/\D/', '', $cnpj);
        if (strlen($cnpj) !== 14) {
            return null;
        }
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

}
