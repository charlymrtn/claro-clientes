<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_sa')->create('token', function (Blueprint $table) {
            // Identificador
            $table->string('id');
            // Propiedades
            $table->text('token');
            // Catalogos
            $table->uuid('comercio_uuid');
            // Traits
            $table->timestamps();
            $table->softDeletes();

            // Indices
            $table->primary(['comercio_uuid', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_sa')->drop('token');
    }
}
