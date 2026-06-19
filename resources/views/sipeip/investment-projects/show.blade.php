@php($canManageProjects = Auth::user()?->hasPermission('projects.manage') ?? false)
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Expediente del proyecto</p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->code }} - {{ $project->name }}</h2>
            </div>
            <div class="flex gap-2">
                @if ($canManageProjects)<a href="{{ route('investment-projects.edit', $project) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Editar</a>@endif
                <a href="{{ route('investment-projects.index') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <section class="bg-white p-6 shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500">Entidad</p>
                            <p class="font-medium text-gray-900">{{ $project->publicEntity?->name ?: 'Sin entidad asignada' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500">Objetivo institucional</p>
                            <p class="font-medium text-gray-900">{{ $project->institutionalObjective?->code ?: 'Sin objetivo' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500">Estado</p>
                            <p class="font-medium text-gray-900">{{ $statuses[$project->status] ?? $project->status }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500">Tipologia</p>
                            <p class="font-medium text-gray-900">{{ $project->intervention_type ?: 'No definida' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500">Presupuesto</p>
                            <p class="font-medium text-gray-900">$ {{ number_format((float) $project->budget, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500">Periodo</p>
                            <p class="font-medium text-gray-900">
                                {{ optional($project->start_date)->format('d/m/Y') ?: 'Sin inicio' }} -
                                {{ optional($project->end_date)->format('d/m/Y') ?: 'Sin cierre' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <p class="text-sm font-semibold text-gray-900">Descripcion</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $project->description ?: 'Sin descripcion registrada.' }}</p>
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <p class="text-sm font-semibold text-gray-900">Observaciones</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $project->observations ?: 'Sin observaciones.' }}</p>
                    </div>
                </section>

                @if ($canManageProjects)<section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900">Subir documento</h3>
                    <p class="mt-1 text-sm text-gray-600">Carga perfiles, informes tecnicos, presupuestos o estudios que soporten el proyecto.</p>

                    <form method="POST" action="{{ route('investment-projects.documents.store', $project) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="type" value="Tipo de documento" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach ($documentTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('type') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="document" value="Archivo" />
                            <input id="document" name="document" type="file" class="mt-1 block w-full rounded-md border border-gray-300 text-sm file:mr-4 file:border-0 file:bg-blue-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white" required>
                            <p class="mt-1 text-xs text-gray-500">PDF, Word, Excel, CSV o imagen. Maximo 10 MB.</p>
                            <x-input-error :messages="$errors->get('document')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Descripcion breve" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <x-primary-button>Subir al expediente</x-primary-button>
                    </form>
                </section>@endif
            </div>

            <section class="bg-white shadow-sm sm:rounded-lg">
                <div class="border-b px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Documentos cargados</h3>
                    <p class="text-sm text-gray-600">Historial documental para trazabilidad y revision del proyecto.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">Documento</th>
                                <th class="px-4 py-3 text-left">Tipo</th>
                                <th class="px-4 py-3 text-left">Subido por</th>
                                <th class="px-4 py-3 text-left">Tamano</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($project->documents as $document)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $document->original_name }}</div>
                                        <div class="text-gray-500">{{ $document->description ?: 'Sin descripcion' }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $documentTypes[$document->type] ?? $document->type }}</td>
                                    <td class="px-4 py-3">{{ $document->uploader?->name ?: 'Sistema' }}</td>
                                    <td class="px-4 py-3">{{ number_format($document->size / 1024, 1) }} KB</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a class="font-medium text-blue-700" href="{{ route('investment-projects.documents.download', [$project, $document]) }}">Descargar</a>
                                            @if ($canManageProjects)<form method="POST" action="{{ route('investment-projects.documents.destroy', [$project, $document]) }}" onsubmit="return confirm('Eliminar este documento del expediente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="font-medium text-red-600" type="submit">Eliminar</button>
                                            </form>@endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Aun no hay documentos cargados para este proyecto.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
