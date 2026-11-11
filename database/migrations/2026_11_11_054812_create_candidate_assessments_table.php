<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruitment_application_id')
                ->constrained('recruitment_applications')
                ->cascadeOnDelete();

            $table->foreignId('recruitment_criterion_id')
                ->constrained('recruitment_criteria')
                ->restrictOnDelete();

            $table->decimal('score', 12, 2);

            $table->foreignId('assessed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assessed_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['recruitment_application_id', 'recruitment_criterion_id'],
                'application_criterion_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_assessments');
    }
};
