<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CLOSED = 'closed';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mentor_id',
        'title',
        'description',
        'benefits',
        'workload_hours',
        'language',
        'category',
        'max_students',
        'meeting_link',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'workload_hours' => 'integer',
            'max_students' => 'integer',
        ];
    }

    /**
     * Mentor responsável pelo curso/mentoria.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Inscrições vinculadas ao curso (RF05).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Aprendizes inscritos no curso.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Recursos Educacionais Abertos (OER) associados ao curso (RF08/RF09).
     */
    public function oerMaterials(): HasMany
    {
        return $this->hasMany(OerMaterial::class, 'course_id');
    }

    /**
     * Avaliações e comentários recebidos pelo curso (RF10).
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'course_id');
    }

    /**
     * Certificados e badges emitidos neste curso (RF11).
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'course_id');
    }

    /**
     * Registros de coautoria vinculados ao curso (RF13).
     */
    public function coauthorships(): HasMany
    {
        return $this->hasMany(Coauthorship::class, 'course_id');
    }
}
