<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activite_reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('visiteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('activite_id')->constrained('activites')->cascadeOnDelete();
            $table->date('date_activite');
            $table->foreignUuid('chauffeur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activite_reservations');
    }
};
