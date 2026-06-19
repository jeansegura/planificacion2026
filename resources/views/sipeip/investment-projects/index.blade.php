@php($canManageProjects = Auth::user()?->hasPermission('projects.manage') ?? false)
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Gestion de inversion publica</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proyectos de inversion</h2>
            </div>
            @if ($canManageProjects)<a href="{{ route('investment-projects.create') }}" class="inline-flex items-center justify-center rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-blue-950 shadow-sm hover:bg-yellow-300">Crear proyecto</a>@endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white p-4 shadow-sm sm:rounded-lg">
                <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <x-text-input name="q" value="{{ request('q') }}" placeholder="Codigo o nombre del proyecto" />
                    <select name="status" class="rounded-md border-gray-300 shadow-sm">
                        <option value="">Todos los estados</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-primary-button>Filtrar</x-primary-button>
                    <a href="{{ route('investment-projects.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Limpiar</a>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">Proyecto</th>
                                <th class="px-4 py-3 text-left">Entidad</th>
                                <th class="px-4 py-3 text-left">Presupuesto</th>
                                <th class="px-4 py-3 text-left">Expediente</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($projects as $project)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $project->code }} - {{ $project->name }}</div>
                                        <div class="text-gray-500">{{ $project->intervention_type ?: 'Sin tipologia definida' }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $project->publicEntity?->name ?: 'Sin entidad' }}</td>
                                    <td class="px-4 py-3">$ {{ number_format((float) $project->budget, 2) }}</td>
                                    <td class="px-4 py-3">{{ $project->documents_count }} documento(s)</td>
                                    <td class="px-4 py-3">{{ $statuses[$project->status] ?? $project->status }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a class="font-medium text-blue-700" href="{{ route('investment-projects.show', $project) }}">Ver/Subir</a>
                                            @if ($canManageProjects)<a class="font-medium text-indigo-600" href="{{ route('investment-projects.edit', $project) }}">Editar</a>@endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No existen proyectos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
