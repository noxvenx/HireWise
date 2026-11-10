<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recruitment_applications', function (Blueprint $table) {
            $table->string('screening_status', 20)
                ->default('pending')
                ->after('notes');

            $table->foreignId('screened_by')
                ->nullable()
                ->after('screening_status')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('screened_at')
                ->nullable()
                ->after('screened_by');

            $table->text('screening_notes')
                ->nullable()
                ->after('screened_at');
        });
    }

    public function down(): void
    {
        Schema::table('recruitment_applications', function (Blueprint $table) {
            $table->dropForeign(['screened_by']);

            $table->dropColumn([
                'screening_status',
                'screened_by',
                'screened_at',
                'screening_notes',
            ]);
        });
    }
};
