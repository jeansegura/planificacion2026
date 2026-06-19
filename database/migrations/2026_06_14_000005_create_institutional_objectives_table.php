<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutional_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategic_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('institution')->index();
            $table->text('description')->nullable();
            $table->text('baseline')->nullable();
            $table->text('expected_result')->nullable();
            $table->string('status')->default('draft')->index();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutional_objectives');
    }
};
