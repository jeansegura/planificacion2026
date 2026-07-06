{{-- Vista Blade de gestion de usuarios institucionales; centraliza campos compartidos por crear y editar. --}}
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div><x-input-label for="name" value="Nombre completo" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
    <div><x-input-label for="identification" value="Identificacion" /><x-text-input id="identification" name="identification" class="mt-1 block w-full" value="{{ old('identification', $user->identification) }}" /><x-input-error :messages="$errors->get('identification')" class="mt-2" /></div>
    <div><x-input-label for="email" value="Correo institucional" /><x-text-input id="email" type="email" name="email" class="mt-1 block w-full" value="{{ old('email', $user->email) }}" required /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
    <div><x-input-label for="phone" value="Telefono" /><x-text-input id="phone" name="phone" class="mt-1 block w-full" value="{{ old('phone', $user->phone) }}" /><x-input-error :messages="$errors->get('phone')" class="mt-2" /></div>
    <div>
        <x-input-label for="user_type" value="Tipo de usuario" />
        <select id="user_type" name="user_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            @foreach ($userTypes as $key => $label)
                <option value="{{ $key }}" @selected(old('user_type', $user->user_type) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="public_entity_id" value="Institucion / entidad publica" />
        <select id="public_entity_id" name="public_entity_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">Seleccione una institucion</option>
            @foreach ($publicEntities as $entity)
                <option value="{{ $entity->id }}" @selected((string) old('public_entity_id', $user->public_entity_id) === (string) $entity->id)>{{ $entity->code }} - {{ $entity->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('public_entity_id')" class="mt-2" />
    </div>
    <div><x-input-label for="organizational_unit" value="Unidad organizacional" /><x-text-input id="organizational_unit" name="organizational_unit" class="mt-1 block w-full" value="{{ old('organizational_unit', $user->organizational_unit) }}" placeholder="Ej. Direccion de Planificacion" required /><x-input-error :messages="$errors->get('organizational_unit')" class="mt-2" /></div>
    <div><x-input-label for="position" value="Cargo" /><x-text-input id="position" name="position" class="mt-1 block w-full" value="{{ old('position', $user->position) }}" /><x-input-error :messages="$errors->get('position')" class="mt-2" /></div>
    <div><x-input-label for="role_id" value="Rol RBAC" /><select id="role_id" name="role_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required><option value="">Seleccione un rol</option>@foreach ($roles as $role)<option value="{{ $role->id }}" @selected((string) old('role_id', $user->role_id) === (string) $role->id)>{{ $role->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('role_id')" class="mt-2" /></div>
    <div><x-input-label for="status" value="Estado" /><select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><option value="active" @selected(old('status', $user->status) === 'active')>Activo</option><option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactivo</option></select><x-input-error :messages="$errors->get('status')" class="mt-2" /></div>
    <div>
        <x-input-label for="auth_provider" value="Proveedor de autenticacion" />
        <select id="auth_provider" name="auth_provider" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            @foreach ($authProviders as $key => $label)
                <option value="{{ $key }}" @selected(old('auth_provider', $user->auth_provider) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('auth_provider')" class="mt-2" />
    </div>
    <div><x-input-label for="sso_subject" value="Identificador SSO / Identity Server" /><x-text-input id="sso_subject" name="sso_subject" class="mt-1 block w-full" value="{{ old('sso_subject', $user->sso_subject) }}" placeholder="Ej. uid institucional" /><x-input-error :messages="$errors->get('sso_subject')" class="mt-2" /></div>

    @if (! $user->exists)
        <div class="md:col-span-2 rounded-md border border-blue-100 bg-blue-50 p-4">
            <label class="flex items-start gap-3 text-sm text-blue-950">
                <input type="checkbox" name="generate_password" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('generate_password', '1'))>
                <span><strong>Generar credencial segura automaticamente.</strong><br>El sistema mostrara la contrasena una sola vez al guardar para entregarla por canal seguro.</span>
            </label>
        </div>
    @else
        <div class="md:col-span-2 rounded-md border border-yellow-200 bg-yellow-50 p-4">
            <label class="flex items-start gap-3 text-sm text-yellow-950">
                <input type="checkbox" name="reset_password" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(old('reset_password'))>
                <span><strong>Regenerar credencial local.</strong><br>Use esta opcion si el usuario perdio su acceso o cambio de perfil.</span>
            </label>
        </div>
    @endif

    <div><x-input-label for="password" value="Contrasena manual opcional" /><x-text-input id="password" type="password" name="password" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
    <div><x-input-label for="password_confirmation" value="Confirmar contrasena manual" /><x-text-input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 block w-full" /></div>
</div>
<div class="mt-6 flex items-center gap-3"><x-primary-button>Guardar</x-primary-button><a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a></div>
