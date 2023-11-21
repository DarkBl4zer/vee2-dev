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
        Schema::create('vee2_cronogramas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_accion');
            $table->unsignedBigInteger('id_etapa');
            $table->string('actividad', 2000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_cronogramas');
    }
};
