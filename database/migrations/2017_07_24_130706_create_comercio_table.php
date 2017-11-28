<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComercioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercio', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('comercio_nombre');
            $table->string('comercio_correo');
            $table->string('comercio_contrasena');
            $table->string('contacto_nombre');
            $table->string('contacto_telefono_empresa');
            $table->string('contacto_correo');
            $table->string('contacto_telefono_comercial');
            $table->string('facturacion_razon_social');
            $table->string('facturacion_responsable_legal');
            $table->string('facturacion_rfc');
            $table->date('facturacion_fecha_alta_legal');
            $table->string('facturacion_direccion');
            $table->string('facturacion_codigo_postal');
            // -------------------------------------------------------------------------
            // Catálogos
            $table->integer('actividad_comercial_id');
            $table->integer('pais_id');
            $table->integer('estado_id');
            // -------------------------------------------------------------------------
            // @todo: Cambiar a referencias de catálogos cuando estos esten disponibles.
            $table->string('facturacion_colonia');
            $table->string('facturacion_municipio');
            $table->string('facturacion_ciudad');
            // -------------------------------------------------------------------------
            $table->enum('estatus', ['nuevo', 'activo', 'inhabilitado']);
            $table->boolean('activo')->default(true);
            // Traits
            $table->timestamps();
            $table->softDeletes();
            // -------------------------------------------------------------------------
            // Índices
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comercio');
    }
}
