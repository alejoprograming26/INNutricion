<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abordajes', function (Blueprint $table) {
            $table->id();
            $table->string('observacion')->nullable();
            $table->date('fecha');
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');
            $table->foreignId('comuna_id')->constrained('comunas')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->integer('cantidad')->default(0);

            // Índices de rendimiento
            $table->index('municipio_id');
            $table->index('parroquia_id');
            $table->index('comuna_id');
            $table->index('sector_id');
            $table->index('fecha');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abordajes');
    }
};
