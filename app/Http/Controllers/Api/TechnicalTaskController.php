<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTechnicalTaskRequest;
use App\Models\TechnicalTask;
use App\Http\Requests\UpdateTechnicalTaskStatusRequest;

class TechnicalTaskController extends Controller
{
   // Assign a technical task to a candidate application.
    public function store(StoreTechnicalTaskRequest $request)
    {
        $user = $request->user();

        // Only admin and recruiter can assign technical tasks.
        if (!$user->hasRole('admin') && !$user->hasRole('recruiter')) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Create the technical task.
        $task = TechnicalTask::create([
            ...$request->validated(),
            'assigned_by' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Technical task assigned successfully.',
            'task' => $task,
        ], 201);
    }

    public function updateStatus( UpdateTechnicalTaskStatusRequest $request,  $id ) {
        $user = $request->user();

        $task = TechnicalTask::with('application.candidate')->findOrFail($id);

        // Candidate can update only their own assigned task.
        $isCandidateOwner =
            $user->hasRole('candidate') &&
            $task->application->candidate->user_id === $user->id;

        // Recruiter and admin can also update task status.
        $isStaff =
            $user->hasRole('admin') ||
            $user->hasRole('recruiter');

        if (!$isCandidateOwner && !$isStaff) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Technical task status updated successfully.',
            'task' => $task,
        ], 200);
    }

}
