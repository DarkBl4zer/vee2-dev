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
        Schema::create('vee2_acciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_delegada');
            $table->unsignedBigInteger('id_actuacion');
            $table->unsignedBigInteger('id_temap');
            $table->unsignedBigInteger('id_temas')->nullable();
            $table->string('titulo', 2000);
            $table->string('objetivo_general', 2000)->nullable();
            $table->date('fecha_plangestion')->nullable();
            $table->integer('numero_profesionales');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->unsignedBigInteger('estado')->default(1);
            $table->boolean('activo')->default(true);
            $table->integer('year');
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_acciones');
    }
};
