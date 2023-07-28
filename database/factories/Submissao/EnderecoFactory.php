<?php

namespace Database\Factories\Submissao;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rua' => fake()->streetName,
            'numero' => fake()->buildingNumber,
            'bairro' => fake()->cityPrefix,
            'cidade' => fake()->city,
            'uf' => fake()->stateAbbr,
            'cep' => fake()->postcode,
            'complemento' => '',
        ];
    }
}
