<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('annulee_par')->nullable()->after('statut');      // 'client' ou 'chauffeur'
            $table->boolean('alerte_chauffeur')->default(false)->after('annulee_par');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['annulee_par', 'alerte_chauffeur']);
        });
    }
};
