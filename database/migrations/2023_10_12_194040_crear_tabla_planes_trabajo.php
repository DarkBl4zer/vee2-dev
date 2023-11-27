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
        Schema::create('vee2_planes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->unsignedBigInteger('id_delegada');
            $table->string('descripcion', 2000)->nullable();
            $table->unsignedBigInteger('estado')->default(1);
            $table->boolean('activo')->default(true);
            $table->integer('version')->default(1);
            $table->string('archivo_firmado', 255)->nullable();
            $table->string('archivo_acta', 255)->nullable();
            $table->string('original_acta', 255)->nullable();
            $table->boolean('vigente')->default(true);
            $table->unsignedBigInteger('id_delegado')->nullable();
            $table->string('fecha_delegado', 255)->nullable();
            $table->unsignedBigInteger('id_coordinador')->nullable();
            $table->string('fecha_coordinador', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_planes_trabajo');
    }
};
