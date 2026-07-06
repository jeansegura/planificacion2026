<?php

/**
 * Controlador MVC del modulo de planes estrategicos; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\StrategicPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StrategicPlanController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $plans = StrategicPlan::query()
            ->with('responsible')
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

        return view('sipeip.strategic-plans.index', [
            'plans' => $plans,
            'statuses' => StrategicPlan::STATUSES,
        ]);
    }

    // Muestra el formulario para crear un nuevo registro.
    public function create(): View
    {
        return view('sipeip.strategic-plans.create', [
            'plan' => new StrategicPlan(['status' => 'draft']),
            'statuses' => StrategicPlan::STATUSES,
            'users' => User::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    // Valida y guarda un nuevo registro en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['objectives'] = $this->linesToArray($request->input('objectives_text'));
        $data['goals'] = $this->linesToArray($request->input('goals_text'));

        $plan = StrategicPlan::create($data);
        AuditLog::record('Planificacion estrategica', 'crear', $plan, $data);

        return redirect()->route('strategic-plans.index')->with('status', 'Plan estrategico registrado correctamente.');
    }

    // Muestra el detalle del registro seleccionado.
    public function show(StrategicPlan $strategicPlan): View
    {
        return view('sipeip.strategic-plans.show', [
            'plan' => $strategicPlan->load('responsible'),
            'statuses' => StrategicPlan::STATUSES,
        ]);
    }

    // Carga el formulario para editar un registro existente.
    public function edit(StrategicPlan $strategicPlan): View
    {
        return view('sipeip.strategic-plans.edit', [
            'plan' => $strategicPlan,
            'statuses' => StrategicPlan::STATUSES,
            'users' => User::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    // Valida cambios y actualiza el registro seleccionado.
    public function update(Request $request, StrategicPlan $strategicPlan): RedirectResponse
    {
        $data = $this->validatedData($request, $strategicPlan);
        $data['objectives'] = $this->linesToArray($request->input('objectives_text'));
        $data['goals'] = $this->linesToArray($request->input('goals_text'));

        $before = $strategicPlan->only(array_keys($data));
        $strategicPlan->update($data);

        AuditLog::record('Planificacion estrategica', 'actualizar', $strategicPlan, [
            'before' => $before,
            'after' => $strategicPlan->only(array_keys($data)),
        ]);

        return redirect()->route('strategic-plans.index')->with('status', 'Plan estrategico actualizado correctamente.');
    }

    // Actualiza el estado de revision del registro.
    public function changeStatus(Request $request, StrategicPlan $strategicPlan): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(StrategicPlan::STATUSES))],
            'observations' => ['nullable', 'string'],
        ]);

        $before = $strategicPlan->only(['status', 'observations']);
        $strategicPlan->update($data);

        AuditLog::record('Planificacion estrategica', 'cambiar estado', $strategicPlan, [
            'before' => $before,
            'after' => $strategicPlan->only(['status', 'observations']),
        ]);

        return back()->with('status', 'Estado del plan actualizado.');
    }

    private function validatedData(Request $request, ?StrategicPlan $plan = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('strategic_plans')->ignore($plan?->id)],
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'period_start' => ['required', 'integer', 'min:2020', 'max:2100'],
            'period_end' => ['required', 'integer', 'min:2020', 'max:2100', 'gte:period_start'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(StrategicPlan::STATUSES))],
            'responsible_user_id' => ['nullable', 'exists:users,id'],
            'observations' => ['nullable', 'string'],
        ]);
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
