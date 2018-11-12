<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRememberTokenToAdminsClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('clientes', function($table) {
            $table->rememberToken();
        });
        Schema::table('admins', function($table) {
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
        //
        Schema::table('clientes', function($table) {
            $table->dropColumn('remember_token');
        });

        Schema::table('admins', function($table) {
            $table->dropColumn('remember_token');
        });
    }
}
