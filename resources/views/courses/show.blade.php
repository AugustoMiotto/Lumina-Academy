@extends('layouts.app')

@section('title', $course->title . ' — Lumina Academy')

@section('content')
    <article aria-labelledby="course-heading">
        <div class="card">
            <div class="flex-between">
                <h1 id="course-heading">{{ $course->title }}</h1>
                <div>
                    <span class="badge">{{ $course->category }}</span>
                    <span class="badge">{{ $course->language }}</span>
                    <span class="badge">{{ $course->workload_hours }}h</span>
                </div>
            </div>

            <p style="margin: 1rem 0;">{{ $course->description }}</p>

            @if($course->benefits)
                <p style="margin-bottom: 1rem;"><strong>Benefícios:</strong> {{ $course->benefits }}</p>
            @endif

            <div class="flex-between" style="border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                <div>
                    <strong>Mentor(a):</strong> {{ $course->mentor->name }}
                    @if($course->mentor->education_level)
                        ({{ $course->mentor->education_level }})
                    @endif
                    @if($course->mentor->verified_credentials)
                        <span class="badge">Credencial Institucional Verificada</span>
                    @endif
                    <br>
                    <strong>Inscritos:</strong> {{ $course->enrollments_count }} / {{ $course->max_students }} vagas
                </div>

                @if($course->enrollments_count < $course->max_students && $course->mentor_id !== auth()->id())
                    <form action="{{ route('courses.enroll', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn">Inscrever-se neste Curso</button>
                    </form>
                @endif
            </div>

            {{-- RF06 - Link da Sessão Virtual --}}
            @if($course->meeting_link)
                <div style="margin-top: 1rem; padding: 0.75rem; background-color: var(--bg-surface); border: 1px solid var(--border-color);">
                    <strong>Sessão Virtual Síncrona (RF06):</strong>
                    <a href="{{ $course->meeting_link }}" target="_blank" rel="noopener noreferrer">{{ $course->meeting_link }}</a>
                </div>
            @endif

            {{-- RF11 - Solicitar emissão de certificado (se concluído) --}}
            <div style="margin-top: 1rem;" class="flex-row">
                <form action="{{ route('courses.certificates.store', $course) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="certificate">
                    <button type="submit" class="btn btn-outline">Emitir Certificado de Conclusão (RF11)</button>
                </form>
            </div>
        </div>

        {{-- RF08 / RF09 - Materiais Educacionais Abertos (OER) do Curso --}}
        <section class="card" aria-labelledby="oer-course-heading">
            <h2 id="oer-course-heading" style="margin-bottom: 0.75rem;">Materiais Educacionais Abertos (OER) do Curso</h2>
            @forelse($course->oerMaterials as $material)
                <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                    <strong>{{ $material->title }}</strong> — {{ $material->description }}
                    <div>
                        <a href="{{ $material->file_path_or_url }}" target="_blank" rel="noopener noreferrer">Acessar Recurso</a>
                    </div>
                </div>
            @empty
                <p>Nenhum material OER específico vinculado a este curso até o momento.</p>
            @endforelse
        </section>

        {{-- RF13 - Coautorias de Projetos Reconhecidas --}}
        <section class="card" aria-labelledby="coauthorship-heading">
            <h2 id="coauthorship-heading" style="margin-bottom: 0.75rem;">Reconhecimento Mútuo de Coautoria (RF13)</h2>
            @forelse($course->coauthorships as $coauthorship)
                <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                    <strong>Coautor(a):</strong> {{ $coauthorship->coauthor->name }}
                    <p>{{ $coauthorship->contribution_description }}</p>
                </div>
            @empty
                <p>Nenhuma coautoria registrada neste curso ainda.</p>
            @endforelse

            @if(auth()->id() === $course->mentor_id || auth()->user()->isAdmin())
                <form action="{{ route('mentor.courses.coauthorships.store', $course) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <h3>Registrar Coautoria de Aprendiz</h3>
                    <div class="flex-row">
                        <div class="form-group">
                            <label for="coauthor_id">ID do Aprendiz Coautor *</label>
                            <input type="number" id="coauthor_id" name="coauthor_id" required min="1">
                        </div>
                        <div class="form-group">
                            <label for="contribution_description">Descrição da Contribuição no Projeto *</label>
                            <input type="text" id="contribution_description" name="contribution_description" required minlength="15" placeholder="Descreva a entrega conjunta...">
                        </div>
                    </div>
                    <button type="submit" class="btn">Registrar Coautoria</button>
                </form>
            @endif
        </section>

        {{-- RF10 - Avaliações e Comentários --}}
        <section class="card" aria-labelledby="evaluations-heading">
            <h2 id="evaluations-heading" style="margin-bottom: 0.75rem;">Avaliações do Curso (RF10)</h2>

            <form action="{{ route('courses.evaluations.store', $course) }}" method="POST" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                @csrf
                <div class="flex-row">
                    <div class="form-group">
                        <label for="rating">Nota (1 a 5 estrelas) *</label>
                        <select id="rating" name="rating" required>
                            <option value="5">5 — Excelente</option>
                            <option value="4">4 — Muito Bom</option>
                            <option value="3">3 — Bom</option>
                            <option value="2">2 — Regular</option>
                            <option value="1">1 — Insuficiente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="professional_utility">Este curso teve utilidade prática/profissional? *</label>
                        <select id="professional_utility" name="professional_utility" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment">Comentário (Opcional)</label>
                    <textarea id="comment" name="comment" rows="2" maxlength="1000" placeholder="Compartilhe sua experiência com a comunidade..."></textarea>
                </div>

                <button type="submit" class="btn">Enviar Avaliação</button>
            </form>

            @forelse($course->evaluations as $evaluation)
                <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                    <div class="flex-between">
                        <strong>{{ $evaluation->user->name }}</strong>
                        <span class="badge">Nota: {{ $evaluation->rating }}/5</span>
                    </div>
                    @if($evaluation->comment)
                        <p style="margin-top: 0.25rem;">{{ $evaluation->comment }}</p>
                    @endif
                </div>
            @empty
                <p>Este curso ainda não possui avaliações.</p>
            @endforelse
        </section>
    </article>
@endsection
