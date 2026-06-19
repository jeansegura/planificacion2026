<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Indicator;
use App\Models\InstitutionalGoal;
use App\Models\InstitutionalObjective;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IndicatorController extends Controller
{
    public function index(Request $request): View
    {
        $indicators = Indicator::query()
            ->with('institutionalGoal', 'institutionalObjective')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.indicators.index', ['indicators' => $indicators]);
    }

    public function create(): View
    {
        return view('sipeip.indicators.create', [
            'indicator' => new Indicator(['status' => 'active', 'periodicity' => 'Anual']),
            'goals' => InstitutionalGoal::orderBy('code')->get(),
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $indicator = Indicator::create($data);
        AuditLog::record('Indicadores institucionales', 'crear', $indicator, $data);

        return redirect()->route('indicators.index')->with('status', 'Indicador registrado correctamente.');
    }

    public function edit(Indicator $indicator): View
    {
        return view('sipeip.indicators.edit', [
            'indicator' => $indicator,
            'goals' => InstitutionalGoal::orderBy('code')->get(),
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
        ]);
    }

    public function update(Request $request, Indicator $indicator): RedirectResponse
    {
        $data = $this->validatedData($request, $indicator);
        $before = $indicator->only(array_keys($data));
        $indicator->update($data);

        AuditLog::record('Indicadores institucionales', 'actualizar', $indicator, [
            'before' => $before,
            'after' => $indicator->only(array_keys($data)),
        ]);

        return redirect()->route('indicators.index')->with('status', 'Indicador actualizado correctamente.');
    }

    private function validatedData(Request $request, ?Indicator $indicator = null): array
    {
        return $request->validate([
            'institutional_goal_id' => ['nullable', 'exists:institutional_goals,id'],
            'institutional_objective_id' => ['nullable', 'exists:institutional_objectives,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('indicators')->ignore($indicator?->id)],
            'name' => ['required', 'string', 'max:255'],
            'formula' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:50'],
            'periodicity' => ['required', 'string', 'max:80'],
            'baseline_value' => ['required', 'numeric', 'min:0'],
            'target_value' => ['required', 'numeric', 'min:0'],
            'current_value' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
