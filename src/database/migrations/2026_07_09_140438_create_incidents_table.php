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
        Schema::create('incidents', function (Blueprint $table) {
    $table->id();
    $table->string('titre', 150);
    $table->text('description');
    $table->foreignId('type_incident_id')
          ->constrained('types_incidents');
    $table->decimal('latitude', 10, 8);
    $table->decimal('longitude', 11, 8);
    $table->foreignId('commune_id')
          ->constrained('communes');
    $table->string('avenue', 150)->nullable();
    $table->enum('statut', ['en_attente','valide','rejete'])
          ->default('en_attente');
    $table->integer('nombre_confirmations')->default(0);
    $table->foreignId('utilisateur_id')
          ->constrained('utilisateurs');
    $table->timestamp('date_creation')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
