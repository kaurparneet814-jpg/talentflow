<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Candidate who applied for the job.
            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            // Job for which the candidate applied.
            $table->foreignId('job_id')
                ->constrained('job_posts')
                ->cascadeOnDelete();

            // Resume submitted with this application.
            $table->foreignId('resume_id')
                ->nullable()
                ->constrained('resumes')
                ->nullOnDelete();

            // Current stage in the hiring pipeline.
            $table->string('status')->default('applied');

            // Final or calculated candidate score.
            $table->decimal('score', 5, 2)->nullable();

            $table->timestamps();

            // Prevent the same candidate from applying to the same job multiple times.
            $table->unique(['candidate_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
