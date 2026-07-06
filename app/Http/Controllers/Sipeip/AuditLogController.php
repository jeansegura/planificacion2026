<?php

/**
 * Controlador MVC del modulo de auditoria del sistema; recibe solicitudes, valida datos y entrega vistas o descargas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    // Lista los registros y aplica filtros de busqueda.
    public function index(Request $request): View
    {
        $logs = AuditLog::query()
            ->with('user')
            ->when($request->filled('module'), fn ($query) => $query->where('module', 'like', '%'.$request->module.'%'))
            ->when($request->filled('action'), fn ($query) => $query->where('action', 'like', '%'.$request->action.'%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('sipeip.audit-logs.index', ['logs' => $logs]);
    }
}
