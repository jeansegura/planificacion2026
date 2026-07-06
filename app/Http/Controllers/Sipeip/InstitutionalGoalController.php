<?php

/**
 * Controlador MVC del modulo de metas institucionales; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InstitutionalGoal;
use App\Models\InstitutionalObjective;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstitutionalGoalController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $goals = InstitutionalGoal::query()
            ->with('institutionalObjective')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.goals.index', ['goals' => $goals]);
    }

    // Muestra el formulario para crear un nuevo registro.
    public function create(): View
    {
        return view('sipeip.goals.create', [
            'goal' => new InstitutionalGoal(['status' => 'active', 'period_year' => now()->year]),
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
        ]);
    }

    // Valida y guarda un nuevo registro en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $goal = InstitutionalGoal::create($data);
        AuditLog::record('Metas institucionales', 'crear', $goal, $data);

        return redirect()->route('goals.index')->with('status', 'Meta institucional registrada correctamente.');
    }

    // Carga el formulario para editar un registro existente.
    public function edit(InstitutionalGoal $goal): View
    {
        return view('sipeip.goals.edit', [
            'goal' => $goal,
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
        ]);
    }

    // Valida cambios y actualiza el registro seleccionado.
    public function update(Request $request, InstitutionalGoal $goal): RedirectResponse
    {
        $data = $this->validatedData($request, $goal);
        $before = $goal->only(array_keys($data));
        $goal->update($data);

        AuditLog::record('Metas institucionales', 'actualizar', $goal, [
            'before' => $before,
            'after' => $goal->only(array_keys($data)),
        ]);

        return redirect()->route('goals.index')->with('status', 'Meta institucional actualizada correctamente.');
    }

    private function validatedData(Request $request, ?InstitutionalGoal $goal = null): array
    {
        return $request->validate([
            'institutional_objective_id' => ['required', 'exists:institutional_objectives,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('institutional_goals')->ignore($goal?->id)],
            'name' => ['required', 'string', 'max:255'],
            'period_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'target_value' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'responsible' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'description' => ['nullable', 'string'],
        ]);
    }
}
