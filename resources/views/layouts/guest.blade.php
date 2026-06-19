<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPeIP') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --ec-yellow: #fcd116;
                --ec-blue: #003893;
                --ec-red: #ce1126;
                --ink: #0f172a;
                --muted: #64748b;
            }

            .auth-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: minmax(0, 1.08fr) minmax(360px, 0.92fr);
                background:
                    radial-gradient(circle at 12% 12%, rgba(252, 209, 22, .28), transparent 28%),
                    radial-gradient(circle at 85% 18%, rgba(206, 17, 38, .18), transparent 24%),
                    linear-gradient(135deg, #f8fafc 0%, #eef4ff 52%, #fff7d6 100%);
                color: var(--ink);
                font-family: Figtree, ui-sans-serif, system-ui, sans-serif;
            }

            .auth-hero {
                position: relative;
                padding: clamp(32px, 6vw, 76px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: hidden;
            }

            .auth-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(90deg, var(--ec-yellow) 0 50%, var(--ec-blue) 50% 75%, var(--ec-red) 75% 100%);
                opacity: .12;
                clip-path: polygon(0 0, 78% 0, 54% 100%, 0 100%);
            }

            .brand-mark {
                position: relative;
                width: 74px;
                height: 74px;
                border-radius: 22px;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, var(--ec-yellow), #ffd84a 46%, var(--ec-blue) 47%, var(--ec-blue) 72%, var(--ec-red) 73%);
                color: white;
                font-weight: 800;
                letter-spacing: .04em;
                box-shadow: 0 18px 45px rgba(15, 23, 42, .18);
            }

            .auth-hero-content {
                position: relative;
                max-width: 720px;
            }

            .auth-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(255, 255, 255, .72);
                color: var(--ec-blue);
                font-size: 13px;
                font-weight: 800;
                letter-spacing: .08em;
                text-transform: uppercase;
                box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
            }

            .auth-title {
                margin: 26px 0 18px;
                font-size: clamp(40px, 6vw, 76px);
                line-height: .95;
                font-weight: 800;
                letter-spacing: 0;
            }

            .auth-title span {
                color: var(--ec-blue);
            }

            .auth-copy {
                max-width: 610px;
                color: #334155;
                font-size: clamp(17px, 2vw, 21px);
                line-height: 1.65;
            }

            .auth-highlights {
                position: relative;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
                max-width: 680px;
                margin-top: 36px;
            }

            .auth-highlight {
                padding: 18px;
                border-radius: 18px;
                background: rgba(255, 255, 255, .74);
                border: 1px solid rgba(148, 163, 184, .24);
                box-shadow: 0 16px 38px rgba(15, 23, 42, .08);
                backdrop-filter: blur(10px);
            }

            .auth-highlight strong {
                display: block;
                font-size: 22px;
                color: var(--ec-blue);
            }

            .auth-highlight span {
                display: block;
                margin-top: 6px;
                color: var(--muted);
                font-size: 13px;
                line-height: 1.35;
            }

            .auth-panel {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 28px;
                background: rgba(255, 255, 255, .72);
                border-left: 1px solid rgba(148, 163, 184, .24);
                backdrop-filter: blur(18px);
            }

            .auth-card {
                width: min(100%, 448px);
                border-radius: 28px;
                background: #ffffff;
                box-shadow: 0 28px 70px rgba(15, 23, 42, .16);
                border: 1px solid rgba(226, 232, 240, .9);
                overflow: hidden;
            }

            .flag-strip {
                height: 10px;
                background: linear-gradient(90deg, var(--ec-yellow) 0 50%, var(--ec-blue) 50% 75%, var(--ec-red) 75%);
            }

            .auth-card-body {
                padding: 32px;
            }

            @media (max-width: 920px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                }

                .auth-hero {
                    min-height: auto;
                    padding-bottom: 28px;
                }

                .auth-panel {
                    border-left: 0;
                    align-items: flex-start;
                    padding-top: 0;
                }
            }

            @media (max-width: 640px) {
                .auth-highlights {
                    grid-template-columns: 1fr;
                }

                .auth-card-body {
                    padding: 24px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-shell">
            <section class="auth-hero">
                <a href="/" class="brand-mark" aria-label="Inicio SIPeIP">SIP</a>

                <div class="auth-hero-content">
                    <div class="auth-eyebrow">Planificacion publica del Ecuador</div>
                    <h1 class="auth-title">Sistema <span>SIPeIP</span></h1>
                    <p class="auth-copy">
                        Gestiona planes, objetivos, alineaciones PND y ODS con trazabilidad institucional, seguridad y una experiencia pensada para trabajo publico serio.
                    </p>

                    <div class="auth-highlights">
                        <div class="auth-highlight"><strong>RBAC</strong><span>Acceso por roles y permisos institucionales.</span></div>
                        <div class="auth-highlight"><strong>PND</strong><span>Alineacion con objetivos nacionales.</span></div>
                        <div class="auth-highlight"><strong>ODS</strong><span>Relacion con Agenda 2030 y reportabilidad.</span></div>
                    </div>
                </div>

                <div style="position: relative; color: #64748b; font-size: 13px; font-weight: 600;">
                    Secretaria Nacional de Planificacion
                </div>
            </section>

            <main class="auth-panel">
                <div class="auth-card">
                    <div class="flag-strip"></div>
                    <div class="auth-card-body">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
