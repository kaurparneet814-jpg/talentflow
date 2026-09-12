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
        Schema::create('technical_tasks', function (Blueprint $table) {
            $table->id();

            // Application for which the technical task is assigned.
            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();

            // Recruiter/Admin who assigned the task.
            $table->foreignId('assigned_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Task details.
            $table->string('title');
            $table->text('description');

            // Submission deadline.
            $table->dateTime('deadline');

            // Current task status.
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_tasks');
    }
};
