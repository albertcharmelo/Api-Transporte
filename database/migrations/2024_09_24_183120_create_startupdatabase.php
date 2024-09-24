<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartupdatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linea_transporte', function (Blueprint $table) {
            $table->id();
            $table->string('linea_nombre', 100);
            $table->timestamps();
        });

        Schema::create('linea_transporte_tarifas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('linea_id');
            $table->float('tarifa', 20, 2)->default(0.00);
            $table->timestamps();
        });


        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->default(0);
            $table->unsignedBigInteger('client_id')->default(0);
            $table->unsignedBigInteger('other_user_id')->nullable();
            $table->enum('transaction', ['ADD', 'DISCOUNT', 'RECHARGE', 'TRANSFER', 'RETURN'])->default('ADD');
            $table->float('amount', 20, 2)->nullable();
            $table->string('invoice', 50)->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('users_qr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->string('qr_name')->nullable()->unique();
            $table->string('qr_image')->default('0');
            $table->string('qr_idShow', 200)->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('users_tipo', function (Blueprint $table) {
            $table->id();
            $table->string('permiso', 10)->default('0');
            $table->string('descripcion', 100)->default('0');
            $table->timestamps();
        });

        Schema::create('users_wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->float('creditos', 20, 2)->default(0.00);
            $table->string('idShow', 200)->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('user_location', function (Blueprint $table) {
            $table->id();
            $table->string('latitud', 12)->nullable();
            $table->string('longitud', 12)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('driving', ['true', 'false'])->default('true');
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
        Schema::dropIfExists('linea_transporte');
        Schema::dropIfExists('linea_transporte_tarifas');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('users_qr');
        Schema::dropIfExists('users_tipo');
        Schema::dropIfExists('users_wallet');
        Schema::dropIfExists('user_location');
    }
}
