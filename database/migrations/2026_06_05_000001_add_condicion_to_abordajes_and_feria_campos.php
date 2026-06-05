<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Abordaje: agregar 9 campos de condición ───────────────────────────
        Schema::table('abordajes', function (Blueprint $table) {
            $table->integer('embarazada')->nullable()->after('total_a_plus');
            $table->integer('mujer_lactante')->nullable()->after('embarazada');
            $table->integer('menor_72_meses')->nullable()->after('mujer_lactante');
            $table->integer('escolar')->nullable()->after('menor_72_meses');
            $table->integer('adolescente')->nullable()->after('escolar');
            $table->integer('adulto')->nullable()->after('adolescente');
            $table->integer('adulto_mayor')->nullable()->after('adulto');
            $table->integer('encamado')->nullable()->after('adulto_mayor');
            $table->integer('discapacidad')->nullable()->after('encamado');
        });

        // ── Feria de Campo: agregar 9 campos de condición ─────────────────────
        Schema::table('feria_campos', function (Blueprint $table) {
            $table->integer('embarazada')->nullable()->after('tipo_a_plus');
            $table->integer('mujer_lactante')->nullable()->after('embarazada');
            $table->integer('menor_72_meses')->nullable()->after('mujer_lactante');
            $table->integer('escolar')->nullable()->after('menor_72_meses');
            $table->integer('adolescente')->nullable()->after('escolar');
            $table->integer('adulto')->nullable()->after('adolescente');
            $table->integer('adulto_mayor')->nullable()->after('adulto');
            $table->integer('encamado')->nullable()->after('adulto_mayor');
            $table->integer('discapacidad')->nullable()->after('encamado');
        });
    }

    public function down(): void
    {
        Schema::table('abordajes', function (Blueprint $table) {
            $table->dropColumn([
                'embarazada', 'mujer_lactante', 'menor_72_meses', 'escolar',
                'adolescente', 'adulto', 'adulto_mayor', 'encamado', 'discapacidad',
            ]);
        });

        Schema::table('feria_campos', function (Blueprint $table) {
            $table->dropColumn([
                'embarazada', 'mujer_lactante', 'menor_72_meses', 'escolar',
                'adolescente', 'adulto', 'adulto_mayor', 'encamado', 'discapacidad',
            ]);
        });
    }
};
