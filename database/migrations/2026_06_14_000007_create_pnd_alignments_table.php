<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pnd_alignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutional_objective_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pnd_objective_id')->constrained()->cascadeOnDelete();
            $table->string('contribution_level')->default('medium');
            $table->text('justification')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->unique(['institutional_objective_id', 'pnd_objective_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pnd_alignments');
    }
};
