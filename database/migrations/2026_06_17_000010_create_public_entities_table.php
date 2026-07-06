<?php

/**
 * Migracion de base de datos 2026_06_17_000010_create_public_entities_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_entities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('acronym')->nullable();
            $table->string('government_level')->default('Nacional');
            $table->string('macro_sector')->nullable();
            $table->string('sector')->nullable();
            $table->string('subsector')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_entities');
    }
};
