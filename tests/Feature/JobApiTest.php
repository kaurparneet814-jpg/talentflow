<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class JobApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_unauthenticated_user_cannot_create_job(): void
    {
        $response = $this->postJson('/api/jobs', [
            'title' => 'Laravel Developer',
            'department' => 'Engineering',
            'description' => 'Laravel Developer position',
            'experience_required' => 3,
            'salary_min' => 40000,
            'salary_max' => 60000,
            'application_deadline' => '2026-09-30',
            'status' => 'active',
        ]);

        $response->assertUnauthorized();
    }


    // Verify that a recruiter can create a job.
    public function test_recruiter_can_create_job(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'recruiter',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/jobs', [
            'title' => 'Laravel Developer',
            'department' => 'Engineering',
            'description' => 'Laravel Developer position',
            'experience_required' => 3,
            'salary_min' => 40000,
            'salary_max' => 60000,
            'application_deadline' => now()->addDays(10)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Job created successfully',
            ]);
    }

    // Verify that a candidate cannot create a job.
    public function test_candidate_cannot_create_job(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'candidate',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/jobs', [
            'title' => 'Laravel Developer',
            'department' => 'Engineering',
            'description' => 'Laravel Developer position',
            'experience_required' => 3,
            'salary_min' => 40000,
            'salary_max' => 60000,
            'application_deadline' => now()->addDays(10)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $response->assertForbidden();
    }


}
