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
        Schema::create('vee2_terminadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_accion');
            $table->unsignedBigInteger('id_delegada');
            $table->string('nom_delegada', 500);
            $table->unsignedBigInteger('id_actuacion');
            $table->string('nom_actuacion', 500);
            $table->unsignedBigInteger('id_temap');
            $table->string('nom_temap', 500);
            $table->unsignedBigInteger('id_temas')->nullable();
            $table->string('nom_temas', 500)->nullable();
            $table->string('titulo', 2000);
            $table->string('objetivo_general', 2000)->nullable();
            $table->date('fecha_plangestion')->nullable();
            $table->integer('numero_profesionales');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->integer('year');
            $table->string('cordis', 100)->nullable();
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_terminadas');
    }
};
