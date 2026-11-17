<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RF03 - Publicar oferta de mentoria/curso
     * RF04 - Buscar ofertas com filtros
     * RF06 - Integrar sessão virtual (meeting_link)
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mentor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('title', 180);
            $table->text('description');
            $table->text('benefits')->nullable();
            $table->unsignedSmallInteger('workload_hours');
            $table->string('language', 20)->default('pt-BR')->index();
            $table->string('category', 100)->index();
            $table->unsignedInteger('max_students')->default(30);
            $table->string('meeting_link', 500)->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('published')->index();
            $table->timestamps();

            // Índice composto para otimizar a busca com filtros no catálogo (RF04)
            $table->index(['status', 'category', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
