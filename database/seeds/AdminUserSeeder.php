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
            "full_name" => "Albert Charmelo",
            "type_id_card" => "V",
            "id_card" => "00000000",
            "profile_image" => null,
            "type_user" => 3,
            "lineaTransporte_id" => null,
            "password" => Hash::make("admin"),
            "gender" => "MALE",
            "email" => "albertcharmelocontacto@gmail.com",
        ]);
    }
}
