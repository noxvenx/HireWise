<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            $table->string('candidate_code', 30)->unique();
            $table->string('full_name', 150);

            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 30)->nullable()->index();

            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();

            $table->string('education', 100)->nullable();
            $table->string('institution', 150)->nullable();
            $table->string('major', 150)->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('full_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};