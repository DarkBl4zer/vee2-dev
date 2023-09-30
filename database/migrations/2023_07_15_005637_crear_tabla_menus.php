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
        Schema::create('vee2_menus', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 6);
            $table->string('icono', 255)->nullable();
            $table->string('nombre', 255)->nullable();;
            $table->string('descripcion', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->unsignedInteger('orden');
            $table->unsignedInteger('usuario_crea')->default(1010);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_menus');
    }
};
