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
        Schema::create('tamano_papel', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('alto');
            $table->integer('ancho');
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tamano_papel');
    }
};
