<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('full_name');
            $table->enum('type_id_card', ['V', 'E', 'P', 'J', 'G'])->default('V');
            $table->string('id_card', 15)->nullable();
            $table->string('profile_image', 80)->nullable();
            $table->unsignedInteger('type_user')->default(1);
            $table->unsignedBigInteger('lineaTransporte_id')->nullable();
            $table->enum('gender', ['FEMALE', 'MALE', 'NO ESPECIFICO'])->default('NO ESPECIFICO');
            $table->string('email')->unique();
            $table->string('idShow', 200)->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
