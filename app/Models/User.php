<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MENTOR = 'mentor';
    public const ROLE_APRENDIZ = 'aprendiz';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'education_level',
        'bio',
        'verified_credentials',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified_credentials' => 'boolean',
        ];
    }

    /**
     * Cursos e mentorias ofertados por este usuário (quando Mentor).
     */
    public function mentoredCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'mentor_id');
    }

    /**
     * Matrículas realizadas por este usuário (quando Aprendiz).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /**
     * Cursos nos quais este usuário está matriculado.
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Materiais OER publicados por este usuário (RF08).
     */
    public function oerMaterials(): HasMany
    {
        return $this->hasMany(OerMaterial::class, 'uploader_id');
    }

    /**
     * Avaliações de cursos feitas por este usuário (RF10).
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'user_id');
    }

    /**
     * Certificados e badges emitidos para este usuário (RF11).
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }

    /**
     * Coautorias registradas por este usuário como Mentor (RF13).
     */
    public function registeredCoauthorships(): HasMany
    {
        return $this->hasMany(Coauthorship::class, 'mentor_id');
    }

    /**
     * Projetos em que este usuário foi reconhecido como Coautor (RF13).
     */
    public function coauthorships(): HasMany
    {
        return $this->hasMany(Coauthorship::class, 'coauthor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMentor(): bool
    {
        return $this->role === self::ROLE_MENTOR;
    }

    public function isAprendiz(): bool
    {
        return $this->role === self::ROLE_APRENDIZ;
    }

    public function hasVerifiedCredentials(): bool
    {
        return (bool) $this->verified_credentials;
    }
}
