<?php

namespace App\Services;

class CandidateScoringService
{
    // Calculate candidate score using weighted criteria.
    public function calculate(array $data): float
    {
        $skillsScore = $data['skills_score'] ?? 0;
        $experienceScore = $data['experience_score'] ?? 0;
        $educationScore = $data['education_score'] ?? 0;
        $bonusScore = $data['bonus_score'] ?? 0;

        $totalScore =
            ($skillsScore * 0.40) +
            ($experienceScore * 0.30) +
            ($educationScore * 0.20) +
            ($bonusScore * 0.10);

        return round($totalScore, 2);
    }
}
