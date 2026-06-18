<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chauffeur_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('zone')->nullable();             // zone desservie (ex : Dakar, Plateau)
            $table->string('vehicule')->nullable();         // ex : Toyota Corolla blanche
            $table->string('tarif_indicatif')->nullable();  // ex : 2 000 FCFA / course
            $table->boolean('disponible')->default(true);   // disponibilité affichée aux visiteurs
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chauffeur_profiles');
    }
};
