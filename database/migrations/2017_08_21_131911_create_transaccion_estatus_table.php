<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionEstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_sa')->create('transaccion_estatus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('indice', 30)->unique();
            $table->string('nombre', 30);
            $table->string('descripcion', 80);
            $table->string('color', 50);
            // Traits
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
        Schema::connection('mysql_sa')->drop('transaccion_estatus');
    }
}
