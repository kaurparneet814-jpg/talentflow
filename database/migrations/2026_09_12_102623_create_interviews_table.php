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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            // Application for which the interview is scheduled.
            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();

            // User assigned as interviewer.
            $table->foreignId('interviewer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Scheduled interview date and time.
            $table->dateTime('scheduled_at');

            // Online meeting link.
            $table->string('meeting_link')->nullable();

            // Current interview status.
            $table->string('status')->default('scheduled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
