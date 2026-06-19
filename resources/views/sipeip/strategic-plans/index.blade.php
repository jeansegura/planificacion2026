@php($canManagePlans = Auth::user()?->hasPermission('plans.manage') ?? false)
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Planes estrategicos institucionales</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Buscar plan" />
                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos los estados</option>
                    @foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach
                </select>
                <x-primary-button>Filtrar</x-primary-button>
                @if ($canManagePlans)<a href="{{ route('strategic-plans.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Nuevo plan</a>@endif
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Plan</th><th class="px-4 py-3 text-left">Periodo</th><th class="px-4 py-3 text-left">Responsable</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-right">Acciones</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($plans as $plan)
                        <tr>
                            <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $plan->code }} - {{ $plan->name }}</div><div class="text-gray-500">{{ $plan->institution }}</div></td>
                            <td class="px-4 py-3">{{ $plan->period_start }} - {{ $plan->period_end }}</td>
                            <td class="px-4 py-3">{{ $plan->responsible?->name ?: 'Sin responsable' }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $statuses[$plan->status] ?? $plan->status }}</span></td>
                            <td class="px-4 py-3 text-right space-x-2"><a class="text-indigo-600 hover:text-indigo-900" href="{{ route('strategic-plans.show', $plan) }}">Ver</a>@if ($canManagePlans)<a class="text-indigo-600 hover:text-indigo-900" href="{{ route('strategic-plans.edit', $plan) }}">Editar</a>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No existen planes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <div class="p-4">{{ $plans->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
