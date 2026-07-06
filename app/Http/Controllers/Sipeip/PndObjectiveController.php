<?php

/**
 * Controlador MVC del modulo de objetivos del PND; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

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
    // Lista los registros y aplica filtros de busqueda.
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

    // Muestra el formulario para crear un nuevo registro.
    public function create(): View
    {
        return view('sipeip.pnd-objectives.create', [
            'pndObjective' => new PndObjective(['status' => 'active']),
        ]);
    }

    // Valida y guarda un nuevo registro en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $pndObjective = PndObjective::create($data);

        AuditLog::record('Objetivos PND', 'crear', $pndObjective, $data);

        return redirect()->route('pnd-objectives.index')->with('status', 'Objetivo PND registrado correctamente.');
    }

    // Carga el formulario para editar un registro existente.
    public function edit(PndObjective $pndObjective): View
    {
        return view('sipeip.pnd-objectives.edit', ['pndObjective' => $pndObjective]);
    }

    // Valida cambios y actualiza el registro seleccionado.
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
