<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('archivo_id')->nullable()->constrained('archivos');
            $table->integer('cantidad');
            $table->decimal('precio_total', 10, 2);
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'vencida'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
};
