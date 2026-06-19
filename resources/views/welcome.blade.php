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
            grid-template-columns: minmax(0, 1.08fr) minmax(340px, .92fr);
            gap: 34px;
            align-items: stretch;
            padding: 42px 0 28px;
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

        .panel {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(0, 56, 147, .18);
            background: var(--paper);
            box-shadow: 0 24px 60px rgba(19, 40, 77, .10);
        }
        .panel-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 20px;
            color: white;
            background: var(--blue);
        }
        .panel-body { padding: 22px; }
        .step {
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--line);
        }
        .step:last-child { border-bottom: 0; }
        .num {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #fff4b8;
            color: #6a5200;
            font-weight: 900;
        }
        .step h3 { margin: 0 0 4px; font-size: 16px; }
        .step p { margin: 0; color: var(--muted); line-height: 1.5; }

        .section { padding: 30px 0 56px; }
        .section-title { margin: 0 0 18px; font-size: 26px; }
        .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .card {
            padding: 22px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: white;
            min-height: 176px;
        }
        .card strong { display: block; margin-bottom: 8px; font-size: 16px; }
        .card p { margin: 0; color: var(--muted); line-height: 1.55; }
        .tag {
            display: inline-flex;
            margin-bottom: 14px;
            padding: 4px 9px;
            border-radius: 999px;
            background: #eaf1ff;
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
        }

        footer { padding: 26px 0; color: var(--muted); border-top: 1px solid var(--line); }

        @media (max-width: 900px) {
            header { align-items: flex-start; flex-direction: column; }
            .hero { grid-template-columns: 1fr; }
            .hero-copy { padding: 30px; }
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 620px) {
            nav { width: 100%; flex-wrap: wrap; }
            .button { flex: 1; }
            .cards { grid-template-columns: 1fr; }
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

            <aside class="panel" aria-label="Flujo principal del sistema">
                <div class="panel-head">
                    <strong>Ciclo SIPeIP</strong>
                    <span>PND + ODS + inversion</span>
                </div>
                <div class="panel-body">
                    <div class="step">
                        <span class="num">01</span>
                        <div><h3>Configurar institucion</h3><p>Usuarios, roles, entidades y planes estrategicos.</p></div>
                    </div>
                    <div class="step">
                        <span class="num">02</span>
                        <div><h3>Alinear objetivos</h3><p>Objetivos institucionales conectados al PND y a los ODS.</p></div>
                    </div>
                    <div class="step">
                        <span class="num">03</span>
                        <div><h3>Crear proyecto</h3><p>Tipologia, presupuesto, periodo, estado y responsable institucional.</p></div>
                    </div>
                    <div class="step">
                        <span class="num">04</span>
                        <div><h3>Subir soportes</h3><p>Expediente documental, auditoria de cambios y descargas controladas.</p></div>
                    </div>
                </div>
            </aside>
        </section>

        <section class="section">
            <h2 class="section-title">Modulos implementados segun el caso</h2>
            <div class="cards">
                <article class="card">
                    <span class="tag">Configuracion</span>
                    <strong>Instituciones y accesos</strong>
                    <p>Gestion de entidades, usuarios, roles y permisos para acceso segmentado.</p>
                </article>
                <article class="card">
                    <span class="tag">Planificacion</span>
                    <strong>Objetivos, PND y ODS</strong>
                    <p>Alineacion estrategica para justificar la contribucion institucional.</p>
                </article>
                <article class="card">
                    <span class="tag">Inversion</span>
                    <strong>Proyectos y expedientes</strong>
                    <p>Registro de proyectos de inversion con tipologias y carga de documentos.</p>
                </article>
                <article class="card">
                    <span class="tag">Control</span>
                    <strong>Reportes y auditoria</strong>
                    <p>Exportables y trazabilidad de acciones para revision tecnica.</p>
                </article>
            </div>
        </section>
    </main>

    <footer class="shell">
        SIPeIP demo academico - Laravel MVC, PostgreSQL y Blade.
    </footer>
</body>
</html>
