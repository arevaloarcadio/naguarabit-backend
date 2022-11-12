<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("update `users` set date_updated = NULL where date_updated = '0000-00-00 00:00:00';");
            DB::statement("ALTER TABLE `users` CHANGE `date_updated` `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'fecha ultima actualizacion de datos';");
            DB::statement("ALTER TABLE `users` CHANGE `date_created` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha registro de cuenta';");
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
