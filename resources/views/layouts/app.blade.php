<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lumina Academy — Educação Aberta (ODS 4)')</title>
    <style>
        /* CSS Minimalista, Alto Contraste (WCAG AAA) e Zero Animações Pesadas (Low-Bandwidth) */
        :root {
            --bg-page: #ffffff;
            --bg-surface: #f4f6f8;
            --text-primary: #111827;
            --text-secondary: #374151;
            --border-color: #1f2937;
            --accent-primary: #0f3d91;
            --accent-hover: #09275e;
            --success-bg: #e6f4ea;
            --success-border: #137333;
            --success-text: #0d5224;
            --error-bg: #fce8e6;
            --error-border: #c5221f;
            --error-text: #8c1513;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            color: var(--accent-primary);
            text-decoration: underline;
            font-weight: 600;
        }

        a:hover, a:focus {
            color: var(--accent-hover);
            outline: 2px solid var(--accent-primary);
            outline-offset: 2px;
        }

        .skip-link {
            position: absolute;
            left: -9999px;
            top: 0;
            background: var(--accent-primary);
            color: #ffffff;
            padding: 0.5rem 1rem;
            z-index: 100;
        }

        .skip-link:focus {
            left: 0.5rem;
            top: 0.5rem;
        }

        .container {
            width: 100%;
            max-width: 1024px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header & Navegação */
        header {
            background-color: var(--bg-surface);
            border-bottom: 2px solid var(--border-color);
            padding: 1rem 0;
        }

        .header-inner {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }

        /* Main */
        main {
            flex: 1;
            padding: 1.5rem 0;
        }

        /* Componentes Utilitários e Formulários */
        .card {
            background-color: var(--bg-page);
            border: 2px solid var(--border-color);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .flex-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .flex-between {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
            flex: 1 1 200px;
        }

        label {
            font-weight: 700;
            font-size: 0.95rem;
        }

        input, select, textarea {
            font: inherit;
            padding: 0.5rem;
            border: 2px solid var(--border-color);
            background-color: #ffffff;
            color: var(--text-primary);
        }

        input:focus, select:focus, textarea:focus {
            outline: 3px solid var(--accent-primary);
            outline-offset: 1px;
        }

        .btn {
            display: inline-block;
            font: inherit;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border: 2px solid var(--accent-primary);
            background-color: var(--accent-primary);
            color: #ffffff;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover, .btn:focus {
            background-color: var(--accent-hover);
            color: #ffffff;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--accent-primary);
        }

        /* Flash Alerts */
        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border: 2px solid;
            font-weight: 600;
        }

        .alert-success {
            background-color: var(--success-bg);
            border-color: var(--success-border);
            color: var(--success-text);
        }

        .alert-error {
            background-color: var(--error-bg);
            border-color: var(--error-border);
            color: var(--error-text);
        }

        .badge {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid var(--border-color);
            background-color: var(--bg-surface);
        }

        /* Footer */
        footer {
            background-color: var(--bg-surface);
            border-top: 2px solid var(--border-color);
            padding: 1rem 0;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>

    <header role="banner">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand">Lumina Academy (ODS 4)</a>

            <nav aria-label="Navegação Principal">
                <ul>
                    <li><a href="{{ route('oer.index') }}">Mural OER</a></li>
                    @auth
                        <li><a href="{{ route('courses.index') }}">Cursos &amp; Mentorias</a></li>
                        <li><a href="{{ route('certificates.index') }}">Meus Certificados</a></li>

                        @if(auth()->user()->isMentor() || auth()->user()->isAdmin())
                            <li><a href="{{ route('mentor.courses.create') }}">Publicar Curso</a></li>
                            <li><a href="{{ route('mentor.oer.create') }}">Publicar OER</a></li>
                        @endif

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline">Sair ({{ auth()->user()->name }})</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Entrar</a></li>
                        <li><a href="{{ route('register') }}" class="btn">Criar Conta</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-content" class="container" role="main">
        @if(session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <p><strong>Por favor, corrija os erros abaixo:</strong></p>
                <ul style="margin-left: 1.25rem; margin-top: 0.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer role="contentinfo">
        <div class="container flex-between">
            <p>&copy; {{ date('Y') }} Lumina Academy — Plataforma Educacional Aberta (ODS 4).</p>
            <p>Interface otimizada para conexões de baixa largura de banda (Zero JS Bloqueante).</p>
        </div>
    </footer>
</body>
</html>
