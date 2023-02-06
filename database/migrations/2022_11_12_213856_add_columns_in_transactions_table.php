<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transacciones', function (Blueprint $table) {
            DB::statement("update `transacciones` set date_updated = NULL where date_updated = null;");
            DB::statement("ALTER TABLE `transacciones` CHANGE `date_updated` `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'fecha ultima actualizacion de datos';");
            DB::statement("ALTER TABLE `transacciones` CHANGE `date_created` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha registro';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transacciones', function (Blueprint $table) {
            //
        });
    }
}
