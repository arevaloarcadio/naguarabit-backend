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
            $table->string('nombre');
            $table->string('login');
            $table->string('nro_documento');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cod_pais');
            $table->string('cod_cuidad');
            $table->string('url_foto')->nullable();
            $table->string('url_img_documento')->nullable();
            $table->text('observ')->nullable();
            $table->boolean('es_admin');
            $table->boolean('activo');
            $table->rememberToken();
            $table->timestamp('date_primeratransaccion')->nullable();
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
