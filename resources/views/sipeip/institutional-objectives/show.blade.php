@php($canManageObjectives = Auth::user()?->hasPermission('objectives.manage') ?? false; $canValidateObjectives = Auth::user()?->hasPermission('objectives.validate') ?? false)
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle del objetivo institucional</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        <div class="bg-white p-6 shadow-sm sm:rounded-lg space-y-4">
            <div class="flex justify-between gap-4"><div><p class="text-sm text-gray-500">{{ $objective->code }}</p><h3 class="text-2xl font-semibold text-gray-900">{{ $objective->name }}</h3><p class="text-gray-600">{{ $objective->institution }}</p></div>@if ($canManageObjectives)<a class="text-indigo-600" href="{{ route('institutional-objectives.edit', $objective) }}">Editar</a>@endif</div>
            <p class="text-gray-700">{{ $objective->description }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"><div><h4 class="font-semibold">PND</h4>@forelse ($objective->pndAlignments as $alignment)<p>{{ $alignment->pndObjective->code }} - {{ $alignment->pndObjective->name }} ({{ $alignment->status }})</p>@empty<p class="text-gray-500">Sin alineaciones PND.</p>@endforelse</div><div><h4 class="font-semibold">ODS</h4>@forelse ($objective->odsAlignments as $alignment)<p>ODS {{ $alignment->sdg->number }} - {{ $alignment->sdg->name }} ({{ $alignment->status }})</p>@empty<p class="text-gray-500">Sin alineaciones ODS.</p>@endforelse</div></div>
            @if ($canValidateObjectives)<div class="border-t pt-4"><form method="POST" action="{{ route('institutional-objectives.status', $objective) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">@csrf @method('PATCH')<select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">@foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected($objective->status === $key)>{{ $label }}</option>@endforeach</select><x-text-input name="observations" value="{{ $objective->observations }}" placeholder="Observaciones" /><x-primary-button>Actualizar estado</x-primary-button></form></div>@endif
        </div>
    </div></div>
</x-app-layout>
