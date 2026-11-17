<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Lista e filtra ofertas de cursos e mentorias (RF04).
     * Previne N+1 com Eager Loading (with / withCount / withAvg) e paginação no servidor (15 por página).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));
        $language = trim((string) $request->query('language', ''));
        $maxWorkload = $request->query('max_workload');

        $courses = Course::query()
            ->with(['mentor:id,name,education_level,verified_credentials'])
            ->withCount('enrollments')
            ->withAvg('evaluations', 'rating')
            ->where('status', Course::STATUS_PUBLISHED)
            ->when($search !== '', function (Builder $query) use ($search): void {
                $escapedSearch = str_replace(['%', '_'], ['\%', '\_'], $search);
                $query->where(function (Builder $subQuery) use ($escapedSearch): void {
                    $subQuery->where('title', 'like', "%{$escapedSearch}%")
                        ->orWhere('description', 'like', "%{$escapedSearch}%")
                        ->orWhere('benefits', 'like', "%{$escapedSearch}%");
                });
            })
            ->when($category !== '', function (Builder $query) use ($category): void {
                $query->where('category', $category);
            })
            ->when($language !== '', function (Builder $query) use ($language): void {
                $query->where('language', $language);
            })
            ->when(is_numeric($maxWorkload) && (int) $maxWorkload > 0, function (Builder $query) use ($maxWorkload): void {
                $query->where('workload_hours', '<=', (int) $maxWorkload);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('courses.index', [
            'courses' => $courses,
            'filters' => [
                'search' => $search,
                'category' => $category,
                'language' => $language,
                'max_workload' => $maxWorkload,
            ],
        ]);
    }

    /**
     * Exibe o formulário para publicação de nova oferta de curso/mentoria (RF03).
     */
    public function create(): View
    {
        return view('courses.create');
    }

    /**
     * Persiste uma nova oferta de curso/mentoria (RF03 e RF06).
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $course = Course::create([
            'mentor_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'benefits' => $validated['benefits'] ?? null,
            'workload_hours' => (int) $validated['workload_hours'],
            'language' => $validated['language'],
            'category' => $validated['category'],
            'max_students' => (int) $validated['max_students'],
            'meeting_link' => $validated['meeting_link'] ?? null,
            'status' => $validated['status'] ?? Course::STATUS_PUBLISHED,
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Oferta de curso/mentoria publicada com sucesso!');
    }

    /**
     * Exibe os detalhes do curso, sessão virtual (RF06), materiais OER e avaliações.
     */
    public function show(Course $course): View
    {
        $course->load([
            'mentor:id,name,education_level,bio,verified_credentials',
            'oerMaterials' => fn ($query) => $query->latest(),
            'evaluations.user:id,name',
            'coauthorships.coauthor:id,name,education_level',
        ])->loadCount('enrollments');

        return view('courses.show', [
            'course' => $course,
        ]);
    }
}
