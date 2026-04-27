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
        Schema::create('escuela4s', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->string('responsable');
            $table->date('fecha');
            $table->string('nombre_escuela');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->string('director_a');
            $table->string('codigo_dea');
            $table->string('codigo_cnae');
            $table->string('tema_tratado');
            $table->enum('fase', ['FASE 1', 'FASE 2', 'FASE 3']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escuela4s');
    }
};
