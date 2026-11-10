<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_applications', function (Blueprint $table) {
            $table->id();

            $table->string('application_code', 30)->unique();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->restrictOnDelete();

            $table->foreignId('recruitment_batch_id')
                ->constrained('recruitment_batches')
                ->restrictOnDelete();

            $table->timestamp('applied_at')->useCurrent();

            $table->string('source', 50)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['candidate_id', 'recruitment_batch_id'],
                'unique_candidate_batch'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_applications');
    }
};
