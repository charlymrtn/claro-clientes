<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaccion', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->integer('comercio_id');
            $table->boolean('prueba');
            $table->enum('operacion', ['pago', 'preautorizacion', 'autorizacion', 'cancelacion']);
            //Catalogos
            $table->integer('transaccion_estatus_id');
            $table->integer('pais_id');
            $table->integer('moneda_id');

            $table->decimal('monto', 19, 4);
            $table->enum('forma_pago', ['tarjeta-credito', 'tarjeta-debito', 'telmex-recibo', 'paypal', 'applepay', 'androidpay', 'visa-checkout', 'masterpass']);
            $table->json('datos_pago');
            $table->json('datos_antifraude');
            $table->json('datos_comercio');
            $table->json('datos_claropagos');
            $table->json('datos_procesador');
            $table->json('datos_destino');
            $table->primary('uuid');
            //traits
            $table->timestamps();

            // Indices
            $table->index(['comercio_id', 'created_at', 'forma_pago']);
            $table->index(['created_at', 'forma_pago']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaccion');
    }
}
