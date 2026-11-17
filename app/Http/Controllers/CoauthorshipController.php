<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoauthorshipRequest;
use App\Models\Coauthorship;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;

class CoauthorshipController extends Controller
{
    /**
     * Registra reconhecimento mútuo de coautoria de projeto em um curso (RF13).
     */
    public function store(StoreCoauthorshipRequest $request, Course $course): RedirectResponse
    {
        $mentor = $request->user();

        if (! $mentor->isAdmin() && $course->mentor_id !== $mentor->id) {
            abort(403, 'Apenas o mentor responsável pelo curso pode registrar coautorias neste projeto.');
        }

        $validated = $request->validated();
        $coauthorId = (int) $validated['coauthor_id'];

        $isEnrolledStudent = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $coauthorId)
            ->exists();

        if (! $isEnrolledStudent) {
            return redirect()
                ->back()
                ->with('error', 'O coautor indicado precisa estar vinculado/inscrito neste curso.');
        }

        Coauthorship::updateOrCreate(
            [
                'course_id' => $course->id,
                'coauthor_id' => $coauthorId,
            ],
            [
                'mentor_id' => $course->mentor_id,
                'contribution_description' => $validated['contribution_description'],
            ]
        );

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Coautoria de projeto registrada com sucesso!');
    }
}
