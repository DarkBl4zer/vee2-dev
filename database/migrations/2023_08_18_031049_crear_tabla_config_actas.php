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
        Schema::create('vee2_config_actas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tipo_acta');
            $table->boolean('require_aprobacion')->default(false);
            $table->unsignedBigInteger('rol_aprueba')->nullable();
            $table->boolean('require_firma')->default(false);
            $table->string('posicion_firma', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_config_actas');
    }
};
