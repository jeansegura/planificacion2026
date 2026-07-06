{{-- Vista Blade de metas institucionales; centraliza campos compartidos por crear y editar. --}}
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div><x-input-label for="institutional_objective_id" value="Objetivo institucional" /><select id="institutional_objective_id" name="institutional_objective_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">@foreach ($objectives as $objective)<option value="{{ $objective->id }}" @selected((string) old('institutional_objective_id', $goal->institutional_objective_id) === (string) $objective->id)>{{ $objective->code }} - {{ $objective->name }}</option>@endforeach</select></div>
    <div><x-input-label for="code" value="Codigo" /><x-text-input id="code" name="code" class="mt-1 block w-full" value="{{ old('code', $goal->code) }}" required /></div>
    <div><x-input-label for="name" value="Nombre" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $goal->name) }}" required /></div>
    <div><x-input-label for="period_year" value="Anio" /><x-text-input id="period_year" type="number" name="period_year" class="mt-1 block w-full" value="{{ old('period_year', $goal->period_year) }}" required /></div>
    <div><x-input-label for="target_value" value="Valor meta" /><x-text-input id="target_value" type="number" step="0.01" name="target_value" class="mt-1 block w-full" value="{{ old('target_value', $goal->target_value) }}" required /></div>
    <div><x-input-label for="unit" value="Unidad" /><x-text-input id="unit" name="unit" class="mt-1 block w-full" value="{{ old('unit', $goal->unit) }}" required /></div>
    <div><x-input-label for="responsible" value="Responsable" /><x-text-input id="responsible" name="responsible" class="mt-1 block w-full" value="{{ old('responsible', $goal->responsible) }}" /></div>
    <div><x-input-label for="status" value="Estado" /><select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><option value="active" @selected(old('status', $goal->status) === 'active')>Activo</option><option value="inactive" @selected(old('status', $goal->status) === 'inactive')>Inactivo</option></select></div>
    <div class="md:col-span-2"><x-input-label for="description" value="Descripcion" /><textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $goal->description) }}</textarea></div>
</div>
<div class="mt-6 flex gap-3"><x-primary-button>Guardar</x-primary-button><a href="{{ route('goals.index') }}" class="text-sm text-gray-600">Cancelar</a></div>
