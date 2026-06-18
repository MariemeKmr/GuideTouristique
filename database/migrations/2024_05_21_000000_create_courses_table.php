<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('visiteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('chauffeur_id')->constrained('users')->cascadeOnDelete();
            $table->string('depart')->nullable();
            $table->string('destination')->nullable();
            $table->string('statut')->default('demandee');
            $table->unsignedTinyInteger('note')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
