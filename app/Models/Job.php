<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_posts';

    protected $fillable = [
        'title',
        'department',
        'description',
        'experience_required',
        'salary_min',
        'salary_max',
        'application_deadline',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get all applications submitted for this job.
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}