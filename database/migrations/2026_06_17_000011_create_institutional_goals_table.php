<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutional_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutional_objective_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('period_year');
            $table->decimal('target_value', 14, 2)->default(0);
            $table->string('unit')->default('unidad');
            $table->string('responsible')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutional_goals');
    }
};
