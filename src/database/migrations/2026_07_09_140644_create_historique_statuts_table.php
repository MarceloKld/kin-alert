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
        Schema::create('historique_statuts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('incident_id')
          ->constrained('incidents')
          ->cascadeOnDelete();
    $table->string('ancien_statut')->nullable();
    $table->string('nouveau_statut');
    $table->foreignId('modifie_par')
          ->nullable()
          ->constrained('utilisateurs')
          ->nullOnDelete();
    $table->timestamp('date_modification')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_statuts');
    }
};
