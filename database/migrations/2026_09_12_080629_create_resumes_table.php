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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();

            // Link each resume to a candidate profile.
            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            // Store resume file information.
            $table->string('original_name');
            $table->string('file_path');

            // Track background processing status.
            $table->string('processing_status')->default('pending');

            // Store extracted resume information after processing.
            $table->json('extracted_data')->nullable();

            // Store the calculated candidate/resume score.
            $table->decimal('skill_score', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
