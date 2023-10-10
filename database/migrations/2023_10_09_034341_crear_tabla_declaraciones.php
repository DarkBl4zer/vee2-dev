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
        Schema::create('vee2_declaraciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_veeduria');
            $table->unsignedBigInteger('id_usuario');
            $table->boolean('firmado')->default(false);
            $table->string('lugar_expedicion', 255)->nullable();
            $table->boolean('funcionario')->nullable();
            $table->unsignedBigInteger('id_profesion')->nullable();
            $table->string('cargo', 255)->nullable();
            $table->string('contrato', 255)->nullable();
            $table->boolean('conflicto')->nullable();
            $table->longText('explicacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('archivo_firmado', 255)->nullable();
            $table->longText('motivo_rechazo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_declaraciones');
    }
};
