@extends('layouts.app')

@section('title', 'Entrar — Lumina Academy')

@section('content')
    <section class="narrow" aria-labelledby="login-heading">
        <h1 class="mb-1" id="login-heading">Acessar Conta</h1>

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

            <div class="my-075">
                <label class="label-inline">
                    <input type="checkbox" name="remember" value="1"> Manter conectado neste dispositivo
                </label>
            </div>

            <button type="submit" class="btn w-full">Entrar</button>
        </form>
    </section>
@endsection
