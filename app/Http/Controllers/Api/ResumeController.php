<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResumeRequest;
use Illuminate\Http\Request;
use App\Models\Resume;
use App\Jobs\ProcessResume;

class ResumeController extends Controller
{
     // Upload a resume for the logged-in candidate.
    public function store(StoreResumeRequest $request)
    {
        $user = $request->user();

        // Only candidate users can upload resumes.
        if (!$user->hasRole('candidate')) {
            return response()->json([
                'message' => 'Only candidates can upload resumes.'
            ], 403);
        }

        // Get candidate profile of the logged-in user.
        $candidate = $user->candidate;

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate profile not found.'
            ], 404);
        }

        $file = $request->file('resume');

        // Store resume privately in Laravel storage.
        $path = $file->store('resumes', 'local');

        // Save resume information in the database.
        $resume = Resume::create([
            'candidate_id' => $candidate->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'processing_status' => 'pending',
        ]);

        // Send resume processing to the background queue.
        ProcessResume::dispatch($resume);

        return response()->json([
            'message' => 'Resume uploaded successfully.',
            'resume' => $resume,
        ], 201);
    }
}
