<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->unsignedInteger('reserve_quota')
                ->default(0)
                ->after('quota');
        });
    }

    public function down(): void
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->dropColumn('reserve_quota');
        });
    }
};
