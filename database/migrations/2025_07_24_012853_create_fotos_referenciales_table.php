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
        Schema::create('foto_referenciales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamano_papel_id')->constrained('tamano_papel')->onDelete('cascade');
            $table->string('url'); // Ruta o URL de la imagen
            $table->string('descripcion')->nullable(); // Descripción opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos_referenciales');
    }
};
