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
        Schema::create('vee2_cronograma_semana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cronograma');
            $table->string('semana', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_cronograma_semana');
    }
};
