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
        Schema::create('vee2_permisos', function (Blueprint $table) {
            $table->id();
            $table->string('url', 250);
            $table->string('accion', 150);
            $table->unsignedBigInteger('id_rol')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('estados', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_permisos');
    }
};
