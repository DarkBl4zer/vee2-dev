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
        Schema::create('vee2_plan_t_accion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plantrabajo');
            $table->unsignedBigInteger('id_accion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vee2_plan_t_accion');
    }
};
