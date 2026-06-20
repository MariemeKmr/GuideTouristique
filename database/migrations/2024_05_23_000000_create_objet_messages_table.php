<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un fil par couple (chauffeur, client).
        Schema::create('objet_threads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('chauffeur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('visiteur_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('rendu')->default(false);
            $table->timestamps();
            $table->unique(['chauffeur_id', 'visiteur_id']);
        });

        Schema::create('objet_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('thread_id')->constrained('objet_threads')->cascadeOnDelete();
            $table->foreignUuid('expediteur_id')->constrained('users')->cascadeOnDelete();
            $table->text('contenu');
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objet_messages');
        Schema::dropIfExists('objet_threads');
    }
};
