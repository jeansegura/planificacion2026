<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InstitutionalObjective;
use App\Models\StrategicPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstitutionalObjectiveController extends Controller
{
    public function index(Request $request): View
    {
        $objectives = InstitutionalObjective::query()
            ->with('strategicPlan')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.institutional-objectives.index', [
            'objectives' => $objectives,
            'statuses' => InstitutionalObjective::STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('sipeip.institutional-objectives.create', [
            'objective' => new InstitutionalObjective(['status' => 'draft']),
            'plans' => StrategicPlan::orderBy('name')->get(),
            'statuses' => InstitutionalObjective::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $objective = InstitutionalObjective::create($data);

        AuditLog::record('Objetivos institucionales', 'crear', $objective, $data);

        return redirect()->route('institutional-objectives.index')->with('status', 'Objetivo institucional registrado correctamente.');
    }

    public function show(InstitutionalObjective $institutionalObjective): View
    {
        return view('sipeip.institutional-objectives.show', [
            'objective' => $institutionalObjective->load('strategicPlan', 'pndAlignments.pndObjective', 'odsAlignments.sdg'),
            'statuses' => InstitutionalObjective::STATUSES,
        ]);
    }

    public function edit(InstitutionalObjective $institutionalObjective): View
    {
        return view('sipeip.institutional-objectives.edit', [
            'objective' => $institutionalObjective,
            'plans' => StrategicPlan::orderBy('name')->get(),
            'statuses' => InstitutionalObjective::STATUSES,
        ]);
    }

    public function update(Request $request, InstitutionalObjective $institutionalObjective): RedirectResponse
    {
        $data = $this->validatedData($request, $institutionalObjective);
        $before = $institutionalObjective->only(array_keys($data));
        $institutionalObjective->update($data);

        AuditLog::record('Objetivos institucionales', 'actualizar', $institutionalObjective, [
            'before' => $before,
            'after' => $institutionalObjective->only(array_keys($data)),
        ]);

        return redirect()->route('institutional-objectives.index')->with('status', 'Objetivo institucional actualizado correctamente.');
    }

    public function changeStatus(Request $request, InstitutionalObjective $institutionalObjective): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(InstitutionalObjective::STATUSES))],
            'observations' => ['nullable', 'string'],
        ]);

        $before = $institutionalObjective->only(['status', 'observations']);
        $institutionalObjective->update($data);

        AuditLog::record('Objetivos institucionales', 'cambiar estado', $institutionalObjective, [
            'before' => $before,
            'after' => $institutionalObjective->only(['status', 'observations']),
        ]);

        return back()->with('status', 'Estado del objetivo actualizado.');
    }

    private function validatedData(Request $request, ?InstitutionalObjective $objective = null): array
    {
        return $request->validate([
            'strategic_plan_id' => ['nullable', 'exists:strategic_plans,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('institutional_objectives')->ignore($objective?->id)],
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'baseline' => ['nullable', 'string'],
            'expected_result' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(InstitutionalObjective::STATUSES))],
            'observations' => ['nullable', 'string'],
        ]);
    }
}
