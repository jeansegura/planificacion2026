<?php

namespace Database\Seeders;

use App\Models\PublicEntity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SipeipAccessSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.manage',
            'roles.view',
            'roles.manage',
            'plans.view',
            'plans.manage',
            'plans.validate',
            'objectives.view',
            'objectives.manage',
            'objectives.validate',
            'pnd.view',
            'pnd.manage',
            'ods.view',
            'ods.manage',
            'goals.view',
            'goals.manage',
            'projects.view',
            'projects.manage',
            'entities.view',
            'entities.manage',
            'reports.view',
            'audit.view',
        ];

        $roles = [
            'Administrador del Sistema' => [
                'description' => 'Administra usuarios, roles, parametros institucionales y todos los modulos SIPeIP.',
                'permissions' => $permissions,
            ],
            'Tecnico de Planificacion SNP' => [
                'description' => 'Registra planes, objetivos, alineaciones, metas e indicadores de planificacion.',
                'permissions' => [
                    'plans.view',
                    'plans.manage',
                    'objectives.view',
                    'objectives.manage',
                    'pnd.view',
                    'pnd.manage',
                    'ods.view',
                    'ods.manage',
                    'goals.view',
                    'goals.manage',
                    'projects.view',
                    'reports.view',
                ],
            ],
            'Revisor SNP' => [
                'description' => 'Revisa y valida informacion estrategica e informes tecnicos.',
                'permissions' => [
                    'plans.view',
                    'plans.validate',
                    'objectives.view',
                    'objectives.validate',
                    'pnd.view',
                    'ods.view',
                    'goals.view',
                    'projects.view',
                    'reports.view',
                    'audit.view',
                ],
            ],
            'Analista de Inversion Publica' => [
                'description' => 'Gestiona proyectos de inversion, tipologias y expediente documental.',
                'permissions' => [
                    'plans.view',
                    'objectives.view',
                    'goals.view',
                    'projects.view',
                    'projects.manage',
                    'entities.view',
                    'reports.view',
                ],
            ],
            'Usuario Externo Entidad Publica' => [
                'description' => 'Registra informacion de su entidad y consulta el avance de su planificacion.',
                'permissions' => [
                    'plans.view',
                    'plans.manage',
                    'objectives.view',
                    'objectives.manage',
                    'goals.view',
                    'goals.manage',
                    'projects.view',
                    'projects.manage',
                    'reports.view',
                ],
            ],
            'Auditor' => [
                'description' => 'Consulta trazabilidad, reportes y evidencias sin modificar informacion sustantiva.',
                'permissions' => [
                    'users.view',
                    'roles.view',
                    'plans.view',
                    'objectives.view',
                    'pnd.view',
                    'ods.view',
                    'goals.view',
                    'projects.view',
                    'entities.view',
                    'reports.view',
                    'audit.view',
                ],
            ],
        ];

        foreach ($roles as $name => $data) {
            Role::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $data['description'],
                    'permissions' => $data['permissions'],
                    'status' => 'active',
                ]
            );
        }

        PublicEntity::updateOrCreate(
            ['code' => 'SNP'],
            [
                'name' => 'Secretaria Nacional de Planificacion',
                'acronym' => 'SNP',
                'government_level' => 'Nacional',
                'macro_sector' => 'Planificacion',
                'sector' => 'Administracion publica',
                'subsector' => 'Planificacion nacional',
                'status' => 'active',
            ]
        );

        PublicEntity::updateOrCreate(
            ['code' => 'GAD-MUN'],
            [
                'name' => 'Gobierno Autonomo Descentralizado Municipal',
                'acronym' => 'GAD Municipal',
                'government_level' => 'Local',
                'macro_sector' => 'Gobierno local',
                'sector' => 'Administracion territorial',
                'subsector' => 'Municipal',
                'status' => 'active',
            ]
        );

        $adminRole = Role::where('name', 'Administrador del Sistema')->first();
        User::query()
            ->whereNull('role_id')
            ->orderBy('id')
            ->limit(1)
            ->update(['role_id' => $adminRole?->id]);
    }
}
