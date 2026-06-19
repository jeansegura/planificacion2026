<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar ODS</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('sdgs.update', $sdg) }}" class="space-y-4">
            @csrf @method('PUT')
            <div><x-input-label for="number" value="Numero" /><x-text-input id="number" type="number" name="number" class="mt-1 block w-full" value="{{ old('number', $sdg->number) }}" required /><x-input-error :messages="$errors->get('number')" class="mt-2" /></div>
            <div><x-input-label for="name" value="Nombre" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $sdg->name) }}" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
            <div><x-input-label for="description" value="Descripcion" /><textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $sdg->description) }}</textarea></div>
            <div><x-input-label for="status" value="Estado" /><select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><option value="active" @selected(old('status', $sdg->status) === 'active')>Activo</option><option value="inactive" @selected(old('status', $sdg->status) === 'inactive')>Inactivo</option></select></div>
            <div class="flex items-center gap-3"><x-primary-button>Guardar</x-primary-button><a href="{{ route('sdgs.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a></div>
        </form>
    </div></div></div>
</x-app-layout>
