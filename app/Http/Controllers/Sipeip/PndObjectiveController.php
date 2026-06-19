<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PndObjective;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PndObjectiveController extends Controller
{
    public function index(Request $request): View
    {
        $pndObjectives = PndObjective::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('axis', 'like', "%{$search}%");
            })
            ->orderBy('axis')
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.pnd-objectives.index', ['pndObjectives' => $pndObjectives]);
    }

    public function create(): View
    {
        return view('sipeip.pnd-objectives.create', [
            'pndObjective' => new PndObjective(['status' => 'active']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $pndObjective = PndObjective::create($data);

        AuditLog::record('Objetivos PND', 'crear', $pndObjective, $data);

        return redirect()->route('pnd-objectives.index')->with('status', 'Objetivo PND registrado correctamente.');
    }

    public function edit(PndObjective $pndObjective): View
    {
        return view('sipeip.pnd-objectives.edit', ['pndObjective' => $pndObjective]);
    }

    public function update(Request $request, PndObjective $pndObjective): RedirectResponse
    {
        $data = $this->validatedData($request, $pndObjective);
        $before = $pndObjective->only(array_keys($data));
        $pndObjective->update($data);

        AuditLog::record('Objetivos PND', 'actualizar', $pndObjective, [
            'before' => $before,
            'after' => $pndObjective->only(array_keys($data)),
        ]);

        return redirect()->route('pnd-objectives.index')->with('status', 'Objetivo PND actualizado correctamente.');
    }

    private function validatedData(Request $request, ?PndObjective $pndObjective = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('pnd_objectives')->ignore($pndObjective?->id)],
            'axis' => ['required', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:255'],
            'policy' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
