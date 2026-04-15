<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_id')->constrained('metas')->onDelete('cascade');
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->index('meta_id');
            $table->index('municipio_id');
            $table->integer('meta_anual')->default(0);
            $table->integer('meta_mensual')->default(0); // meta_anual / 12
            $table->timestamps();

            // Un municipio solo puede tener un detalle por meta
            $table->unique(['meta_id', 'municipio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_metas');
    }
};
