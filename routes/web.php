<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CoauthorshipController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OerMaterialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Guest & Mural Público OER - RF01, RF09, RF11)
|--------------------------------------------------------------------------
*/
Route::get('/', [OerMaterialController::class, 'index'])->name('home');
Route::get('/oer', [OerMaterialController::class, 'index'])->name('oer.index');
Route::get('/certificates/verify/{hashCode}', [CertificateController::class, 'show'])->name('certificates.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');
});

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Grupo de Mentores (role:mentor,admin)
    | RF02 (Credenciais), RF03 (Publicar Cursos), RF08 (Publicar OER), RF13 (Coautoria)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:mentor,admin')
        ->prefix('mentor')
        ->name('mentor.')
        ->group(function (): void {
            // RF02 - Submeter/atualizar credenciais de mentor para validação institucional
            Route::post('/credentials', [AuthController::class, 'submitMentorCredentials'])
                ->name('credentials.submit');

            // RF03 & RF06 - Publicar oferta de mentoria/curso com link de sessão virtual
            Route::get('/courses/create', [CourseController::class, 'create'])
                ->name('courses.create');
            Route::post('/courses', [CourseController::class, 'store'])
                ->name('courses.store');

            // RF08 - Publicar Recurso Educacional Aberto (OER)
            Route::get('/oer/create', [OerMaterialController::class, 'create'])
                ->name('oer.create');
            Route::post('/oer', [OerMaterialController::class, 'store'])
                ->name('oer.store');

            // RF13 - Registrar coautoria de projeto
            Route::post('/courses/{course}/coauthorships', [CoauthorshipController::class, 'store'])
                ->name('courses.coauthorships.store');

            // Conclusão de matrícula para habilitar emissão de certificado (RF11)
            Route::post('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])
                ->name('enrollments.complete');
        });

    /*
    |----------------------------------------------------------------------
    | Grupo de Aprendizes (role:aprendiz,mentor,admin)
    | RF04 (Buscar Cursos), RF05 (Inscrever-se), RF09 (Consultar OER),
    | RF10 (Avaliar Curso), RF11 (Emitir/Consultar Certificados)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:aprendiz,mentor,admin')->group(function (): void {
        // RF04 - Buscar ofertas de cursos com filtros avançados
        Route::get('/courses', [CourseController::class, 'index'])
            ->name('courses.index');
        Route::get('/courses/{course}', [CourseController::class, 'show'])
            ->whereNumber('course')
            ->name('courses.show');

        // RF05 - Inscrever-se em curso
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])
            ->whereNumber('course')
            ->name('courses.enroll');

        // RF10 - Avaliar e comentar curso
        Route::post('/courses/{course}/evaluations', [EvaluationController::class, 'store'])
            ->whereNumber('course')
            ->name('courses.evaluations.store');

        // RF11 - Emitir e listar certificados/badges
        Route::get('/certificates', [CertificateController::class, 'index'])
            ->name('certificates.index');
        Route::post('/courses/{course}/certificates', [CertificateController::class, 'store'])
            ->whereNumber('course')
            ->name('courses.certificates.store');
    });

    /*
    |----------------------------------------------------------------------
    | Grupo Administrativo / Instituição (role:admin)
    | RF02 - Validar credenciais de mentor
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function (): void {
            Route::post('/mentors/{mentor}/verify', [AuthController::class, 'verifyMentorCredentials'])
                ->name('mentors.verify');
        });
});
