<?php
// config/paises.php

use Illuminate\Support\Str;

// carrega ISO2 => nome em pt_BR
$listaPtBr = include base_path('vendor/umpirsky/country-list/data/pt_BR/country.php');

return collect($listaPtBr)
    ->mapWithKeys(function (string $nome, string $iso) {
        $slug = Str::slug($nome, '_');
        return [
            $slug => [
                'nome' => $nome,
                'iso'  => strtolower($iso),
            ],
        ];
    })
    ->toArray();
