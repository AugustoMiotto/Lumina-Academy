@extends('layouts.app')

@section('title', 'Publicar Curso ou Mentoria — Lumina Academy')

@section('content')
    <section aria-labelledby="create-course-heading">
        <div class="flex-between mb-1">
            <h1 id="create-course-heading">Publicar Nova Oferta de Curso / Mentoria</h1>
            <a href="{{ route('courses.index') }}" class="btn btn-outline">Voltar ao Catálogo</a>
        </div>

        <form action="{{ route('mentor.courses.store') }}" method="POST" class="card">
            @csrf

            <div class="form-group">
                <label for="title">Título da Oferta *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required minlength="5" maxlength="180" placeholder="Ex: Introdução à Lógica de Programação com Python">
            </div>

            <div class="flex-row">
                <div class="form-group">
                    <label for="category">Categoria *</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" required maxlength="100" placeholder="Ex: Tecnologia, Educação, Gestão...">
                </div>

                <div class="form-group">
                    <label for="language">Idioma *</label>
                    <select id="language" name="language" required>
                        <option value="pt-BR" @selected(old('language', 'pt-BR') === 'pt-BR')>Português (pt-BR)</option>
                        <option value="es" @selected(old('language') === 'es')>Espanhol (es)</option>
                        <option value="en" @selected(old('language') === 'en')>Inglês (en)</option>
                        <option value="fr" @selected(old('language') === 'fr')>Francês (fr)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="workload_hours">Carga Horária (horas) *</label>
                    <input type="number" id="workload_hours" name="workload_hours" value="{{ old('workload_hours', 20) }}" required min="1" max="1000">
                </div>

                <div class="form-group">
                    <label for="max_students">Limite de Vagas *</label>
                    <input type="number" id="max_students" name="max_students" value="{{ old('max_students', 30) }}" required min="1" max="500">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Descrição Detalhada *</label>
                <textarea id="description" name="description" rows="4" required minlength="20" placeholder="Descreva a ementa, objetivos e público-alvo...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="benefits">Benefícios para o Aprendiz (Opcional)</label>
                <textarea id="benefits" name="benefits" rows="2" placeholder="Competências práticas adquiridas ao final...">{{ old('benefits') }}</textarea>
            </div>

            <div class="form-group">
                <label for="meeting_link">Link da Sessão Virtual Síncrona — Meet/Zoom (HTTPS - RF06)</label>
                <input type="url" id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/xxx-xxxx-xxx">
            </div>

            <div class="mt-1">
                <button type="submit" class="btn">Publicar Oferta</button>
            </div>
        </form>
    </section>
@endsection
