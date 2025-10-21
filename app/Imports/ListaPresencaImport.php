<?php

namespace App\Imports;

use App\Models\User;
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

    private function detectarTipoDocumento(?string $v): ?string
    {
        if (!$v) return null;
        $d = preg_replace('/\D+/', '', $v);
        if (strlen($d) === 11) return 'cpf';
        if (strlen($d) === 14) return 'cnpj';
        $alnum = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $v));
        if ($alnum && preg_match('/^(?=.*[A-Z])[A-Z0-9]{5,12}$/', $alnum)) return 'passaporte';
        return null;
    }

    private function normalizarDocumento(?string $v, ?string $t): array
    {
        if (!$v || !$t) return [null, null];
        return match ($t) {
            'cpf'        => [preg_replace('/\D+/', '', $v), 'cpf'],
            'cnpj'       => [preg_replace('/\D+/', '', $v), 'cnpj'],
            'passaporte' => [strtoupper(preg_replace('/[^A-Z0-9]/i', '', $v)), 'passaporte'],
            default      => [null, null],
        };
    }

    private function normalizarPassaporte(string $p): string { return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $p)); }
    private function onlyDigits(string $v): string { return preg_replace('/\D+/', '', $v); }

}
