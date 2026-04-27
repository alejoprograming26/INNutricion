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
        Schema::table('transcripciones', function (Blueprint $table) {
            $table->index(['tipo', 'fecha'], 'idx_trans_tipo_fecha');
        });

        // Schema::table('abordajes', function (Blueprint $table) {
        //     $table->index(['municipio_id', 'fecha'], 'idx_abord_muni_fecha');
        // });

        // Índices de búsqueda para nombres geográficos
        Schema::table('municipios', function (Blueprint $table) {
            $table->index('nombre');
        });
        Schema::table('parroquias', function (Blueprint $table) {
            $table->index('nombre');
        });
        Schema::table('comunas', function (Blueprint $table) {
            $table->index('nombre');
        });
        Schema::table('sectores', function (Blueprint $table) {
            $table->index('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transcripciones', function (Blueprint $table) {
            $table->dropIndex('idx_trans_tipo_fecha');
        });

        // Schema::table('abordajes', function (Blueprint $table) {
        //     $table->dropIndex('idx_abord_muni_fecha');
        // });

        Schema::table('municipios', function (Blueprint $table) {
            $table->dropIndex(['nombre']);
        });
        Schema::table('parroquias', function (Blueprint $table) {
            $table->dropIndex(['nombre']);
        });
        Schema::table('comunas', function (Blueprint $table) {
            $table->dropIndex(['nombre']);
        });
        Schema::table('sectores', function (Blueprint $table) {
            $table->dropIndex(['nombre']);
        });
    }
};
