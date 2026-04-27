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
        Schema::table('abordajes', function (Blueprint $table) {
            $table->string('responsable')->nullable()->after('observacion');
            $table->integer('total_a')->default(0)->after('cantidad');
            $table->integer('total_b')->default(0)->after('total_a');
            $table->integer('total_a_plus')->default(0)->after('total_b');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abordajes', function (Blueprint $table) {
            $table->dropColumn(['responsable', 'total_a', 'total_b', 'total_a_plus']);
        });
    }
};
