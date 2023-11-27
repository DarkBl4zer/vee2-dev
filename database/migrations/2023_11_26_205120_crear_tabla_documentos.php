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
        Schema::create('vee2_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_accion');
            $table->integer('n_tipo');
            $table->string('t_tipo', 255);
            $table->string('carpeta', 255);
            $table->string('archivo', 255);
            $table->string('n_original', 255);
            $table->string('fecha', 255);
            $table->string('usuario', 255);
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_documentos');
    }
};
