<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['recargas', 'liquidacion', 'reembolso', 'cobro', 'qr']);
            $table->longText('description');
            $table->string('http_code')->default('500');
            $table->string('reference')->nullable();
            $table->string('amount')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('user_id')->foreign('user_id')->references('id')->on('users');
            $table->string('chofer_id')->foreign('chofer_id')->references('id')->on('datos_chofer')->nullable();
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
        Schema::dropIfExists('app_logs');
    }
}
