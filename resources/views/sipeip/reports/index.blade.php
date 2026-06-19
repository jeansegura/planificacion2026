<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes y exportacion</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900">Conjuntos disponibles</h3>
            <p class="mt-1 text-sm text-gray-500">Exportacion inicial en CSV y JSON para apoyar analisis, control y rendicion de cuentas.</p>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($datasets as $key => $label)
                    <div class="border rounded-lg p-4">
                        <p class="font-semibold text-gray-900">{{ $label }}</p>
                        <div class="mt-4 flex gap-2">
                            <a class="inline-flex items-center px-3 py-2 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase" href="{{ route('reports.export', [$key, 'csv']) }}">CSV</a>
                            <a class="inline-flex items-center px-3 py-2 border rounded-md text-xs font-semibold uppercase" href="{{ route('reports.export', [$key, 'json']) }}">JSON</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div></div>
</x-app-layout>
