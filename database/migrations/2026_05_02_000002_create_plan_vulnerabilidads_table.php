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
        Schema::create('plan_vulnerabilidads', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->string('responsable');
            $table->date('fecha');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->integer('total_entregas');
            $table->json('tipo');
            // Índices para optimización
            $table->index(['sector_id', 'fecha']);
            $table->index('fecha');
            $table->index('responsable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_vulnerabilidads');
    }
};
