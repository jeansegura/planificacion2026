{{-- Vista Blade de objetivos institucionales; lista registros, filtros y acciones principales. --}}
@php($canManageObjectives = Auth::user()?->hasPermission('objectives.manage') ?? false)
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Objetivos institucionales</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Buscar objetivo" />
                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><option value="">Todos los estados</option>@foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select>
                <x-primary-button>Filtrar</x-primary-button>
                @if ($canManageObjectives)<a href="{{ route('institutional-objectives.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Nuevo objetivo</a>@endif
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Objetivo</th><th class="px-4 py-3 text-left">Plan</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-right">Acciones</th></tr></thead>
            <tbody class="divide-y divide-gray-100">@forelse ($objectives as $objective)<tr>
                <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $objective->code }} - {{ $objective->name }}</div><div class="text-gray-500">{{ $objective->institution }}</div></td>
                <td class="px-4 py-3">{{ $objective->strategicPlan?->name ?: 'Sin plan asociado' }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $statuses[$objective->status] ?? $objective->status }}</span></td>
                <td class="px-4 py-3 text-right space-x-2"><a class="text-indigo-600" href="{{ route('institutional-objectives.show', $objective) }}">Ver</a>@if ($canManageObjectives)<a class="text-indigo-600" href="{{ route('institutional-objectives.edit', $objective) }}">Editar</a>@endif</td>
            </tr>@empty<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No existen objetivos registrados.</td></tr>@endforelse</tbody>
        </table></div><div class="p-4">{{ $objectives->links() }}</div></div>
    </div></div>
</x-app-layout>
