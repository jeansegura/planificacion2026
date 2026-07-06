<?php

/**
 * Seeder que carga datos iniciales para flujo SIPeIP, como roles, permisos, usuarios o catalogos.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace Database\Seeders;

use App\Models\PublicEntity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SipeipAccessSeeder extends Seeder
{
    public function run(): void
    {
        // Catalogo general de permisos usados por rutas, botones y menus.
        $permissions = [
            'users.view',
            'users.manage',
            'roles.view',
            'roles.manage',
            'plans.view',
            'plans.manage',
            'plans.validate',
            'plans.approve',
            'objectives.view',
            'objectives.manage',
            'objectives.validate',
            'objectives.approve',
            'pnd.view',
            'pnd.manage',
            'pnd.align.manage',
            'pnd.validate',
            'ods.view',
            'ods.manage',
            'ods.align.manage',
            'ods.validate',
            'goals.view',
            'goals.manage',
            'projects.view',
            'projects.manage',
            'projects.validate',
            'entities.view',
            'entities.manage',
            'reports.view',
            'audit.view',
            'support.view',
        ];

        // Matriz RBAC basada en los actores descritos en la problematica del caso.
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
                    'pnd.align.manage',
                    'ods.view',
                    'ods.align.manage',
                    'goals.view',
                    'goals.manage',
                    'reports.view',
                ],
            ],
            'Revisor Institucional SNP' => [
                'description' => 'Verifica alineacion normativa y devuelve o valida informacion registrada.',
                'permissions' => [
                    'plans.view',
                    'plans.validate',
                    'objectives.view',
                    'objectives.validate',
                    'pnd.view',
                    'pnd.validate',
                    'ods.view',
                    'ods.validate',
                    'goals.view',
                    'projects.view',
                    'reports.view',
                    'audit.view',
                ],
            ],
            'Revisor SNP' => [
                'description' => 'Alias operativo del revisor institucional para compatibilidad con datos previos.',
                'permissions' => [
                    'plans.view',
                    'plans.validate',
                    'objectives.view',
                    'objectives.validate',
                    'pnd.view',
                    'pnd.validate',
                    'ods.view',
                    'ods.validate',
                    'goals.view',
                    'projects.view',
                    'reports.view',
                    'audit.view',
                ],
            ],
            'Autoridad Validante' => [
                'description' => 'Aprueba oficialmente planes y objetivos institucionales y registra observaciones finales.',
                'permissions' => [
                    'plans.view',
                    'plans.approve',
                    'objectives.view',
                    'objectives.approve',
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
                    'projects.validate',
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
            'Auditor/Control Interno' => [
                'description' => 'Rol del caso de estudio para auditoria, trazabilidad e informes de control.',
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
            'Desarrollador/Soporte Tecnico' => [
                'description' => 'Atiende incidencias y documenta soporte tecnico con acceso de consulta.',
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
                    'support.view',
                ],
            ],
            'Superadministrador Sistema' => [
                'description' => 'Control total de configuracion, datos, seguridad, auditoria y soporte del sistema.',
                'permissions' => $permissions,
            ],
        ];

        // Crea o actualiza roles sin duplicarlos cuando se ejecuta nuevamente el seeder.
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

        // Entidad base de la Secretaria Nacional de Planificacion.
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

        // Entidad externa demo para probar usuarios de otras instituciones publicas.
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

        // Si existe un usuario sin rol, se lo asigna como administrador inicial.
        $adminRole = Role::where('name', 'Administrador del Sistema')->first();
        User::query()
            ->whereNull('role_id')
            ->orderBy('id')
            ->limit(1)
            ->update(['role_id' => $adminRole?->id]);
    }
}
