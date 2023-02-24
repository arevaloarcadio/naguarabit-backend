<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //account_destiny
        Schema::create('cuentas_destino', function (Blueprint $table) {
            $table->id();
            $table->string('cod_banco');
            $table->string('nrocta')->unique();
            $table->string('tipo_cta');
            $table->string('doctitular');
            $table->string('nombretitular');
            $table->string('email');
            $table->string('telefono');
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
        Schema::dropIfExists('create_account_destiny_tables');
    }
}
