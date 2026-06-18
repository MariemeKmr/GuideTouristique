<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('visiteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('chauffeur_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
