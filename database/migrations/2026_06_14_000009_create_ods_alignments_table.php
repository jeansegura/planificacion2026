<?php

/**
 * Migracion de base de datos 2026_06_14_000009_create_ods_alignments_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
        Schema::create('ods_alignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutional_objective_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sdg_id')->constrained('sdgs')->cascadeOnDelete();
            $table->string('target_reference')->nullable();
            $table->string('contribution_level')->default('medium');
            $table->text('justification')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->unique(['institutional_objective_id', 'sdg_id', 'target_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ods_alignments');
    }
};
