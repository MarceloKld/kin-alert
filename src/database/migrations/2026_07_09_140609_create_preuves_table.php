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
        Schema::create('preuves', function (Blueprint $table) {
    $table->id();
    $table->foreignId('incident_id')
          ->constrained('incidents')
          ->cascadeOnDelete();
    $table->string('fichier');
    $table->string('type_fichier', 50);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preuves');
    }
};
