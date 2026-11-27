@extends('layouts.app')

@section('title', 'Validação de Certificado — Lumina Academy')

@section('content')
    <article class="card cert" aria-labelledby="cert-title">
        <span class="badge mb-075">ODS 4 — EDUCAÇÃO DE QUALIDADE</span>
        <h1 class="mb-1" id="cert-title">
            {{ $certificate->type === 'badge' ? 'Insígnia Digital (Badge)' : 'Certificado de Conclusão' }}
        </h1>

        <p class="text-lg mb-1">
            Certificamos que <strong>{{ $certificate->user->name }}</strong> concluiu com êxito a mentoria/curso
            <strong>"{{ $certificate->course->title }}"</strong>, com carga horária de
            <strong>{{ $certificate->course->workload_hours }} horas</strong>, ministrada por
            <strong>{{ $certificate->course->mentor->name }}</strong>.
        </p>

        <div class="cert-footer">
            <p><strong>Código de Autenticidade (Hash SHA-256):</strong> <code>{{ $certificate->hash_code }}</code></p>
            <p><strong>Emitido em:</strong> {{ $certificate->created_at?->format('d/m/Y H:i') }}</p>
        </div>
    </article>
@endsection
