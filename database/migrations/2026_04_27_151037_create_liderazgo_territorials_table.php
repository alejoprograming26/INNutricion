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
        Schema::create('liderazgo_territorials', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->string('responsable');
            $table->date('fecha');
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('tema_tratado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liderazgo_territorials');
    }
};
