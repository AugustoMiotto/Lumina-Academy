<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Realiza a inscrição de um aprendiz em um curso (RF05).
     * Garante atomicamente que:
     * 1. O curso está aberto para inscrições.
     * 2. O usuário não é o próprio mentor do curso.
     * 3. O aluno ainda não está inscrito no curso.
     * 4. Há vagas disponíveis (enrollments < max_students).
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        if ($course->mentor_id === $user->id) {
            return redirect()
                ->back()
                ->with('error', 'O mentor responsável não pode se inscrever como aprendiz na própria oferta.');
        }

        return DB::transaction(function () use ($course, $user): RedirectResponse {
            /** @var Course $lockedCourse */
            $lockedCourse = Course::query()
                ->whereKey($course->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedCourse->status !== Course::STATUS_PUBLISHED) {
                return redirect()
                    ->back()
                    ->with('error', 'Este curso não está aberto para novas inscrições no momento.');
            }

            $alreadyEnrolled = Enrollment::query()
                ->where('course_id', $lockedCourse->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyEnrolled) {
                return redirect()
                    ->back()
                    ->with('error', 'Você já possui uma inscrição registrada neste curso.');
            }

            $currentEnrollmentsCount = Enrollment::query()
                ->where('course_id', $lockedCourse->id)
                ->count();

            if ($currentEnrollmentsCount >= $lockedCourse->max_students) {
                return redirect()
                    ->back()
                    ->with('error', 'Não há mais vagas disponíveis para esta oferta de mentoria/curso.');
            }

            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $lockedCourse->id,
                'status' => Enrollment::STATUS_ENROLLED,
            ]);

            return redirect()
                ->route('courses.show', $lockedCourse)
                ->with('success', 'Inscrição realizada com sucesso! Bons estudos.');
        });
    }

    /**
     * Marca a matrícula de um aprendiz como concluída pelo mentor (habilitando RF11).
     */
    public function complete(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->load('course:id,mentor_id');
        $user = $request->user();

        if (! $user->isAdmin() && $enrollment->course->mentor_id !== $user->id) {
            abort(403, 'Apenas o mentor responsável pelo curso pode concluir matrículas.');
        }

        $enrollment->update([
            'status' => Enrollment::STATUS_COMPLETED,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Matrícula marcada como concluída com sucesso.');
    }
}
