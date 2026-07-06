{{-- Vista Blade de estructura visual base; renderiza una parte de la interfaz. --}}
@php
    $user = Auth::user();
    $can = fn (string $permission): bool => $user?->hasPermission($permission) ?? false;

    $navigationItems = collect([
        [
            'label' => 'Dashboard',
            'description' => 'Resumen general del sistema',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'visible' => true,
        ],
        [
            'label' => 'Usuarios',
            'description' => 'Usuarios y accesos institucionales',
            'href' => route('users.index'),
            'active' => request()->routeIs('users.*'),
            'visible' => $can('users.view'),
        ],
        [
            'label' => 'Roles',
            'description' => 'Roles y permisos del sistema',
            'href' => route('roles.index'),
            'active' => request()->routeIs('roles.*'),
            'visible' => $can('roles.view'),
        ],
        [
            'label' => 'Planes',
            'description' => 'Planes estrategicos institucionales',
            'href' => route('strategic-plans.index'),
            'active' => request()->routeIs('strategic-plans.*'),
            'visible' => $can('plans.view'),
        ],
        [
            'label' => 'Objetivos',
            'description' => 'Objetivos institucionales',
            'href' => route('institutional-objectives.index'),
            'active' => request()->routeIs('institutional-objectives.*'),
            'visible' => $can('objectives.view'),
        ],
        [
            'label' => 'PND',
            'description' => 'Alineacion al Plan Nacional de Desarrollo',
            'href' => route('pnd-alignments.index'),
            'active' => request()->routeIs('pnd-*'),
            'visible' => $can('pnd.view'),
        ],
        [
            'label' => 'ODS',
            'description' => 'Agenda 2030 y objetivos sostenibles',
            'href' => route('ods-alignments.index'),
            'active' => request()->routeIs('ods-*') || request()->routeIs('sdgs.*'),
            'visible' => $can('ods.view'),
        ],
        [
            'label' => 'Metas',
            'description' => 'Metas e indicadores institucionales',
            'href' => route('goals.index'),
            'active' => request()->routeIs('goals.*') || request()->routeIs('indicators.*'),
            'visible' => $can('goals.view'),
        ],
        [
            'label' => 'Proyectos',
            'description' => 'Proyectos de inversion publica',
            'href' => route('investment-projects.index'),
            'active' => request()->routeIs('investment-projects.*'),
            'visible' => $can('projects.view'),
        ],
        [
            'label' => 'Entidades',
            'description' => 'Instituciones y entidades publicas',
            'href' => route('public-entities.index'),
            'active' => request()->routeIs('public-entities.*'),
            'visible' => $can('entities.view'),
        ],
        [
            'label' => 'Reportes',
            'description' => 'Informes y datos consolidados',
            'href' => route('reports.index'),
            'active' => request()->routeIs('reports.*'),
            'visible' => $can('reports.view'),
        ],
        [
            'label' => 'Auditoria',
            'description' => 'Trazabilidad de operaciones',
            'href' => route('audit-logs.index'),
            'active' => request()->routeIs('audit-logs.*'),
            'visible' => $can('audit.view'),
        ],
    ])->where('visible')->values();

    $searchItems = $navigationItems
        ->map(fn (array $item): array => [
            'label' => $item['label'],
            'description' => $item['description'],
            'href' => $item['href'],
        ])
        ->values();

    $initials = collect(preg_split('/\s+/', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

<nav
    x-data="{
        mobileOpen: false,
        searchOpen: false,
        query: '',
        modules: @js($searchItems),
        get results() {
            const value = this.query.trim().toLowerCase();
            if (!value) return this.modules.slice(0, 6);
            return this.modules.filter((item) =>
                `${item.label} ${item.description}`.toLowerCase().includes(value)
            );
        }
    }"
    @keydown.escape.window="searchOpen = false; query = ''"
    @keydown.window="
        if ($event.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
            $event.preventDefault();
            $refs.searchInput?.focus();
            searchOpen = true;
        }
    "
    class="relative z-40 border-b border-slate-200 bg-white shadow-[0_8px_30px_rgba(15,35,75,0.06)]"
>
    <div class="h-1 bg-[linear-gradient(90deg,#fcd116_0_50%,#003893_50%_75%,#ce1126_75%)]"></div>

    <div class="mx-auto max-w-[1480px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-[76px] items-center gap-4">
            <a href="{{ route('dashboard') }}" class="group flex shrink-0 items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-[#003893] text-sm font-black text-white shadow-lg shadow-blue-900/20 transition group-hover:-translate-y-0.5">
                    SP
                </span>
                <span class="hidden leading-tight lg:block">
                    <strong class="block text-base font-extrabold tracking-wide text-slate-900">SIPeIP</strong>
                    <small class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Ecuador</small>
                </span>
            </a>

            <div class="relative mx-auto hidden w-full max-w-xl md:block" @click.outside="searchOpen = false">
                <label for="module-search" class="sr-only">Buscar modulo</label>
                <span class="pointer-events-none absolute inset-y-0 left-0 grid w-12 place-items-center text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                </span>
                <input
                    id="module-search"
                    x-ref="searchInput"
                    x-model="query"
                    @focus="searchOpen = true"
                    type="search"
                    placeholder="Buscar modulos, proyectos, usuarios..."
                    autocomplete="off"
                    class="h-11 w-full rounded-lg border-slate-200 bg-slate-50 py-2 pl-12 pr-16 text-sm text-slate-800 shadow-inner outline-none transition placeholder:text-slate-400 focus:border-[#003893] focus:bg-white focus:ring-4 focus:ring-blue-100"
                >
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <kbd class="rounded border border-slate-200 bg-white px-2 py-0.5 text-[11px] font-bold text-slate-400">/</kbd>
                </span>

                <div
                    x-cloak
                    x-show="searchOpen"
                    x-transition.origin.top
                    class="absolute left-0 right-0 top-[52px] overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl shadow-slate-900/15"
                >
                    <div class="border-b border-slate-100 px-4 py-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Accesos disponibles
                    </div>
                    <div class="max-h-80 overflow-y-auto p-2">
                        <template x-for="(item, index) in results" :key="item.href">
                            <a
                                :href="item.href"
                                class="flex items-center justify-between gap-4 rounded-md px-3 py-3 transition hover:bg-blue-50 focus:bg-blue-50 focus:outline-none"
                            >
                                <span>
                                    <strong class="block text-sm text-slate-800" x-text="item.label"></strong>
                                    <small class="text-xs text-slate-500" x-text="item.description"></small>
                                </span>
                                <span class="text-lg text-[#003893]">&rsaquo;</span>
                            </a>
                        </template>
                        <p x-show="results.length === 0" class="px-3 py-8 text-center text-sm text-slate-500">
                            No se encontraron modulos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="ml-auto hidden shrink-0 sm:block">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex max-w-[270px] items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-left transition hover:border-blue-200 hover:bg-blue-50/50 focus:outline-none focus:ring-4 focus:ring-blue-100">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-[#fcd116] text-xs font-black text-[#173466]">
                                {{ $initials ?: 'U' }}
                            </span>
                            <span class="min-w-0">
                                <strong class="block truncate text-sm font-bold text-slate-800">{{ $user->name }}</strong>
                                <small class="block truncate text-xs text-slate-500">{{ $user->role?->name ?: 'Sin rol asignado' }}</small>
                            </span>
                            <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <div class="truncate text-sm font-bold text-slate-800">{{ $user->name }}</div>
                            <div class="truncate text-xs text-slate-500">{{ $user->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Mi perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button
                @click="mobileOpen = !mobileOpen"
                class="ml-auto grid h-11 w-11 place-items-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 sm:hidden"
                aria-label="Abrir menu"
            >
                <svg x-show="!mobileOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-cloak x-show="mobileOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 6 12 12M18 6 6 18"></path>
                </svg>
            </button>
        </div>

        <div class="hidden border-t border-slate-100 sm:block">
            <div class="flex gap-1 overflow-x-auto py-2 [scrollbar-width:thin]">
                @foreach ($navigationItems as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'relative shrink-0 rounded-md px-3.5 py-2 text-sm font-semibold transition',
                            'bg-[#003893] text-white shadow-md shadow-blue-900/15' => $item['active'],
                            'text-slate-600 hover:bg-slate-100 hover:text-[#003893]' => ! $item['active'],
                        ])
                    >
                        {{ $item['label'] }}
                        @if ($item['active'])
                            <span class="absolute -bottom-2 left-1/2 h-1 w-6 -translate-x-1/2 rounded-t bg-[#fcd116]"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div
        x-cloak
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="border-t border-slate-200 bg-white sm:hidden"
    >
        <div class="p-4">
            <div class="mb-4 rounded-lg bg-slate-50 p-3">
                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                <div class="text-xs text-slate-500">{{ $user->role?->name ?: 'Sin rol asignado' }}</div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                @foreach ($navigationItems as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'rounded-md border px-3 py-3 text-sm font-semibold transition',
                            'border-[#003893] bg-[#003893] text-white' => $item['active'],
                            'border-slate-200 text-slate-700 hover:border-blue-200 hover:bg-blue-50' => ! $item['active'],
                        ])
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="mt-4 flex gap-2 border-t border-slate-200 pt-4">
                <a href="{{ route('profile.edit') }}" class="flex-1 rounded-md border border-slate-200 px-3 py-2 text-center text-sm font-semibold text-slate-700">
                    Mi perfil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button class="w-full rounded-md bg-[#ce1126] px-3 py-2 text-sm font-semibold text-white">
                        Cerrar sesion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
