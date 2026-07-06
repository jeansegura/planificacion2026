<?php

/**
 * Migracion de base de datos 2026_06_17_000012_create_indicators_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutional_goal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institutional_objective_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('formula')->nullable();
            $table->string('unit')->default('%');
            $table->string('periodicity')->default('Anual');
            $table->decimal('baseline_value', 14, 2)->default(0);
            $table->decimal('target_value', 14, 2)->default(0);
            $table->decimal('current_value', 14, 2)->default(0);
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
