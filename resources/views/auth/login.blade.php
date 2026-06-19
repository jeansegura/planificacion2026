<x-guest-layout>
    <div style="margin-bottom: 26px;">
        <p style="color: #003893; font-size: 13px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;">Acceso seguro</p>
        <h2 style="margin-top: 8px; color: #0f172a; font-size: 30px; line-height: 1.1; font-weight: 800;">Bienvenido al SIPeIP</h2>
        <p style="margin-top: 10px; color: #64748b; font-size: 15px; line-height: 1.6;">Ingresa con tus credenciales institucionales para continuar con la gestion de planificacion.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 18px;">
        @csrf

        <div>
            <x-input-label for="email" value="Correo institucional" style="font-weight: 700; color: #334155;" />
            <x-text-input
                id="email"
                style="margin-top: 8px; width: 100%; height: 48px; border-radius: 14px; border-color: #cbd5e1;"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="usuario@institucion.gob.ec"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <x-input-label for="password" value="Contrasena" style="font-weight: 700; color: #334155;" />
                @if (Route::has('password.request'))
                    <a style="color: #003893; font-size: 13px; font-weight: 700; text-decoration: none;" href="{{ route('password.request') }}">
                        Olvide mi contrasena
                    </a>
                @endif
            </div>

            <x-text-input
                id="password"
                style="margin-top: 8px; width: 100%; height: 48px; border-radius: 14px; border-color: #cbd5e1;"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Ingresa tu contrasena"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" style="display: flex; align-items: center; gap: 10px; color: #475569; font-size: 14px;">
            <input id="remember_me" type="checkbox" style="border-radius: 6px; border-color: #cbd5e1; color: #003893;" name="remember">
            <span>Recordar sesion en este equipo</span>
        </label>

        <button
            type="submit"
            style="height: 52px; border: 0; border-radius: 16px; background: linear-gradient(90deg, #fcd116 0 12%, #003893 12% 78%, #ce1126 78%); color: white; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; box-shadow: 0 16px 30px rgba(0, 56, 147, .28); cursor: pointer;"
        >
            Iniciar sesion
        </button>

        <div style="padding: 14px 16px; border-radius: 16px; background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; font-size: 13px; line-height: 1.5;">
            Acceso reservado para usuarios autorizados. Las acciones dentro del sistema se registran para auditoria y trazabilidad.
        </div>
    </form>
</x-guest-layout>
