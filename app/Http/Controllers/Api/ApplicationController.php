<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{

    // List all applications for recruiters and admins.
    public function index(Request $request)
    {
        $user = $request->user();

        // Only admin and recruiter can view all applications.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $applications = Application::with([
            'candidate.user',
            'job',
            'resume'
        ])->latest()->get();

        return response()->json([
            'message' => 'Applications fetched successfully.',
            'applications' => ApplicationResource::collection($applications),
        ], 200);
    }

    // Submit an application for a job.
    public function store(StoreApplicationRequest $request, $jobId)
    {
        $user = $request->user();

        // Only candidates can apply for jobs.
        if (!$user->hasRole('candidate')) {
            return response()->json([
                'message' => 'Only candidates can apply for jobs.'
            ], 403);
        }

        // Get the candidate profile of the logged-in user.
        $candidate = $user->candidate;

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate profile not found.'
            ], 404);
        }

        // Make sure the requested job exists.
        $job = Job::findOrFail($jobId);

        // Make sure the selected resume belongs to this candidate.
        $resume = $candidate->resumes()
            ->where('id', $request->resume_id)
            ->first();

        if (!$resume) {
            return response()->json([
                'message' => 'Invalid resume selected.'
            ], 422);
        }

        // Prevent duplicate applications for the same job.
        $alreadyApplied = Application::where('candidate_id', $candidate->id)
            ->where('job_id', $job->id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'You have already applied for this job.'
            ], 409);
        }

        // Create application and initial status history together.
        $application = DB::transaction(function () use ($candidate, $job, $resume, $user) {

            $application = Application::create([
                'candidate_id' => $candidate->id,
                'job_id' => $job->id,
                'resume_id' => $resume->id,
                'status' => 'applied',
            ]);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'old_status' => null,
                'new_status' => 'applied',
                'changed_by' => $user->id,
            ]);

            return $application;
        });

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => new ApplicationResource($application),
        ], 201);
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, $id)
    {
        $user = $request->user();

        // Only admin and recruiter can change application status.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $application = Application::findOrFail($id);
        $oldStatus = $application->status;

        // Update application and history together.
        DB::transaction(function () use ($application, $request, $user, $oldStatus) {

            $application->update([
                'status' => $request->status,
            ]);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'changed_by' => $user->id,
            ]);
        });

        return response()->json([
            'message' => 'Application status updated successfully.',
            'application' => new ApplicationResource($application),
        ], 200);
    }
}
