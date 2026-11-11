<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruitment_batch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('criterion_code', 30);
            $table->string('name', 150);
            $table->text('description')->nullable();

            // benefit atau cost
            $table->string('attribute', 20);

            // Bobot dalam persentase, contoh 25.00
            $table->decimal('weight', 8, 2);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['recruitment_batch_id', 'criterion_code'],
                'batch_criterion_code_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_criteria');
    }
};
