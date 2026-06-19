<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PublicEntity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicEntityController extends Controller
{
    public function index(Request $request): View
    {
        $entities = PublicEntity::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('sector', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.public-entities.index', ['entities' => $entities]);
    }

    public function create(): View
    {
        return view('sipeip.public-entities.create', [
            'entity' => new PublicEntity(['status' => 'active', 'government_level' => 'Nacional']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $entity = PublicEntity::create($data);
        AuditLog::record('Entidades publicas', 'crear', $entity, $data);

        return redirect()->route('public-entities.index')->with('status', 'Entidad publica registrada correctamente.');
    }

    public function edit(PublicEntity $publicEntity): View
    {
        return view('sipeip.public-entities.edit', ['entity' => $publicEntity]);
    }

    public function update(Request $request, PublicEntity $publicEntity): RedirectResponse
    {
        $data = $this->validatedData($request, $publicEntity);
        $before = $publicEntity->only(array_keys($data));
        $publicEntity->update($data);

        AuditLog::record('Entidades publicas', 'actualizar', $publicEntity, [
            'before' => $before,
            'after' => $publicEntity->only(array_keys($data)),
        ]);

        return redirect()->route('public-entities.index')->with('status', 'Entidad publica actualizada correctamente.');
    }

    private function validatedData(Request $request, ?PublicEntity $entity = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('public_entities')->ignore($entity?->id)],
            'name' => ['required', 'string', 'max:255'],
            'acronym' => ['nullable', 'string', 'max:50'],
            'government_level' => ['required', 'string', 'max:100'],
            'macro_sector' => ['nullable', 'string', 'max:150'],
            'sector' => ['nullable', 'string', 'max:150'],
            'subsector' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
