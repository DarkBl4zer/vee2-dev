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
        Schema::create('vee2_usuario_notificacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_perfil');
            $table->string('tipo', 255);
            $table->string('texto', 255);
            $table->string('url', 255);
            $table->boolean('activo')->default(true);
            $table->boolean('eliminado')->default(false);
            $table->unsignedInteger('usuario_crea')->default(1010);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_usuario_notificacion');
    }
};
