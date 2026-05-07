<?php

namespace Database\Seeders;

use App\Models\Users\Administrador;
use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministradorSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = User::where('name', 'Administrador')->first()->id;

        Administrador::firstOrCreate([
            'user_id' => $user_id,
        ]);
    }
}
