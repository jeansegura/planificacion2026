<?php

/**
 * Migracion de base de datos 2026_06_14_000002_add_institutional_fields_to_users_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('identification', 20)->nullable()->unique()->after('name');
            $table->string('institution')->nullable()->after('email');
            $table->string('position')->nullable()->after('institution');
            $table->string('phone', 30)->nullable()->after('position');
            $table->string('status')->default('active')->index()->after('phone');
            $table->timestamp('deactivated_at')->nullable()->after('status');
            $table->foreignId('role_id')->nullable()->after('deactivated_at')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['identification', 'institution', 'position', 'phone', 'status', 'deactivated_at']);
        });
    }
};
