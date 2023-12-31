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
        Schema::create('vee2_listas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 255);
            //$table->string('prefijo', 255)->nullable();
            $table->string('nombre', 255);
            //$table->string('sufijo', 255)->nullable();
            $table->string('valor_texto', 255)->nullable();
            $table->unsignedBigInteger('valor_numero')->nullable();
            $table->unsignedInteger('tipo_valor')->default(1);
            //$table->string('formato', 255)->default('@nombre');
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_listas');
    }
};
