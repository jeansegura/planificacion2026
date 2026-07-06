<?php

/**
 * Controlador MVC del modulo de alineaciones con ODS; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InstitutionalObjective;
use App\Models\OdsAlignment;
use App\Models\Sdg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OdsAlignmentController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $alignments = OdsAlignment::query()
            ->with('institutionalObjective', 'sdg')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.ods-alignments.index', [
            'alignments' => $alignments,
            'statuses' => OdsAlignment::STATUSES,
        ]);
    }

    // Muestra el formulario para crear un nuevo registro.
    public function create(): View
    {
        return view('sipeip.ods-alignments.create', [
            'alignment' => new OdsAlignment(['status' => 'pending', 'contribution_level' => 'medium']),
            'institutionalObjectives' => InstitutionalObjective::orderBy('code')->get(),
            'sdgs' => Sdg::where('status', 'active')->orderBy('number')->get(),
            'statuses' => OdsAlignment::STATUSES,
        ]);
    }

    // Valida y guarda un nuevo registro en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $alignment = OdsAlignment::create($data);

        AuditLog::record('Alineacion ODS', 'crear', $alignment, $data);

        return redirect()->route('ods-alignments.index')->with('status', 'Alineacion ODS registrada correctamente.');
    }

    // Carga el formulario para editar un registro existente.
    public function edit(OdsAlignment $odsAlignment): View
    {
        return view('sipeip.ods-alignments.edit', [
            'alignment' => $odsAlignment,
            'institutionalObjectives' => InstitutionalObjective::orderBy('code')->get(),
            'sdgs' => Sdg::where('status', 'active')->orderBy('number')->get(),
            'statuses' => OdsAlignment::STATUSES,
        ]);
    }

    // Valida cambios y actualiza el registro seleccionado.
    public function update(Request $request, OdsAlignment $odsAlignment): RedirectResponse
    {
        $data = $this->validatedData($request, $odsAlignment);
        $before = $odsAlignment->only(array_keys($data));
        $odsAlignment->update($data);

        AuditLog::record('Alineacion ODS', 'actualizar', $odsAlignment, [
            'before' => $before,
            'after' => $odsAlignment->only(array_keys($data)),
        ]);

        return redirect()->route('ods-alignments.index')->with('status', 'Alineacion ODS actualizada correctamente.');
    }

    private function validatedData(Request $request, ?OdsAlignment $alignment = null): array
    {
        return $request->validate([
            'institutional_objective_id' => ['required', 'exists:institutional_objectives,id'],
            'sdg_id' => ['required', 'exists:sdgs,id'],
            'target_reference' => ['nullable', 'string', 'max:100'],
            'contribution_level' => ['required', Rule::in(['low', 'medium', 'high'])],
            'justification' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(OdsAlignment::STATUSES))],
            'observations' => ['nullable', 'string'],
        ]);
    }
}
