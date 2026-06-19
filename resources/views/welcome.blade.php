<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SIPeIP') }}</title>
    <style>
        :root {
            --yellow: #fcd116;
            --blue: #003893;
            --red: #ce1126;
            --ink: #172033;
            --muted: #64748b;
            --line: #dbe3ef;
            --paper: #ffffff;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f6f8fc;
        }

        .flag { display: grid; grid-template-columns: 2fr 1fr 1fr; height: 8px; }
        .flag span:nth-child(1) { background: var(--yellow); }
        .flag span:nth-child(2) { background: var(--blue); }
        .flag span:nth-child(3) { background: var(--red); }

        .shell { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 22px 0;
        }

        .brand { display: flex; align-items: center; gap: 12px; font-weight: 800; }
        .brand-mark {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            color: white;
            background: linear-gradient(135deg, var(--blue), #0d5bd8);
            box-shadow: 0 14px 28px rgba(0, 56, 147, .18);
        }

        nav { display: flex; align-items: center; gap: 12px; }
        a { color: inherit; text-decoration: none; }
        .link { color: var(--blue); font-weight: 700; }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 18px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: white;
            font-weight: 700;
        }
        .button.primary { border-color: var(--yellow); background: var(--yellow); color: #10234d; }
        .button.dark { border-color: var(--blue); background: var(--blue); color: white; }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, .88fr) minmax(420px, 1.12fr);
            gap: 34px;
            align-items: stretch;
            padding: 42px 0 62px;
        }

        .hero-copy {
            padding: 46px;
            border-radius: 8px;
            background: linear-gradient(135deg, #fff, #eef4ff);
            border: 1px solid var(--line);
            box-shadow: 0 24px 60px rgba(19, 40, 77, .08);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(252, 209, 22, .22);
            color: #6a5200;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        h1 { margin: 0; font-size: clamp(34px, 5vw, 62px); line-height: 1.02; }
        .lead { margin: 20px 0 28px; max-width: 680px; color: var(--muted); font-size: 18px; line-height: 1.7; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }

        .ecuador-visual {
            position: relative;
            min-height: 520px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(0, 56, 147, .18);
            box-shadow: 0 24px 60px rgba(19, 40, 77, .10);
        }
        .ecuador-visual img {
            width: 100%;
            height: 100%;
            min-height: 520px;
            display: block;
            object-fit: cover;
            object-position: center;
        }
        .ecuador-visual figcaption {
            position: absolute;
            right: 18px;
            bottom: 18px;
            left: 18px;
            padding: 14px 16px;
            border-left: 4px solid var(--yellow);
            color: white;
            background: rgba(0, 36, 96, .88);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.45;
        }

        footer { padding: 26px 0; color: var(--muted); border-top: 1px solid var(--line); }

        @media (max-width: 900px) {
            header { align-items: flex-start; flex-direction: column; }
            .hero { grid-template-columns: 1fr; }
            .hero-copy { padding: 30px; }
            .ecuador-visual,
            .ecuador-visual img { min-height: 420px; }
        }

        @media (max-width: 620px) {
            nav { width: 100%; flex-wrap: wrap; }
            .button { flex: 1; }
            .hero { padding-top: 22px; }
            .ecuador-visual,
            .ecuador-visual img { min-height: 320px; }
            .ecuador-visual figcaption { right: 12px; bottom: 12px; left: 12px; }
        }
    </style>
</head>
<body>
    <div class="flag"><span></span><span></span><span></span></div>

    <header class="shell">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand-mark">SP</span>
            <span>SIPeIP Ecuador<br><small>Planificacion e inversion publica</small></span>
        </a>

        @if (Route::has('login'))
            <nav>
                @auth
                    <a class="button dark" href="{{ url('/dashboard') }}">Ir al panel</a>
                @else
                    <a class="link" href="{{ route('login') }}">Acceso</a>
                    @if (Route::has('register'))
                        <a class="button" href="{{ route('register') }}">Registro</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="shell">
        <section class="hero">
            <div class="hero-copy">
                <span class="eyebrow">Caso complexivo UTPL 2026</span>
                <h1>Sistema Integral de Planificacion e Inversion Publica</h1>
                <p class="lead">
                    Plataforma MVC para configurar instituciones, alinear objetivos con el PND y ODS,
                    gestionar metas e indicadores, registrar proyectos de inversion y mantener trazabilidad
                    documental para auditoria.
                </p>
                <div class="actions">
                    @auth
                        <a class="button primary" href="{{ route('investment-projects.index') }}">Gestionar proyectos</a>
                        <a class="button" href="{{ route('reports.index') }}">Ver reportes</a>
                    @else
                        <a class="button primary" href="{{ route('login') }}">Ingresar al sistema</a>
                        @if (Route::has('register'))
                            <a class="button" href="{{ route('register') }}">Crear cuenta</a>
                        @endif
                    @endauth
                </div>
            </div>

            <figure class="ecuador-visual">
                <img
                    src="{{ asset('images/ecuador-planificacion.png') }}"
                    alt="Ecuador representado mediante territorio, infraestructura y desarrollo publico"
                >
                <figcaption>
                    Planificacion territorial e inversion publica para el desarrollo sostenible del Ecuador.
                </figcaption>
            </figure>
        </section>
    </main>

    <footer class="shell">
        SIPeIP demo academico - Laravel MVC, PostgreSQL y Blade.
    </footer>
</body>
</html>
