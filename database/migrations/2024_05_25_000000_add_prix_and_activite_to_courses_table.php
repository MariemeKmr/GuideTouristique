<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedInteger('prix')->nullable()->after('destination');          // montant en FCFA
            $table->foreignUuid('activite_id')->nullable()->after('chauffeur_id')->constrained('activites')->nullOnDelete();
            $table->date('date_prevue')->nullable()->after('activite_id');               // pour les courses liees a une activite
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activite_id');
            $table->dropColumn(['prix', 'date_prevue']);
        });
    }
};
