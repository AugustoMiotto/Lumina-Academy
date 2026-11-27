@extends('layouts.app')

@section('title', 'Publicar Recurso OER — Lumina Academy')

@section('content')
    <section aria-labelledby="create-oer-heading">
        <div class="flex-between mb-1">
            <h1 id="create-oer-heading">Publicar Recurso Educacional Aberto (OER)</h1>
            <a href="{{ route('oer.index') }}" class="btn btn-outline">Voltar ao Mural OER</a>
        </div>

        <form action="{{ route('mentor.oer.store') }}" method="POST" class="card">
            @csrf

            <div class="form-group">
                <label for="title">Título do Material *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required minlength="3" maxlength="180">
            </div>

            <div class="form-group">
                <label for="description">Descrição Pedagógica *</label>
                <textarea id="description" name="description" rows="3" required minlength="10">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="file_path_or_url">URL ou Caminho do Arquivo *</label>
                <input type="text" id="file_path_or_url" name="file_path_or_url" value="{{ old('file_path_or_url') }}" required placeholder="https://...">
            </div>

            <div class="form-group">
                <label for="course_id">ID do Curso Associado (Opcional)</label>
                <input type="number" id="course_id" name="course_id" value="{{ old('course_id') }}" min="1">
            </div>

            <button type="submit" class="btn">Publicar OER</button>
        </form>
    </section>
@endsection
