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
        Schema::create('utilisateurs', function (Blueprint $table) {
    $table->id();
    $table->string('nom', 150);
    $table->string('email', 150)->unique();
    $table->string('mot_de_passe');
    $table->string('telephone', 20)->nullable();
    $table->enum('role', ['utilisateur','moderateur','administrateur'])
          ->default('utilisateur');
    $table->enum('statut', ['actif','suspendu'])
          ->default('actif');
    $table->timestamp('date_creation')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
