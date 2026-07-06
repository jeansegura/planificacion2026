{{-- Vista Blade de entidades publicas; centraliza campos compartidos por crear y editar. --}}
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div><x-input-label for="code" value="Codigo" /><x-text-input id="code" name="code" class="mt-1 block w-full" value="{{ old('code', $entity->code) }}" required /><x-input-error :messages="$errors->get('code')" class="mt-2" /></div>
    <div><x-input-label for="name" value="Nombre" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $entity->name) }}" required /></div>
    <div><x-input-label for="acronym" value="Siglas" /><x-text-input id="acronym" name="acronym" class="mt-1 block w-full" value="{{ old('acronym', $entity->acronym) }}" /></div>
    <div><x-input-label for="government_level" value="Nivel de gobierno" /><x-text-input id="government_level" name="government_level" class="mt-1 block w-full" value="{{ old('government_level', $entity->government_level) }}" required /></div>
    <div><x-input-label for="macro_sector" value="Macro sector" /><x-text-input id="macro_sector" name="macro_sector" class="mt-1 block w-full" value="{{ old('macro_sector', $entity->macro_sector) }}" /></div>
    <div><x-input-label for="sector" value="Sector" /><x-text-input id="sector" name="sector" class="mt-1 block w-full" value="{{ old('sector', $entity->sector) }}" /></div>
    <div><x-input-label for="subsector" value="Subsector" /><x-text-input id="subsector" name="subsector" class="mt-1 block w-full" value="{{ old('subsector', $entity->subsector) }}" /></div>
    <div><x-input-label for="status" value="Estado" /><select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><option value="active" @selected(old('status', $entity->status) === 'active')>Activo</option><option value="inactive" @selected(old('status', $entity->status) === 'inactive')>Inactivo</option></select></div>
</div>
<div class="mt-6 flex gap-3"><x-primary-button>Guardar</x-primary-button><a href="{{ route('public-entities.index') }}" class="text-sm text-gray-600">Cancelar</a></div>
