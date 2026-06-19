@csrf
<div class="space-y-4">
    <div><x-input-label for="name" value="Nombre del rol" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $role->name) }}" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
    <div><x-input-label for="description" value="Descripcion" /><textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $role->description) }}</textarea><x-input-error :messages="$errors->get('description')" class="mt-2" /></div>
    <div><x-input-label for="status" value="Estado" /><select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><option value="active" @selected(old('status', $role->status) === 'active')>Activo</option><option value="inactive" @selected(old('status', $role->status) === 'inactive')>Inactivo</option></select></div>
    <div>
        <x-input-label value="Permisos por modulo" />
        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach ($permissions as $key => $label)
                <label class="flex gap-2 rounded border border-gray-200 p-3 text-sm"><input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key, old('permissions', $role->permissions ?? []), true)) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"><span>{{ $label }}</span></label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
    </div>
</div>
<div class="mt-6 flex items-center gap-3"><x-primary-button>Guardar</x-primary-button><a href="{{ route('roles.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a></div>
