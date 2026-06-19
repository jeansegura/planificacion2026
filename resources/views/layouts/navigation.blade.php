@php
    $user = Auth::user();
    $can = fn (string $permission): bool => $user?->hasPermission($permission) ?? false;
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

                    @if ($can('users.view'))
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Usuarios</x-nav-link>
                    @endif

                    @if ($can('roles.view'))
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">Roles</x-nav-link>
                    @endif

                    @if ($can('plans.view'))
                        <x-nav-link :href="route('strategic-plans.index')" :active="request()->routeIs('strategic-plans.*')">Planes</x-nav-link>
                    @endif

                    @if ($can('objectives.view'))
                        <x-nav-link :href="route('institutional-objectives.index')" :active="request()->routeIs('institutional-objectives.*')">Objetivos</x-nav-link>
                    @endif

                    @if ($can('pnd.view'))
                        <x-nav-link :href="route('pnd-alignments.index')" :active="request()->routeIs('pnd-*')">PND</x-nav-link>
                    @endif

                    @if ($can('ods.view'))
                        <x-nav-link :href="route('ods-alignments.index')" :active="request()->routeIs('ods-*') || request()->routeIs('sdgs.*')">ODS</x-nav-link>
                    @endif

                    @if ($can('goals.view'))
                        <x-nav-link :href="route('goals.index')" :active="request()->routeIs('goals.*') || request()->routeIs('indicators.*')">Metas</x-nav-link>
                    @endif

                    @if ($can('projects.view'))
                        <x-nav-link :href="route('investment-projects.index')" :active="request()->routeIs('investment-projects.*')">Proyectos</x-nav-link>
                    @endif

                    @if ($can('entities.view'))
                        <x-nav-link :href="route('public-entities.index')" :active="request()->routeIs('public-entities.*')">Entidades</x-nav-link>
                    @endif

                    @if ($can('reports.view'))
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Reportes</x-nav-link>
                    @endif

                    @if ($can('audit.view'))
                        <x-nav-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')">Auditoria</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                {{ $user->name }}
                                <span class="block text-xs text-gray-400">{{ $user->role?->name ?: 'Sin rol asignado' }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesion</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>

            @if ($can('users.view'))
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Usuarios</x-responsive-nav-link>
            @endif

            @if ($can('roles.view'))
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">Roles</x-responsive-nav-link>
            @endif

            @if ($can('plans.view'))
                <x-responsive-nav-link :href="route('strategic-plans.index')" :active="request()->routeIs('strategic-plans.*')">Planes</x-responsive-nav-link>
            @endif

            @if ($can('objectives.view'))
                <x-responsive-nav-link :href="route('institutional-objectives.index')" :active="request()->routeIs('institutional-objectives.*')">Objetivos</x-responsive-nav-link>
            @endif

            @if ($can('pnd.view'))
                <x-responsive-nav-link :href="route('pnd-alignments.index')" :active="request()->routeIs('pnd-*')">PND</x-responsive-nav-link>
            @endif

            @if ($can('ods.view'))
                <x-responsive-nav-link :href="route('ods-alignments.index')" :active="request()->routeIs('ods-*') || request()->routeIs('sdgs.*')">ODS</x-responsive-nav-link>
            @endif

            @if ($can('goals.view'))
                <x-responsive-nav-link :href="route('goals.index')" :active="request()->routeIs('goals.*') || request()->routeIs('indicators.*')">Metas</x-responsive-nav-link>
            @endif

            @if ($can('projects.view'))
                <x-responsive-nav-link :href="route('investment-projects.index')" :active="request()->routeIs('investment-projects.*')">Proyectos</x-responsive-nav-link>
            @endif

            @if ($can('entities.view'))
                <x-responsive-nav-link :href="route('public-entities.index')" :active="request()->routeIs('public-entities.*')">Entidades</x-responsive-nav-link>
            @endif

            @if ($can('reports.view'))
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Reportes</x-responsive-nav-link>
            @endif

            @if ($can('audit.view'))
                <x-responsive-nav-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')">Auditoria</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                <div class="text-xs text-gray-400">{{ $user->role?->name ?: 'Sin rol asignado' }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesion</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
