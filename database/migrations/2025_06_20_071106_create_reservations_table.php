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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('objectif')->nullable();
            $table->enum('statut', ['en_attente', 'confirmée', 'annulée'])->default('en_attente');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('laboratoire_id')->constrained('laboratoires')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
