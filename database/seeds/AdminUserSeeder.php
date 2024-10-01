<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "full_name" => "Programadores",
            "type_id_card" => "V",
            "id_card" => "00000000",
            "profile_image" => null,
            "type_user" => 3,
            "lineaTransporte_id" => null,
            "password" => Hash::make("programador#01."),
            "gender" => "MALE",
            "email" => "contacto@asadvzla.com",
        ]);
    }
}
