<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventosToEndpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_sa')->table('endpoints', function (Blueprint $table) {
            //
            $table->integer('num_eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_sa')->table('endpoints', function (Blueprint $table) {
            //
        });
    }
}
