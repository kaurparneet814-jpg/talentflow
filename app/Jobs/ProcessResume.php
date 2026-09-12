<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Resume;
use App\Services\CandidateScoringService;


class ProcessResume implements ShouldQueue
{
     use Queueable;

    protected $resume;

    // Receive the uploaded resume.
    public function __construct(Resume $resume)
    {
        $this->resume = $resume;
    }

    // Process the resume in the background.
    public function handle(CandidateScoringService $scoringService): void
    {
        // Mark the resume as processing.
        $this->resume->update([
            'processing_status' => 'processing',
        ]);

        // Temporary extracted data for the MVP.
        $extractedData = [
            'skills' => ['PHP', 'Laravel', 'MySQL'],
            'experience_years' => 3,
            'education' => 'BCA',
        ];

        // Prepare scores extracted from the resume.
        $scoreData = [
            'skills_score' => 80,
            'experience_score' => 70,
            'education_score' => 80,
            'bonus_score' => 60,
        ];

        // Calculate the final weighted candidate score.
        $finalScore = $scoringService->calculate($scoreData);

        // Store extracted data and calculated score.
        $this->resume->update([
            'extracted_data' => $extractedData,
            'skill_score' => $finalScore,
            'processing_status' => 'completed',
        ]);
    }
}
