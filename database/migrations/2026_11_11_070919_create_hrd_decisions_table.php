<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrd_decisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruitment_application_id')
                ->constrained('recruitment_applications')
                ->restrictOnDelete();

            $table->foreignId('saw_calculation_id')
                ->constrained('saw_calculations')
                ->restrictOnDelete();

            $table->string('decision_status', 30);

            $table->text('reason')->nullable();

            $table->foreignId('decided_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('decided_at')->nullable();

            $table->timestamps();

            $table->index(
                ['recruitment_application_id', 'saw_calculation_id'],
                'hrd_decisions_application_calculation_index'
            );

            $table->index('decision_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrd_decisions');
    }
};
