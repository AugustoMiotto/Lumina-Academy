<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Evaluation;
use Illuminate\Http\RedirectResponse;

class EvaluationController extends Controller
{
    /**
     * Registra a avaliação e comentário de um aluno em um curso (RF10).
     * Garante que apenas alunos com matrícula ativa ('enrolled') ou concluída ('completed')
     * possam avaliar o curso e evita avaliações duplicadas.
     */
    public function store(StoreEvaluationRequest $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        $hasValidEnrollment = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->whereIn('status', [Enrollment::STATUS_ENROLLED, Enrollment::STATUS_COMPLETED])
            ->exists();

        if (! $hasValidEnrollment) {
            return redirect()
                ->back()
                ->with('error', 'Apenas aprendizes com matrícula ativa ou concluída neste curso podem avaliá-lo.');
        }

        $alreadyEvaluated = Evaluation::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyEvaluated) {
            return redirect()
                ->back()
                ->with('error', 'Você já enviou uma avaliação para este curso.');
        }

        $validated = $request->validated();

        Evaluation::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'rating' => (int) $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'professional_utility' => (bool) $validated['professional_utility'],
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Sua avaliação foi registrada com sucesso! Obrigado pelo feedback.');
    }
}
