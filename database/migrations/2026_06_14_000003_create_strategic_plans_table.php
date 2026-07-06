<?php

/**
 * Migracion de base de datos 2026_06_14_000003_create_strategic_plans_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
        Schema::create('strategic_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('institution')->index();
            $table->unsignedSmallInteger('period_start');
            $table->unsignedSmallInteger('period_end');
            $table->text('description')->nullable();
            $table->json('objectives')->nullable();
            $table->json('goals')->nullable();
            $table->string('status')->default('draft')->index();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_plans');
    }
};
