<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSolicitudesAndLiquidaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_chofer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name_user');
            $table->string('placa');
            $table->string('dueño_vehiculo');
            $table->string('marca_vehiculo');
            $table->year('año_vehiculo');
            $table->string('tipo_combustible');
            $table->enum('estado_solicitud', ['PENDIENTE', 'APROVADA', 'RECHAZADA'])->default('PENDIENTE');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('liquidacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('banco');
            $table->string('cedula');
            $table->string('numero_de_cuenta');
            $table->decimal('monto_liquidar', 10, 2);
            $table->string('tipo_cuenta');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_chofer');
        Schema::dropIfExists('liquidacion');
    }
}
