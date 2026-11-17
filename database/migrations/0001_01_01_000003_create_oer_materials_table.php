<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RF08 - Publicar recurso educacional (OER)
     * RF09 - Consultar repositório (OER)
     */
    public function up(): void
    {
        Schema::create('oer_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('uploader_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->nullOnDelete();
            $table->string('title', 180);
            $table->text('description');
            $table->string('file_path_or_url', 500);
            $table->boolean('public')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oer_materials');
    }
};
