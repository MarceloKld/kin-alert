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
        Schema::create('commentaires', function (Blueprint $table) {
    $table->id();
    $table->text('contenu');
    $table->foreignId('utilisateur_id')
          ->constrained('utilisateurs')
          ->cascadeOnDelete();
    $table->foreignId('incident_id')
          ->constrained('incidents')
          ->cascadeOnDelete();
    $table->timestamp('date_creation')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
