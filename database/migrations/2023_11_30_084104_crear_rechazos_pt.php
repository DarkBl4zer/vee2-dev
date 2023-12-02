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
        Schema::create('vee2_rechazos_pt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plant');
            $table->dateTime('fecha_rechazo');
            $table->string('texto_rechazo', 3000);
            $table->string('nombre_rechazo', 255);
            $table->dateTime('fecha_respuesta')->nullable();
            $table->string('texto_respuesta', 3000)->nullable();
            $table->string('nombre_respuesta', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_rechazos_pt');
    }
};
