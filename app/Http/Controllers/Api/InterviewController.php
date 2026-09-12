<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInterviewRequest;
use App\Models\Interview;
use App\Http\Requests\UpdateInterviewRequest;

class InterviewController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();

        // Only admin and recruiter can view interviews.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $interviews = Interview::with([
            'application.candidate.user',
            'application.job',
            'interviewer'
        ])->latest('scheduled_at')->get();

        return response()->json([
            'message' => 'Interviews fetched successfully.',
            'interviews' => $interviews
        ], 200);
    }



    // Schedule a new interview.
    public function store(StoreInterviewRequest $request)
    {
        $user = $request->user();

        // Only admin and recruiter can schedule interviews.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Prevent interviewer time conflicts.
        $conflictExists = Interview::where('interviewer_id', $request->interviewer_id)
            ->where('scheduled_at', $request->scheduled_at)
            ->exists();

        if ($conflictExists) {
            return response()->json([
                'message' => 'Interviewer already has an interview scheduled at this time.'
            ], 409);
        }

        $interview = Interview::create([
            ...$request->validated(),
            'status' => $request->status ?? 'scheduled',
        ]);

        return response()->json([
            'message' => 'Interview scheduled successfully.',
            'interview' => $interview,
        ], 201);
    }

    // Update an existing interview.
    public function update(UpdateInterviewRequest $request, $id)
    {
        $user = $request->user();

        // Only admin and recruiter can update interviews.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $interview = Interview::findOrFail($id);

        // Use existing values if interviewer or time is not being changed.
        $interviewerId = $request->input(
            'interviewer_id',
            $interview->interviewer_id
        );

        $scheduledAt = $request->input(
            'scheduled_at',
            $interview->scheduled_at
        );

        // Check whether another interview already uses this interviewer and time.
        $conflictExists = Interview::where('interviewer_id', $interviewerId)
            ->where('scheduled_at', $scheduledAt)
            ->where('id', '!=', $interview->id)
            ->exists();

        if ($conflictExists) {
            return response()->json([
                'message' => 'Interviewer already has an interview scheduled at this time.'
            ], 409);
        }

        $interview->update($request->validated());

        return response()->json([
            'message' => 'Interview updated successfully.',
            'interview' => $interview,
        ], 200);
    }

}
