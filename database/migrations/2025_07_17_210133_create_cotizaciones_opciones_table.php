<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cotizaciones_opciones', function (Blueprint $table) {
            $table->foreignId('idCotizacion')
                ->constrained('cotizaciones')
                ->onDelete('cascade');

            $table->foreignId('idOpcionProducto')
                ->constrained('opciones_producto')
                ->onDelete('cascade');

            $table->primary(['idCotizacion', 'idOpcionProducto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_opciones');
    }
};