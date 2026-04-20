<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transcripciones', function (Blueprint $table) {
            $table->id();
            $table->string('observacion')->nullable();
            $table->string('responsable');
            $table->date('fecha');
            $table->enum('tipo', [
                'VULNERABILIDAD',
                'CPLV',
                'LACTANCIA MATERNA',
                'ENCUESTA DIETARIA',
                'MONITOREO DE PRECIO',
                'SUGIMA',
                'PERINATAL',
                'PRIMER NIVEL DE ATENCION',
                'DESNUTRICION GRAVE',
                'CONSULTA',
            ]);
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->foreignId('comuna_id')->constrained('comunas')->onDelete('cascade');
            $table->index('municipio_id');
            $table->index('parroquia_id');
            $table->index('sector_id');
            $table->index('comuna_id');
            $table->index('tipo');
            $table->index('fecha');
            $table->integer('cantidad')->default(0);
            $table->integer('ingreso')->nullable();
            $table->integer('egreso')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcripciones');
    }
};
