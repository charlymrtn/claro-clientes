<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_sa')->create('cat__eventos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('nombre', 30)->unique();
            $table->string('tipo_evento', 30);
            $table->string('metodo', 30);
            
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
        Schema::connection('mysql_sa')->drop('cat__eventos');
    }
}
