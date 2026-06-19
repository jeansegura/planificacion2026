<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
        });

        $names = [
            1 => 'Fin de la pobreza',
            2 => 'Hambre cero',
            3 => 'Salud y bienestar',
            4 => 'Educacion de calidad',
            5 => 'Igualdad de genero',
            6 => 'Agua limpia y saneamiento',
            7 => 'Energia asequible y no contaminante',
            8 => 'Trabajo decente y crecimiento economico',
            9 => 'Industria, innovacion e infraestructura',
            10 => 'Reduccion de las desigualdades',
            11 => 'Ciudades y comunidades sostenibles',
            12 => 'Produccion y consumo responsables',
            13 => 'Accion por el clima',
            14 => 'Vida submarina',
            15 => 'Vida de ecosistemas terrestres',
            16 => 'Paz, justicia e instituciones solidas',
            17 => 'Alianzas para lograr los objetivos',
        ];

        foreach ($names as $number => $name) {
            DB::table('sdgs')->insert([
                'number' => $number,
                'name' => $name,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sdgs');
    }
};
