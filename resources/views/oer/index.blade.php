@extends('layouts.app')

@section('title', 'Repositório de Recursos Educacionais Abertos (OER) — Lumina Academy')

@section('content')
    <section aria-labelledby="oer-heading">
        <div class="flex-between mb-1">
            <h1 id="oer-heading">Repositório de Recursos Educacionais Abertos (OER)</h1>
            @auth
                @if(auth()->user()->isMentor() || auth()->user()->isAdmin())
                    <a href="{{ route('mentor.oer.create') }}" class="btn">+ Publicar Recurso OER</a>
                @endif
            @endauth
        </div>

        <form action="{{ route('oer.index') }}" method="GET" class="card" aria-label="Buscar materiais OER">
            <div class="flex-row">
                <div class="form-group mb-0">
                    <label for="search">Buscar por título ou tema</label>
                    <input type="text" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Apostilas, guias, artigos livres...">
                </div>
                <div class="align-end">
                    <button type="submit" class="btn">Buscar</button>
                </div>
            </div>
        </form>

        @forelse($materials as $material)
            <article class="card" aria-labelledby="oer-title-{{ $material->id }}">
                <div class="flex-between">
                    <h2 id="oer-title-{{ $material->id }}">{{ $material->title }}</h2>
                    @if($material->course)
                        <span class="badge">Curso: {{ $material->course->title }}</span>
                    @else
                        <span class="badge">Recurso Geral</span>
                    @endif
                </div>

                <p class="my-05">{{ $material->description }}</p>

                <div class="flex-between text-sm">
                    <span>
                        <strong>Publicado por:</strong> {{ $material->uploader->name }}
                        @if($material->uploader->verified_credentials)
                            <span class="badge">Mentor Verificado</span>
                        @endif
                    </span>
                    <a href="{{ $material->file_path_or_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline">Acessar / Baixar Material</a>
                </div>
            </article>
        @empty
            <div class="card">
                <p>Nenhum recurso educacional aberto encontrado.</p>
            </div>
        @endforelse

        <div class="mt-1">
            {{ $materials->links() }}
        </div>
    </section>
@endsection
