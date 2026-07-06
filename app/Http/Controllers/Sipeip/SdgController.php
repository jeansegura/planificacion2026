<?php

/**
 * Controlador MVC del modulo de objetivos ODS; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Sdg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SdgController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $sdgs = Sdg::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('number', (int) $search);
            })
            ->orderBy('number')
            ->paginate(17)
            ->withQueryString();

        return view('sipeip.sdgs.index', ['sdgs' => $sdgs]);
    }

    // Carga el formulario para editar un registro existente.
    public function edit(Sdg $sdg): View
    {
        return view('sipeip.sdgs.edit', ['sdg' => $sdg]);
    }

    // Valida cambios y actualiza el registro seleccionado.
    public function update(Request $request, Sdg $sdg): RedirectResponse
    {
        $data = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'max:17', Rule::unique('sdgs')->ignore($sdg->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $before = $sdg->only(array_keys($data));
        $sdg->update($data);

        AuditLog::record('Objetivos ODS', 'actualizar', $sdg, [
            'before' => $before,
            'after' => $sdg->only(array_keys($data)),
        ]);

        return redirect()->route('sdgs.index')->with('status', 'ODS actualizado correctamente.');
    }
}
