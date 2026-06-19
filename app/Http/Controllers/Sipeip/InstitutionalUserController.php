<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PublicEntity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstitutionalUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('role', 'publicEntity')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('identification', 'like', "%{$search}%")
                        ->orWhere('institution', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('role_id'), fn ($query) => $query->where('role_id', $request->role_id))
            ->when($request->filled('user_type'), fn ($query) => $query->where('user_type', $request->user_type))
            ->when($request->filled('public_entity_id'), fn ($query) => $query->where('public_entity_id', $request->public_entity_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
            'publicEntities' => PublicEntity::active()->orderBy('name')->get(),
            'userTypes' => $this->userTypes(),
        ]);
    }

    public function create(): View
    {
        return view('sipeip.users.create', [
            'roles' => Role::active()->orderBy('name')->get(),
            'publicEntities' => PublicEntity::active()->orderBy('name')->get(),
            'userTypes' => $this->userTypes(),
            'authProviders' => $this->authProviders(),
            'user' => new User([
                'status' => 'active',
                'user_type' => 'snp_technician',
                'auth_provider' => 'local',
                'must_change_password' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $plainPassword = null;
        $this->applyInstitutionName($data);

        if ($data['auth_provider'] === 'local') {
            $plainPassword = $this->resolvePassword($data, true);
            $data['password'] = Hash::make($plainPassword);
            $data['email_verified_at'] = now();
        } else {
            $data['password'] = Hash::make(Str::random(40));
            $data['must_change_password'] = false;
        }

        unset($data['password_confirmation'], $data['generate_password'], $data['reset_password']);

        $user = User::create($data);
        AuditLog::record('Usuarios institucionales', 'crear', $user, $user->only($this->auditableFields($data)));

        $redirect = redirect()->route('users.index')->with('status', 'Usuario institucional registrado correctamente.');

        if ($plainPassword) {
            $redirect->with('generated_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $plainPassword,
                'role' => $user->role?->name,
            ]);
        }

        return $redirect;
    }

    public function edit(User $user): View
    {
        return view('sipeip.users.edit', [
            'roles' => Role::active()->orderBy('name')->get(),
            'publicEntities' => PublicEntity::active()->orderBy('name')->get(),
            'userTypes' => $this->userTypes(),
            'authProviders' => $this->authProviders(),
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatedData($request, $user);
        $plainPassword = null;
        $this->applyInstitutionName($data);

        if ($data['auth_provider'] === 'local') {
            if (! empty($data['password']) || $request->boolean('reset_password')) {
                $plainPassword = $this->resolvePassword($data, false, $request->boolean('reset_password'));
                $data['password'] = Hash::make($plainPassword);
                $data['must_change_password'] = true;
            } else {
                unset($data['password']);
            }
        } else {
            $data['must_change_password'] = false;
            unset($data['password']);
        }

        unset($data['password_confirmation'], $data['generate_password'], $data['reset_password']);

        $auditableFields = $this->auditableFields($data);
        $before = $user->only($auditableFields);
        $user->update($data);

        AuditLog::record('Usuarios institucionales', 'actualizar', $user, [
            'before' => $before,
            'after' => $user->only($auditableFields),
        ]);

        $redirect = redirect()->route('users.index')->with('status', 'Usuario institucional actualizado correctamente.');

        if ($plainPassword) {
            $redirect->with('generated_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $plainPassword,
                'role' => $user->role?->name,
            ]);
        }

        return $redirect;
    }

    public function deactivate(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('status', 'No puedes desactivar tu propia cuenta desde este modulo.');
        }

        $user->update(['status' => 'inactive', 'deactivated_at' => now()]);
        AuditLog::record('Usuarios institucionales', 'desactivar', $user);

        return back()->with('status', 'Usuario desactivado.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['status' => 'active', 'deactivated_at' => null]);
        AuditLog::record('Usuarios institucionales', 'activar', $user);

        return back()->with('status', 'Usuario activado.');
    }

    private function validatedData(Request $request, ?User $user = null): array
    {
        $isLocalProvider = $request->input('auth_provider', 'local') === 'local';
        $shouldGeneratePassword = $request->boolean('generate_password') || $request->boolean('reset_password');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identification' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'password' => [
                Rule::requiredIf($isLocalProvider && ! $user && ! $shouldGeneratePassword),
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'institution' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'role_id' => ['required', 'exists:roles,id'],
            'public_entity_id' => ['required', 'exists:public_entities,id'],
            'user_type' => ['required', Rule::in(array_keys($this->userTypes()))],
            'organizational_unit' => ['required', 'string', 'max:255'],
            'auth_provider' => ['required', Rule::in(array_keys($this->authProviders()))],
            'sso_subject' => [
                Rule::requiredIf(! $isLocalProvider),
                'nullable',
                'string',
                'max:255',
            ],
            'must_change_password' => ['sometimes', 'boolean'],
            'generate_password' => ['sometimes', 'boolean'],
            'reset_password' => ['sometimes', 'boolean'],
        ]);
    }

    private function auditableFields(array $data): array
    {
        return array_values(array_diff(array_keys($data), ['password']));
    }

    private function resolvePassword(array $data, bool $creating, bool $resetting = false): string
    {
        if (! empty($data['password']) && ! ($data['generate_password'] ?? false) && ! $resetting) {
            return $data['password'];
        }

        return Str::password(14, true, true, false, false);
    }

    private function applyInstitutionName(array &$data): void
    {
        $entity = PublicEntity::find($data['public_entity_id'] ?? null);
        if ($entity) {
            $data['institution'] = $entity->name;
        }
    }

    private function userTypes(): array
    {
        return [
            'snp_technician' => 'Tecnico de la SNP',
            'snp_reviewer' => 'Revisor de la SNP',
            'investment_analyst' => 'Analista de inversion publica',
            'external_entity' => 'Usuario externo de entidad publica',
            'auditor' => 'Auditor',
        ];
    }

    private function authProviders(): array
    {
        return [
            'local' => 'Credencial local generada',
            'identity_server' => 'Identity Server / SSO institucional',
        ];
    }
}
