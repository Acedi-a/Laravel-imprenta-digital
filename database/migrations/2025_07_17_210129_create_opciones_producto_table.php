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
        Schema::create('opciones_producto', function (Blueprint $table) {
            $table->id();
            $table->integer('producto_id');
            $table->string('nombre_opcion');
            $table->string('valor_opcion');
            $table->decimal('ajuste_precio');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opciones_producto');
    }
};
