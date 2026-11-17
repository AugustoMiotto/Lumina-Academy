<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Lista os certificados e badges do aprendiz autenticado (RF11) com paginação e Eager Loading.
     */
    public function index(Request $request): View
    {
        $certificates = Certificate::query()
            ->with(['course:id,title,workload_hours,mentor_id', 'course.mentor:id,name'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('certificates.index', [
            'certificates' => $certificates,
        ]);
    }

    /**
     * Emite certificado e badge para um aluno com matrícula concluída (RF11).
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        $completedEnrollment = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->exists();

        if (! $completedEnrollment) {
            return redirect()
                ->back()
                ->with('error', 'O certificado só pode ser emitido após a conclusão do curso.');
        }

        $type = $request->input('type') === Certificate::TYPE_BADGE
            ? Certificate::TYPE_BADGE
            : Certificate::TYPE_CERTIFICATE;

        $certificate = Certificate::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'type' => $type,
            ],
            [
                'hash_code' => hash('sha256', sprintf('%d-%d-%s-%s', $user->id, $course->id, $type, bin2hex(random_bytes(16)))),
            ]
        );

        return redirect()
            ->route('certificates.show', $certificate->hash_code)
            ->with('success', 'Certificado emitido com sucesso!');
    }

    /**
     * Exibe e valida publicamente um certificado/badge pelo hash único (RF11).
     */
    public function show(string $hashCode): View
    {
        $certificate = Certificate::query()
            ->with([
                'user:id,name,education_level',
                'course:id,title,workload_hours,category,mentor_id',
                'course.mentor:id,name,education_level,verified_credentials',
            ])
            ->where('hash_code', $hashCode)
            ->firstOrFail();

        return view('certificates.show', [
            'certificate' => $certificate,
        ]);
    }
}
