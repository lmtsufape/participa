<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
        ->forEndereco()
        ->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
        ]);
        User::factory()
        ->forEndereco()
        ->create([
            'name' => 'Participante',
            'email' => 'participante@ufrpe.br',
        ]);
        User::factory()
        ->forEndereco()
        ->create([
            'name' => 'CoordComissaoCientifica',
            'email' => 'coordCC@ufrpe.br',
        ]);
        User::factory()
        ->forEndereco()
        ->create([
            'name' => 'CoordComissaoOrganizadora',
            'email' => 'coordCO@ufrpe.br',
        ]);
    }
}
