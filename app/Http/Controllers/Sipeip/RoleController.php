<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::query()
            ->withCount('users')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.roles.index', ['roles' => $roles]);
    }

    public function create(): View
    {
        return view('sipeip.roles.create', [
            'role' => new Role(['status' => 'active', 'permissions' => []]),
            'permissions' => $this->permissions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $role = Role::create($data);

        AuditLog::record('Roles y permisos', 'crear', $role, $data);

        return redirect()->route('roles.index')->with('status', 'Rol registrado correctamente.');
    }

    public function edit(Role $role): View
    {
        return view('sipeip.roles.edit', [
            'role' => $role,
            'permissions' => $this->permissions(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $this->validatedData($request, $role);
        $before = $role->only(array_keys($data));
        $role->update($data);

        AuditLog::record('Roles y permisos', 'actualizar', $role, [
            'before' => $before,
            'after' => $role->only(array_keys($data)),
        ]);

        return redirect()->route('roles.index')->with('status', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->update(['status' => 'inactive']);
        AuditLog::record('Roles y permisos', 'desactivar', $role);

        return back()->with('status', 'Rol desactivado sin eliminar su historial.');
    }

    private function validatedData(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('roles')->ignore($role?->id)],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }

    private function permissions(): array
    {
        return [
            'users.view' => 'Consultar usuarios institucionales',
            'users.manage' => 'Crear y actualizar usuarios institucionales',
            'roles.view' => 'Consultar roles y permisos',
            'roles.manage' => 'Crear y actualizar roles y permisos',
            'plans.view' => 'Consultar planes estrategicos',
            'plans.manage' => 'Crear y actualizar planes estrategicos',
            'plans.validate' => 'Cambiar estados de validacion de planes',
            'objectives.view' => 'Consultar objetivos institucionales',
            'objectives.manage' => 'Crear y actualizar objetivos institucionales',
            'objectives.validate' => 'Validar objetivos institucionales',
            'pnd.view' => 'Consultar objetivos y alineaciones PND',
            'pnd.manage' => 'Gestionar objetivos y alineaciones PND',
            'ods.view' => 'Consultar ODS y alineaciones ODS',
            'ods.manage' => 'Gestionar ODS y alineaciones ODS',
            'goals.view' => 'Consultar metas e indicadores',
            'goals.manage' => 'Gestionar metas e indicadores',
            'projects.view' => 'Consultar proyectos de inversion',
            'projects.manage' => 'Gestionar proyectos de inversion',
            'entities.view' => 'Consultar entidades publicas',
            'entities.manage' => 'Gestionar entidades publicas',
            'reports.view' => 'Consultar y exportar reportes',
            'audit.view' => 'Consultar trazabilidad del sistema',
        ];
    }
}
