@extends('layouts.app')

@section('title', 'Entrar — Lumina Academy')

@section('content')
    <section style="max-width: 480px; margin: 0 auto;" aria-labelledby="login-heading">
        <h1 id="login-heading" style="margin-bottom: 1rem;">Acessar Conta</h1>

        <form action="{{ route('login.authenticate') }}" method="POST" class="card">
            @csrf

            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Senha *</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div style="margin: 0.75rem 0;">
                <label style="font-weight: 400;">
                    <input type="checkbox" name="remember" value="1"> Manter conectado neste dispositivo
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Entrar</button>
        </form>
    </section>
@endsection
