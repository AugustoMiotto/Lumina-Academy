<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RF13 - Registrar coautoria (Reconhecimento mútuo de projeto)
     */
    public function up(): void
    {
        Schema::create('coauthorships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();
            $table->foreignId('mentor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('coauthor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->text('contribution_description');
            $table->timestamps();

            $table->unique(['course_id', 'coauthor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coauthorships');
    }
};
