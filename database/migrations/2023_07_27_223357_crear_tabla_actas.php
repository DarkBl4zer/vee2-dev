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
        Schema::create('vee2_actas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tipo_acta');
            $table->string('descripcion', 255)->nullable();
            $table->unsignedBigInteger('id_accion')->nullable();
            $table->boolean('aprobada')->nullable();
            $table->string('archivo', 255)->nullable();
            $table->string('nombre_archivo', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_actas');
    }
};
