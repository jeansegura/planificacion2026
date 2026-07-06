<?php

/**
 * Controlador MVC del modulo de alineaciones con el PND; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InstitutionalObjective;
use App\Models\PndAlignment;
use App\Models\PndObjective;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PndAlignmentController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $alignments = PndAlignment::query()
            ->with('institutionalObjective', 'pndObjective')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.pnd-alignments.index', [
            'alignments' => $alignments,
            'statuses' => PndAlignment::STATUSES,
        ]);
    }

    // Muestra el formulario para crear un nuevo registro.
    public function create(): View
    {
        return view('sipeip.pnd-alignments.create', [
            'alignment' => new PndAlignment(['status' => 'pending', 'contribution_level' => 'medium']),
            'institutionalObjectives' => InstitutionalObjective::orderBy('code')->get(),
            'pndObjectives' => PndObjective::where('status', 'active')->orderBy('axis')->orderBy('code')->get(),
            'statuses' => PndAlignment::STATUSES,
        ]);
    }

    // Valida y guarda un nuevo registro en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $alignment = PndAlignment::create($data);

        AuditLog::record('Alineacion PND', 'crear', $alignment, $data);

        return redirect()->route('pnd-alignments.index')->with('status', 'Alineacion PND registrada correctamente.');
    }

    // Carga el formulario para editar un registro existente.
    public function edit(PndAlignment $pndAlignment): View
    {
        return view('sipeip.pnd-alignments.edit', [
            'alignment' => $pndAlignment,
            'institutionalObjectives' => InstitutionalObjective::orderBy('code')->get(),
            'pndObjectives' => PndObjective::where('status', 'active')->orderBy('axis')->orderBy('code')->get(),
            'statuses' => PndAlignment::STATUSES,
        ]);
    }

    // Valida cambios y actualiza el registro seleccionado.
    public function update(Request $request, PndAlignment $pndAlignment): RedirectResponse
    {
        $data = $this->validatedData($request, $pndAlignment);
        $before = $pndAlignment->only(array_keys($data));
        $pndAlignment->update($data);

        AuditLog::record('Alineacion PND', 'actualizar', $pndAlignment, [
            'before' => $before,
            'after' => $pndAlignment->only(array_keys($data)),
        ]);

        return redirect()->route('pnd-alignments.index')->with('status', 'Alineacion PND actualizada correctamente.');
    }

    private function validatedData(Request $request, ?PndAlignment $alignment = null): array
    {
        return $request->validate([
            'institutional_objective_id' => ['required', 'exists:institutional_objectives,id'],
            'pnd_objective_id' => [
                'required',
                'exists:pnd_objectives,id',
                Rule::unique('pnd_alignments')
                    ->where('institutional_objective_id', $request->institutional_objective_id)
                    ->ignore($alignment?->id),
            ],
            'contribution_level' => ['required', Rule::in(['low', 'medium', 'high'])],
            'justification' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(PndAlignment::STATUSES))],
            'observations' => ['nullable', 'string'],
        ]);
    }
}
