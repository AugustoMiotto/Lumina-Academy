@extends('layouts.app')

@section('title', 'Validação de Certificado — Lumina Academy')

@section('content')
    <article class="card" style="padding: 2rem; text-align: center;" aria-labelledby="cert-title">
        <span class="badge" style="margin-bottom: 0.75rem;">ODS 4 — EDUCAÇÃO DE QUALIDADE</span>
        <h1 id="cert-title" style="margin-bottom: 1rem;">
            {{ $certificate->type === 'badge' ? 'Insígnia Digital (Badge)' : 'Certificado de Conclusão' }}
        </h1>

        <p style="font-size: 1.1rem; margin-bottom: 1rem;">
            Certificamos que <strong>{{ $certificate->user->name }}</strong> concluiu com êxito a mentoria/curso
            <strong>"{{ $certificate->course->title }}"</strong>, com carga horária de
            <strong>{{ $certificate->course->workload_hours }} horas</strong>, ministrada por
            <strong>{{ $certificate->course->mentor->name }}</strong>.
        </p>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid var(--border-color); font-size: 0.9rem;">
            <p><strong>Código de Autenticidade (Hash SHA-256):</strong> <code>{{ $certificate->hash_code }}</code></p>
            <p><strong>Emitido em:</strong> {{ $certificate->created_at?->format('d/m/Y H:i') }}</p>
        </div>
    </article>
@endsection
