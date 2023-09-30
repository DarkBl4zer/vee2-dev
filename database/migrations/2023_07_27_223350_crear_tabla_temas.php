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
        Schema::create('vee2_temas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->unsignedInteger('nivel');
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('id_acta');
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_temas');
    }
};
