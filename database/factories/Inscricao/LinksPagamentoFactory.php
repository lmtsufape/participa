<?php

namespace Database\Factories\Inscricao;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinksPagamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 'link', 'valor', 'data_inicio', 'data_fim', 'categoria'
        return [
            'link' => fake()->link,
            'valor' => fake()->price,
            'data_inicio' => fake()->beginDate,
            'data_fim' => fake()->endDate
        ];
    }
}
