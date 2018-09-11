<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_sa')->create('endpoints', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('url', 30);
            $table->boolean('es_activo')->default(true);
            $table->boolean('es_valido')->default(false);
            $table->integer('max_intentos');

            $table->string('comercio_uuid',50);

            /* $table->unsignedInstringteger('comercio_uuid');
            $table->foreign('comercio_uuid')->references('uuid')->on('comercio'); */

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_sa')->drop('endpoints');
    }
}
