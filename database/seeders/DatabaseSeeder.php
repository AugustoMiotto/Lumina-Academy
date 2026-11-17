<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Coauthorship;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\OerMaterial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with MVP demonstration data (ODS 4).
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@lumina.org'],
            [
                'name' => 'Coordenação Lumina (Admin)',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'education_level' => 'Gestão Institucional ODS 4',
                'bio' => 'Equipe responsável pela validação de credenciais docentes.',
                'verified_credentials' => true,
            ]
        );

        $mentor = User::updateOrCreate(
            ['email' => 'mentor@lumina.org'],
            [
                'name' => 'Profa. Dra. Amara Diallo',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_MENTOR,
                'education_level' => 'Doutorado em Engenharia de Software — Universidade Parceira',
                'bio' => 'Pesquisadora em tecnologias educacionais de baixo consumo de banda e software livre.',
                'verified_credentials' => true,
            ]
        );

        $aprendiz = User::updateOrCreate(
            ['email' => 'aprendiz@lumina.org'],
            [
                'name' => 'Lucas Nascimento',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_APRENDIZ,
                'education_level' => 'Ensino Médio Técnico',
                'bio' => 'Estudante interessado em desenvolvimento web acessível e impacto comunitário.',
                'verified_credentials' => false,
            ]
        );

        $course = Course::updateOrCreate(
            ['title' => 'Desenvolvimento Web Sustentável para Conexões Limitadas'],
            [
                'mentor_id' => $mentor->id,
                'description' => 'Curso prático focado em arquitetura SSR, HTML5 semântico, otimização de banco de dados e segurança OWASP para regiões em desenvolvimento.',
                'benefits' => 'Capacitação para construir sistemas públicos e comunitários resilientes e ultrarrápidos.',
                'workload_hours' => 40,
                'language' => 'pt-BR',
                'category' => 'Tecnologia',
                'max_students' => 25,
                'meeting_link' => 'https://meet.google.com/lum-ina-ods4',
                'status' => Course::STATUS_PUBLISHED,
            ]
        );

        Enrollment::updateOrCreate(
            [
                'user_id' => $aprendiz->id,
                'course_id' => $course->id,
            ],
            [
                'status' => Enrollment::STATUS_COMPLETED,
            ]
        );

        OerMaterial::updateOrCreate(
            ['title' => 'Guia Prático de Performance Web Low-Bandwidth (PDF Aberto)'],
            [
                'uploader_id' => $mentor->id,
                'course_id' => $course->id,
                'description' => 'Cartilha aberta sob licença Creative Commons com padrões de compressão, SSR e acessibilidade.',
                'file_path_or_url' => 'https://unesdoc.unesco.org/',
                'public' => true,
            ]
        );

        Evaluation::updateOrCreate(
            [
                'course_id' => $course->id,
                'user_id' => $aprendiz->id,
            ],
            [
                'rating' => 5,
                'comment' => 'Mentoria transformadora! Consegui aplicar os conceitos em um projeto local da minha comunidade.',
                'professional_utility' => true,
            ]
        );

        Coauthorship::updateOrCreate(
            [
                'course_id' => $course->id,
                'coauthor_id' => $aprendiz->id,
            ],
            [
                'mentor_id' => $mentor->id,
                'contribution_description' => 'Coautoria no desenvolvimento dos templates acessíveis de alto contraste para o repositório comunitário.',
            ]
        );

        Certificate::updateOrCreate(
            [
                'user_id' => $aprendiz->id,
                'course_id' => $course->id,
                'type' => Certificate::TYPE_CERTIFICATE,
            ],
            [
                'hash_code' => hash('sha256', 'lumina-mvp-cert-' . $aprendiz->id . '-' . $course->id),
            ]
        );
    }
}
