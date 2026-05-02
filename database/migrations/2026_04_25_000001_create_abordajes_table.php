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
            $table->string('responsable')->nullable();
            $table->date('fecha');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->integer('cantidad')->default(0);
            $table->integer('total_a')->default(0);
            $table->integer('total_b')->default(0);
            $table->integer('total_a_plus')->default(0);

            // Índices para optimización
            $table->index(['sector_id', 'fecha']);
            $table->index('fecha');
            $table->index('responsable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abordajes');
    }
};
