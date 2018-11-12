<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW view_users AS
        (SELECT * FROM (
            SELECT id, user, password, remember_token FROM clientes
            UNION ALL
            SELECT id, user, password, remember_token FROM  admins
        ) as union_users);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement( 'DROP VIEW view_users' );
    }
}
