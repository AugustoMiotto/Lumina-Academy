@extends('layouts.app')

@section('title', 'Criar Conta — Lumina Academy')

@section('content')
    <section style="max-width: 600px; margin: 0 auto;" aria-labelledby="register-heading">
        <h1 id="register-heading" style="margin-bottom: 1rem;">Criar Conta na Lumina Academy</h1>

        <form action="{{ route('register.store') }}" method="POST" class="card">
            @csrf

            <div class="form-group">
                <label for="name">Nome Completo *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255">
            </div>

            <div class="flex-row">
                <div class="form-group">
                    <label for="role">Perfil de Participação *</label>
                    <select id="role" name="role" required>
                        <option value="aprendiz" @selected(old('role', 'aprendiz') === 'aprendiz')>Aprendiz (Estudante)</option>
                        <option value="mentor" @selected(old('role') === 'mentor')>Mentor(a) / Educador(a)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="education_level">Nível Educacional / Instituição</label>
                    <input type="text" id="education_level" name="education_level" value="{{ old('education_level') }}" maxlength="100" placeholder="Ex: Graduação, Mestrado, Técnico...">
                </div>
            </div>

            <div class="form-group">
                <label for="bio">Biografia Resumida / Área de Atuação</label>
                <textarea id="bio" name="bio" rows="3" maxlength="1000">{{ old('bio') }}</textarea>
            </div>

            <div class="flex-row">
                <div class="form-group">
                    <label for="password">Senha (mín. 8 caracteres) *</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%; margin-top: 0.5rem;">Concluir Cadastro</button>
        </form>
    </section>
@endsection
