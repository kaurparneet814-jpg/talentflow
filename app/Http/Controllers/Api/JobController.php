<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Models\User;
use App\Http\Resources\JobResource;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->get();

        return response()->json([
            'message' => 'Jobs fetched successfully',
            'jobs' => $jobs
        ], 200);
    }

    public function store(StoreJobRequest $request)
    {
        $this->authorize('create', Job::class);

        $job = Job::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Job created successfully',
            'job' => new JobResource($job),
        ], 201);
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);

        return response()->json([
            'message' => 'Job fetched successfully',
            'job' => $job
        ], 200);
    }

    public function update(UpdateJobRequest $request, $id)
    {
        $job = Job::findOrFail($id);

        $this->authorize('update', $job);

        $job->update($request->validated());

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job
        ], 200);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);

        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully'
        ], 200);
    }

}
