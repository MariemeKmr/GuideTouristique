<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->string('categorie'); // nautique, motorisee, animaliere, nature, culturelle
            $table->string('lieu')->nullable();
            $table->string('tarif')->nullable();
            $table->text('description')->nullable();
            $table->foreignUuid('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};
