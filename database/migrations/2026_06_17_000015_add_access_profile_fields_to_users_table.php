<?php

/**
 * Migracion de base de datos 2026_06_17_000015_add_access_profile_fields_to_users_table.php; crea o modifica tablas necesarias para el modulo flujo SIPeIP.
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
            $table->foreignId('public_entity_id')->nullable()->after('role_id')->constrained()->nullOnDelete();
            $table->string('user_type', 60)->default('snp_technician')->after('public_entity_id');
            $table->string('organizational_unit')->nullable()->after('user_type');
            $table->string('auth_provider', 30)->default('local')->after('organizational_unit');
            $table->string('sso_subject')->nullable()->after('auth_provider');
            $table->boolean('must_change_password')->default(true)->after('sso_subject');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('public_entity_id');
            $table->dropColumn([
                'user_type',
                'organizational_unit',
                'auth_provider',
                'sso_subject',
                'must_change_password',
            ]);
        });
    }
};
