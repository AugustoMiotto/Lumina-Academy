<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RF10 - Avaliar e comentar
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1 a 5
            $table->text('comment')->nullable();
            $table->boolean('professional_utility')->default(true);
            $table->timestamps();

            // Cada aluno pode avaliar um curso apenas uma vez
            $table->unique(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
