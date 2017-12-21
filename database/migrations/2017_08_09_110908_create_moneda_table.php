<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonedaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::connection('mysql_sa')->create('moneda',function(Blueprint $table){
            $table->increments('id');
            $table->string('nombre');
            $table->string('iso_a3');
            $table->integer('pais_id')->foreign('pais_id')->references('id')->on('pais');
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

    public function down(){
        Schema::connection('mysql_sa')->drop('moneda');
    }

}
