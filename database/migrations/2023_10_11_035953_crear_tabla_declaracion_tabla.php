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
        Schema::create('vee2_declaracion_tabla', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_declaracion');
            $table->integer('tipo');
            $table->string('nombres', 255);
            $table->string('cargo', 255);
            $table->string('area', 255);
            $table->string('tipo_relacion', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_declaracion_tabla');
    }
};
