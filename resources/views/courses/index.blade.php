@extends('layouts.app')

@section('title', 'Catálogo de Cursos e Mentorias — Lumina Academy')

@section('content')
    <section aria-labelledby="catalog-heading">
        <div class="flex-between mb-1">
            <h1 id="catalog-heading">Ofertas de Cursos e Mentorias</h1>
            @if(auth()->user()->isMentor() || auth()->user()->isAdmin())
                <a href="{{ route('mentor.courses.create') }}" class="btn">+ Nova Oferta</a>
            @endif
        </div>

        {{-- RF04 - Formulário de Busca e Filtros (SSR Tradicional) --}}
        <form action="{{ route('courses.index') }}" method="GET" class="card" aria-label="Filtros de busca de cursos">
            <div class="flex-row">
                <div class="form-group">
                    <label for="search">Palavra-chave</label>
                    <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Título, tema ou benefício...">
                </div>

                <div class="form-group">
                    <label for="category">Categoria</label>
                    <input type="text" id="category" name="category" value="{{ $filters['category'] ?? '' }}" placeholder="Ex: Tecnologia, Saúde...">
                </div>

                <div class="form-group">
                    <label for="language">Idioma</label>
                    <select id="language" name="language">
                        <option value="">Todos</option>
                        <option value="pt-BR" @selected(($filters['language'] ?? '') === 'pt-BR')>Português (pt-BR)</option>
                        <option value="es" @selected(($filters['language'] ?? '') === 'es')>Espanhol (es)</option>
                        <option value="en" @selected(($filters['language'] ?? '') === 'en')>Inglês (en)</option>
                        <option value="fr" @selected(($filters['language'] ?? '') === 'fr')>Francês (fr)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="max_workload">Carga Horária Máx. (h)</label>
                    <input type="number" id="max_workload" name="max_workload" min="1" value="{{ $filters['max_workload'] ?? '' }}" placeholder="Ex: 40">
                </div>
            </div>

            <div class="flex-row mt-05">
                <button type="submit" class="btn">Filtrar Ofertas</button>
                <a href="{{ route('courses.index') }}" class="btn btn-outline">Limpar Filtros</a>
            </div>
        </form>

        {{-- Listagem Paginada de Cursos --}}
        @forelse($courses as $course)
            <article class="card" aria-labelledby="course-title-{{ $course->id }}">
                <div class="flex-between">
                    <h2 id="course-title-{{ $course->id }}">
                        <a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a>
                    </h2>
                    <div>
                        <span class="badge">{{ $course->category }}</span>
                        <span class="badge">{{ $course->language }}</span>
                        <span class="badge">{{ $course->workload_hours }}h</span>
                    </div>
                </div>

                <p class="my-05">{{ $course->description }}</p>

                <div class="flex-between mt-1 text-sm">
                    <div>
                        <strong>Mentor(a):</strong> {{ $course->mentor->name }}
                        @if($course->mentor->verified_credentials)
                            <span class="badge" title="Credencial Institucional Verificada">Verificado</span>
                        @endif
                        | <strong>Vagas ocupadas:</strong> {{ $course->enrollments_count }} / {{ $course->max_students }}
                        @if($course->evaluations_avg_rating)
                            | <strong>Avaliação:</strong> {{ number_format((float) $course->evaluations_avg_rating, 1) }}/5.0
                        @endif
                    </div>

                    {{-- RF05 - Formulário POST nativo para inscrição com proteção CSRF --}}
                    @if($course->enrollments_count < $course->max_students && $course->mentor_id !== auth()->id())
                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">Inscrever-se</button>
                        </form>
                    @elseif($course->enrollments_count >= $course->max_students)
                        <span class="badge">Vagas Esgotadas</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="card">
                <p>Nenhuma oferta de curso ou mentoria encontrada para os filtros informados.</p>
            </div>
        @endforelse

        {{-- Paginação Servidor do Laravel --}}
        <div class="mt-1">
            {{ $courses->links() }}
        </div>
    </section>
@endsection
