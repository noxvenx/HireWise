<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->boolean('criteria_weights_locked')
                ->default(false)
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->dropColumn('criteria_weights_locked');
        });
    }
};
