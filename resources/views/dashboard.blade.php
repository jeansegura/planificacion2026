@php
    $user = Auth::user();
    $can = fn (string $permission): bool => $user?->hasPermission($permission) ?? false;

    $cards = [
        [
            'permission' => 'users.view',
            'route' => 'users.index',
            'label' => 'Usuarios institucionales',
            'value' => $usersCount,
            'description' => 'Alta formal, roles, institucion, unidad y estado de acceso.',
        ],
        [
            'permission' => 'roles.view',
            'route' => 'roles.index',
            'label' => 'Roles y permisos',
            'value' => $rolesCount,
            'description' => 'Matriz RBAC para pantallas y funciones por perfil.',
        ],
        [
            'permission' => 'plans.view',
            'route' => 'strategic-plans.index',
            'label' => 'Planes estrategicos',
            'value' => $plansCount,
            'description' => 'Planificacion institucional y flujo de estados.',
        ],
        [
            'permission' => 'objectives.view',
            'route' => 'institutional-objectives.index',
            'label' => 'Objetivos institucionales',
            'value' => $objectivesCount,
            'description' => 'Objetivos, validacion y observaciones tecnicas.',
        ],
        [
            'permission' => 'pnd.view',
            'route' => 'pnd-alignments.index',
            'label' => 'Alineaciones PND',
            'value' => $pndAlignmentsCount,
            'description' => 'Relacion con objetivos nacionales de desarrollo.',
        ],
        [
            'permission' => 'ods.view',
            'route' => 'ods-alignments.index',
            'label' => 'Alineaciones ODS',
            'value' => $odsAlignmentsCount,
            'description' => 'Contribucion institucional a Agenda 2030.',
        ],
        [
            'permission' => 'goals.view',
            'route' => 'goals.index',
            'label' => 'Metas / Indicadores',
            'value' => "{$goalsCount} / {$indicatorsCount}",
            'description' => 'Seguimiento de metas, indicadores y periodicidad.',
        ],
        [
            'permission' => 'projects.view',
            'route' => 'investment-projects.index',
            'label' => 'Proyectos de inversion',
            'value' => $projectsCount,
            'description' => 'Tipologias, presupuesto y expediente documental.',
        ],
        [
            'permission' => 'entities.view',
            'route' => 'public-entities.index',
            'label' => 'Entidades publicas',
            'value' => $entitiesCount,
            'description' => 'Catalogo institucional, sectores y nivel de gobierno.',
        ],
        [
            'permission' => 'reports.view',
            'route' => 'reports.index',
            'label' => 'Reportes',
            'value' => 'CSV / JSON',
            'description' => 'Exportacion de informacion tecnica del sistema.',
        ],
        [
            'permission' => 'audit.view',
            'route' => 'audit-logs.index',
            'label' => 'Auditoria',
            'value' => 'Trazas',
            'description' => 'Historial de acciones por usuario, modulo y fecha.',
        ],
    ];

    $visibleCards = array_values(array_filter($cards, fn ($card) => $can($card['permission'])));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Panel por rol</p>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">SIPeIP - {{ $user->role?->name ?: 'Usuario institucional' }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Funciones habilitadas</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Estas pantallas se muestran segun los permisos del rol asignado. Las rutas tambien estan protegidas por middleware.
                        </p>
                    </div>
                    <div class="rounded-md bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        <span class="font-semibold">Institucion:</span> {{ $user->publicEntity?->acronym ?: ($user->institution ?: 'Sin institucion') }}
                    </div>
                </div>
            </div>

            @if (count($visibleCards) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($visibleCards as $card)
                        <a href="{{ route($card['route']) }}" class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100 hover:border-blue-200 hover:shadow-md transition">
                            <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                            <p class="mt-3 text-sm text-gray-600">{{ $card['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white p-8 shadow-sm sm:rounded-lg text-center text-gray-600">
                    Tu cuenta esta activa, pero aun no tiene permisos funcionales asignados. Solicita a un administrador revisar el rol RBAC.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">Alcance visible para tu rol</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                        @foreach ($visibleCards as $card)
                            <div class="border rounded-lg p-4">
                                <p class="font-semibold">{{ $card['label'] }}</p>
                                <p class="mt-2">{{ $card['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
