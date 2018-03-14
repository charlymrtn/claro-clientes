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
        Schema::connection('mysql_sa')->create('transaccion', function (Blueprint $table) {
            $table->uuid('uuid');
            //Catalogos
            $table->uuid('comercio_uuid');
            $table->integer('transaccion_estatus_id');
            $table->integer('pais_id');
            $table->integer('moneda_id');
            // Datos de transaccion
            $table->boolean('prueba');
            $table->decimal('monto', 19, 4);
            $table->enum('operacion', ['pago', 'preautorizacion', 'autorizacion', 'cancelacion']);
            $table->enum('forma_pago', ['tarjeta', 'telmex-recibo', 'telcel-recibo', 'paypal', 'applepay', 'androidpay', 'visa-checkout', 'masterpass']);
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
            $table->index(['comercio_uuid', 'created_at', 'forma_pago']);
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
        Schema::connection('mysql_sa')->dropIfExists('transaccion');
    }
}
