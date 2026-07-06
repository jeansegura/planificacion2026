{{-- Vista Blade de gestion de roles y permisos; lista registros, filtros y acciones principales. --}}
@php($canManageRoles = Auth::user()?->hasPermission('roles.manage') ?? false)
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles y permisos</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Buscar rol" />
                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="active" @selected(request('status') === 'active')>Activo</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactivo</option>
                </select>
                <x-primary-button>Filtrar</x-primary-button>
                @if ($canManageRoles)<a href="{{ route('roles.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Nuevo rol</a>@endif
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Rol</th><th class="px-4 py-3 text-left">Permisos</th><th class="px-4 py-3 text-left">Usuarios</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-right">Acciones</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($roles as $role)
                        <tr>
                            <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $role->name }}</div><div class="text-gray-500">{{ $role->description }}</div></td>
                            <td class="px-4 py-3 text-gray-600">{{ count($role->permissions ?? []) }} permisos</td>
                            <td class="px-4 py-3">{{ $role->users_count }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $role->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $role->status === 'active' ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="px-4 py-3 text-right space-x-2">@if ($canManageRoles)<a class="text-indigo-600 hover:text-indigo-900" href="{{ route('roles.edit', $role) }}">Editar</a><form class="inline" method="POST" action="{{ route('roles.destroy', $role) }}">@csrf @method('DELETE')<button class="text-gray-600 hover:text-gray-900">Desactivar</button></form>@else<span class="text-gray-400">Solo lectura</span>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No existen roles registrados.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <div class="p-4">{{ $roles->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
