<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Header riwayat perhitungan SAW
        Schema::create('saw_calculations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruitment_batch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedInteger('version');

            $table->foreignId('calculated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('calculated_at')->nullable();

            $table->decimal('total_weight', 8, 2);
            $table->unsignedInteger('candidate_count');

            $table->timestamps();

            $table->unique(
                ['recruitment_batch_id', 'version'],
                'batch_saw_version_unique'
            );
        });

        // Hasil akhir dan ranking kandidat
        Schema::create('saw_calculation_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('saw_calculation_id')
                ->constrained('saw_calculations')
                ->cascadeOnDelete();

            $table->foreignId('recruitment_application_id')
                ->constrained('recruitment_applications')
                ->restrictOnDelete();

            $table->decimal('preference_score', 16, 8);
            $table->unsignedInteger('rank');

            $table->timestamps();

            $table->unique(
                ['saw_calculation_id', 'recruitment_application_id'],
                'saw_result_application_unique'
            );
        });

        // Detail matriks dan normalisasi setiap kriteria
        Schema::create('saw_calculation_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('saw_calculation_id')
                ->constrained('saw_calculations')
                ->cascadeOnDelete();

            $table->foreignId('recruitment_application_id')
                ->constrained('recruitment_applications')
                ->restrictOnDelete();

            $table->foreignId('recruitment_criterion_id')
                ->nullable()
                ->constrained('recruitment_criteria')
                ->nullOnDelete();

            // Snapshot kriteria saat perhitungan dilakukan
            $table->string('criterion_code', 30);
            $table->string('criterion_name', 150);
            $table->string('attribute', 20);
            $table->decimal('weight', 8, 2);

            // Nilai awal, normalisasi, dan nilai terbobot
            $table->decimal('raw_score', 12, 2);
            $table->decimal('normalized_score', 16, 8);
            $table->decimal('weighted_score', 16, 8);

            $table->timestamps();

            $table->unique(
                [
                    'saw_calculation_id',
                    'recruitment_application_id',
                    'criterion_code',
                ],
                'saw_detail_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saw_calculation_details');
        Schema::dropIfExists('saw_calculation_results');
        Schema::dropIfExists('saw_calculations');
    }
};
