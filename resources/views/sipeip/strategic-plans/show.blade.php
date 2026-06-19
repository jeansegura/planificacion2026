@php($canManagePlans = Auth::user()?->hasPermission('plans.manage') ?? false; $canValidatePlans = Auth::user()?->hasPermission('plans.validate') ?? false)
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle del plan estrategico</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        <div class="bg-white p-6 shadow-sm sm:rounded-lg space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-sm text-gray-500">{{ $plan->code }}</p><h3 class="text-2xl font-semibold text-gray-900">{{ $plan->name }}</h3><p class="text-gray-600">{{ $plan->institution }} | {{ $plan->period_start }} - {{ $plan->period_end }}</p></div>
                @if ($canManagePlans)<a href="{{ route('strategic-plans.edit', $plan) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>@endif
            </div>
            <p class="text-gray-700">{{ $plan->description }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><h4 class="font-semibold text-gray-900">Objetivos</h4><ul class="mt-2 list-disc list-inside text-gray-700">@forelse ($plan->objectives ?? [] as $objective)<li>{{ $objective }}</li>@empty<li>Sin objetivos registrados.</li>@endforelse</ul></div>
                <div><h4 class="font-semibold text-gray-900">Metas</h4><ul class="mt-2 list-disc list-inside text-gray-700">@forelse ($plan->goals ?? [] as $goal)<li>{{ $goal }}</li>@empty<li>Sin metas registradas.</li>@endforelse</ul></div>
            </div>
            @if ($canValidatePlans)<div class="border-t pt-4">
                <form method="POST" action="{{ route('strategic-plans.status', $plan) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf @method('PATCH')
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">@foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected($plan->status === $key)>{{ $label }}</option>@endforeach</select>
                    <x-text-input name="observations" value="{{ $plan->observations }}" placeholder="Observaciones de revision" />
                    <x-primary-button>Actualizar estado</x-primary-button>
                </form>
            </div>@endif
        </div>
    </div></div>
</x-app-layout>
