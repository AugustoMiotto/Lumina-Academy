@extends('layouts.app')

@section('title', 'Meus Certificados e Badges — Lumina Academy')

@section('content')
    <section aria-labelledby="certs-heading">
        <h1 id="certs-heading" style="margin-bottom: 1rem;">Meus Certificados e Badges (RF11)</h1>

        @forelse($certificates as $certificate)
            <article class="card flex-between">
                <div>
                    <h2>{{ $certificate->course->title }}</h2>
                    <p>
                        <strong>Tipo:</strong> {{ strtoupper($certificate->type) }} |
                        <strong>Carga Horária:</strong> {{ $certificate->course->workload_hours }}h |
                        <strong>Mentor(a):</strong> {{ $certificate->course->mentor->name }}
                    </p>
                    <p style="font-size: 0.85rem;"><strong>Hash de Verificação:</strong> <code>{{ $certificate->hash_code }}</code></p>
                </div>
                <a href="{{ route('certificates.show', $certificate->hash_code) }}" class="btn">Visualizar Certificado</a>
            </article>
        @empty
            <div class="card">
                <p>Você ainda não possui certificados ou badges emitidos.</p>
            </div>
        @endforelse

        <div style="margin-top: 1rem;">
            {{ $certificates->links() }}
        </div>
    </section>
@endsection
