<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coauthorship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'mentor_id',
        'coauthor_id',
        'contribution_description',
    ];

    /**
     * Curso/mentoria em que ocorreu a coautoria.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Mentor que reconheceu a coautoria.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Aprendiz/parceiro reconhecido como coautor do projeto.
     */
    public function coauthor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coauthor_id');
    }
}
