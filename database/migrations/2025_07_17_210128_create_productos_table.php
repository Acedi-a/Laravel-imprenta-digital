<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string(column: 'tipo_impresion'); // Digital, Offset, Gran formato
            $table->string('tipo_papel')->nullable(); // Couché, Bond, Opalina
            $table->string('acabado')->nullable(); // Laminado, Troquelado, Barniz
            $table->string('color')->nullable(); // Color, B/N
            $table->foreignId('tamano_papel_id')->nullable()->constrained('tamano_papel');
            $table->integer('cantidad_minima')->default(1);
            $table->decimal('precio_base', 10, 2);
            $table->decimal('descuento', 5, 2)->nullable();
            $table->string('imagen')->nullable(); // Ruta de la imagen del producto
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
