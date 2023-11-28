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
        Schema::create('vee2_planes_gestion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_accion');
            $table->unsignedBigInteger('id_delegada');
            $table->string('pdf_ob_es', 255)->nullable();
            $table->string('pdf_met', 255)->nullable();
            $table->string('pdf_mue', 255)->nullable();
            $table->string('pdf_ctx', 255)->nullable();
            $table->string('pdf_info', 255)->nullable();
            $table->integer('estado')->default(1);
            $table->boolean('activo')->default(true);
            $table->string('archivo_firmado', 255)->nullable();
            $table->string('archivo_cronograma', 255)->nullable();
            $table->string('archivo_acta', 255)->nullable();
            $table->string('original_acta', 255)->nullable();
            $table->date('fecha_informe')->nullable();
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
        Schema::dropIfExists('vee2_planes_gestion');
    }
};
