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
        Schema::create('feria_campos', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->string('responsable');
            $table->date('fecha');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');

            // Nuevos campos solicitados
            $table->boolean('venta_lina_nutrivida')->default(false);
            $table->boolean('antrometria')->default(false);
            $table->integer('tipo_a')->nullable();
            $table->integer('tipo_b')->nullable();
            $table->integer('tipo_a_plus')->nullable();
            $table->boolean('campana4s')->default(false);
            $table->string('tema_tratado')->nullable();

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
        Schema::dropIfExists('feria_campos');
    }
};
