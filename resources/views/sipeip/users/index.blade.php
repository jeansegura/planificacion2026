@php($canManageUsers = Auth::user()?->hasPermission('users.manage') ?? false)
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">RBAC institucional</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios institucionales</h2>
            </div>
            @if ($canManageUsers)<a href="{{ route('users.create') }}" class="inline-flex items-center justify-center rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-blue-950 shadow-sm hover:bg-yellow-300">Registrar usuario</a>@endif
        </div>
    </x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status')) <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div> @endif
        @if (session('generated_credentials'))
            @php($credentials = session('generated_credentials'))
            <div class="rounded-md border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-950">
                <div class="font-semibold">Credencial generada para {{ $credentials['name'] }}</div>
                <div class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-3">
                    <div><span class="font-semibold">Usuario:</span> {{ $credentials['email'] }}</div>
                    <div><span class="font-semibold">Contrasena temporal:</span> <code class="rounded bg-white px-2 py-1">{{ $credentials['password'] }}</code></div>
                    <div><span class="font-semibold">Rol:</span> {{ $credentials['role'] ?: 'Sin rol' }}</div>
                </div>
                <p class="mt-2 text-xs">Muestra esta clave una sola vez. Entregala por canal seguro y pide cambio al primer ingreso institucional.</p>
            </div>
        @endif
        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <x-text-input name="q" value="{{ request('q') }}" placeholder="Buscar usuario" />
                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos los estados</option>
                    <option value="active" @selected(request('status') === 'active')>Activo</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactivo</option>
                </select>
                <select name="role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos los roles</option>
                    @foreach ($roles as $role)<option value="{{ $role->id }}" @selected((string) request('role_id') === (string) $role->id)>{{ $role->name }}</option>@endforeach
                </select>
                <select name="user_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos los tipos</option>
                    @foreach ($userTypes as $key => $label)<option value="{{ $key }}" @selected(request('user_type') === $key)>{{ $label }}</option>@endforeach
                </select>
                <select name="public_entity_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas las instituciones</option>
                    @foreach ($publicEntities as $entity)<option value="{{ $entity->id }}" @selected((string) request('public_entity_id') === (string) $entity->id)>{{ $entity->code }}</option>@endforeach
                </select>
                <div class="flex gap-2"><x-primary-button>Filtrar</x-primary-button><a href="{{ route('users.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Limpiar</a></div>
            </form>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Usuario</th><th class="px-4 py-3 text-left">Perfil institucional</th><th class="px-4 py-3 text-left">Institucion / unidad</th><th class="px-4 py-3 text-left">Rol</th><th class="px-4 py-3 text-left">Acceso</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-right">Acciones</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $user->name }}</div><div class="text-gray-500">{{ $user->email }}</div><div class="text-gray-400">{{ $user->identification }}</div></td>
                            <td class="px-4 py-3 text-gray-700">{{ $userTypes[$user->user_type] ?? $user->user_type }}<div class="text-gray-400">{{ $user->position }}</div></td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->publicEntity?->name ?: ($user->institution ?: 'Sin institucion') }}<div class="text-gray-400">{{ $user->organizational_unit }}</div></td>
                            <td class="px-4 py-3">{{ $user->role?->name ?: 'Sin rol' }}</td>
                            <td class="px-4 py-3">{{ $user->auth_provider === 'identity_server' ? 'SSO' : 'Local' }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $user->status === 'active' ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="px-4 py-3 text-right space-x-2">@if ($canManageUsers)<a class="text-indigo-600 hover:text-indigo-900" href="{{ route('users.edit', $user) }}">Editar</a><form class="inline" method="POST" action="{{ $user->status === 'active' ? route('users.deactivate', $user) : route('users.activate', $user) }}">@csrf @method('PATCH')<button class="text-gray-600 hover:text-gray-900">{{ $user->status === 'active' ? 'Desactivar' : 'Activar' }}</button></form>@else<span class="text-gray-400">Solo lectura</span>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No existen usuarios con esos criterios.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <div class="p-4">{{ $users->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
