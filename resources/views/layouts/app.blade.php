<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lumina Academy — Educação Aberta (ODS 4)')</title>
    <meta name="description" content="Plataforma de intercâmbio educacional aberta, leve e segura (ODS 4).">

    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0f3d91">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/pwa-register.js') }}" defer></script>
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
                <ul class="error-list">
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
