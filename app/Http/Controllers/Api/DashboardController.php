<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\TechnicalTask;


class DashboardController extends Controller
{
    // Return recruitment dashboard analytics for admin and recruiter.
    public function index(Request $request)
    {
        $user = $request->user();

        // Only admin and recruiter can access dashboard analytics.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Count applications grouped by their current status.
        $applicationsByStatus = Application::selectRaw(
            'status, COUNT(*) as total'
        )
            ->groupBy('status')
            ->pluck('total', 'status');

        // Fetch upcoming scheduled interviews.
        $upcomingInterviews = Interview::with([
            'application.candidate.user',
            'application.job',
            'interviewer'
        ])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        // Build dashboard statistics.
        $analytics = [
            'total_jobs' => Job::count(),
            'total_applications' => Application::count(),
            'applications_by_status' => $applicationsByStatus,
            'average_resume_score' => round(
                Resume::whereNotNull('skill_score')->avg('skill_score') ?? 0,
                2
            ),
            'upcoming_interviews' => $upcomingInterviews,
            'overdue_technical_tasks' => TechnicalTask::where(
                'status',
                'overdue'
            )->count(),
        ];

        return response()->json([
            'message' => 'Dashboard analytics fetched successfully.',
            'analytics' => $analytics,
        ], 200);
    }
}
