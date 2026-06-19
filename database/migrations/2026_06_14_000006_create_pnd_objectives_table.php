<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pnd_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('axis')->index();
            $table->string('name');
            $table->text('policy')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        DB::table('pnd_objectives')->insert([
            ['code' => 'PND-SOC-01', 'axis' => 'Eje Social', 'name' => 'Mejorar condiciones de vida de la poblacion', 'policy' => 'Politica social', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PND-ECO-01', 'axis' => 'Eje Desarrollo Economico', 'name' => 'Impulsar productividad y empleo', 'policy' => 'Politica economica', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PND-INF-01', 'axis' => 'Eje Infraestructura, Energia y Medio Ambiente', 'name' => 'Fortalecer infraestructura sostenible', 'policy' => 'Politica ambiental e infraestructura', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PND-INS-01', 'axis' => 'Eje Institucional', 'name' => 'Fortalecer gestion publica transparente', 'policy' => 'Politica institucional', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pnd_objectives');
    }
};
