<?php

/**
 * Migracion de base de datos 2026_06_17_000013_create_investment_projects_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
        Schema::create('investment_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_entity_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institutional_objective_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('intervention_type')->nullable();
            $table->decimal('budget', 16, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('draft')->index();
            $table->text('description')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_projects');
    }
};
