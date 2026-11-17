<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LuminaMvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_oer_repository_loads_successfully(): void
    {
        $response = $this->get(route('oer.index'));
        $response->assertStatus(200);
        $response->assertSee('Repositório de Recursos Educacionais Abertos (OER)');
    }

    public function test_verified_mentor_can_publish_course_and_unverified_cannot(): void
    {
        $unverifiedMentor = User::factory()->create([
            'role' => User::ROLE_MENTOR,
            'verified_credentials' => false,
        ]);

        $verifiedMentor = User::factory()->verifiedMentor()->create();

        $payload = [
            'title' => 'Arquitetura Web para Regiões Remotas',
            'description' => 'Curso completo sobre otimização extrema de banda e segurança web.',
            'benefits' => 'Domínio de SSR e boas práticas OWASP.',
            'workload_hours' => 30,
            'language' => 'pt-BR',
            'category' => 'Tecnologia',
            'max_students' => 2,
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ];

        $this->actingAs($unverifiedMentor)
            ->post(route('mentor.courses.store'), $payload)
            ->assertStatus(403);

        $this->actingAs($verifiedMentor)
            ->post(route('mentor.courses.store'), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('courses', [
            'title' => 'Arquitetura Web para Regiões Remotas',
            'mentor_id' => $verifiedMentor->id,
        ]);
    }

    public function test_student_enrollment_respects_vacancy_limits_and_prevents_duplicates(): void
    {
        $mentor = User::factory()->verifiedMentor()->create();
        $student1 = User::factory()->create(['role' => User::ROLE_APRENDIZ]);
        $student2 = User::factory()->create(['role' => User::ROLE_APRENDIZ]);

        $course = Course::create([
            'mentor_id' => $mentor->id,
            'title' => 'Mentoria com 1 Vaga',
            'description' => 'Descrição detalhada com mais de vinte caracteres.',
            'workload_hours' => 10,
            'language' => 'pt-BR',
            'category' => 'Educação',
            'max_students' => 1,
            'status' => Course::STATUS_PUBLISHED,
        ]);

        // Primeira inscrição deve ter sucesso
        $this->actingAs($student1)
            ->post(route('courses.enroll', $course))
            ->assertSessionHas('success');

        // Tentativa duplicada do mesmo aluno deve falhar
        $this->actingAs($student1)
            ->post(route('courses.enroll', $course))
            ->assertSessionHas('error');

        // Segundo aluno não consegue vaga pois max_students = 1
        $this->actingAs($student2)
            ->post(route('courses.enroll', $course))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_only_enrolled_or_completed_students_can_evaluate_course(): void
    {
        $mentor = User::factory()->verifiedMentor()->create();
        $enrolledStudent = User::factory()->create(['role' => User::ROLE_APRENDIZ]);
        $outsiderStudent = User::factory()->create(['role' => User::ROLE_APRENDIZ]);

        $course = Course::create([
            'mentor_id' => $mentor->id,
            'title' => 'Curso de Teste de Avaliação',
            'description' => 'Descrição detalhada com mais de vinte caracteres.',
            'workload_hours' => 15,
            'language' => 'pt-BR',
            'category' => 'Gestão',
            'max_students' => 10,
            'status' => Course::STATUS_PUBLISHED,
        ]);

        Enrollment::create([
            'user_id' => $enrolledStudent->id,
            'course_id' => $course->id,
            'status' => Enrollment::STATUS_ENROLLED,
        ]);

        // Aluno não matriculado é bloqueado
        $this->actingAs($outsiderStudent)
            ->post(route('courses.evaluations.store', $course), [
                'rating' => 5,
                'comment' => 'Tentando avaliar sem matrícula',
                'professional_utility' => true,
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('evaluations', 0);

        // Aluno matriculado consegue avaliar
        $this->actingAs($enrolledStudent)
            ->post(route('courses.evaluations.store', $course), [
                'rating' => 5,
                'comment' => 'Excelente curso!',
                'professional_utility' => true,
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseCount('evaluations', 1);
    }
}
