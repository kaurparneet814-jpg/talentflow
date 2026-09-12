<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    // Fields allowed for mass assignment.
    protected $fillable = [
        'candidate_id',
        'job_id',
        'resume_id',
        'status',
        'score',
    ];

    // Candidate who submitted the application.
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Job associated with the application.
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Resume submitted with the application.
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    // Status change history of this application.
    public function statusHistory()
    {
        return $this->hasMany(ApplicationStatusHistory::class);
    }

    // Interviews scheduled for this application.
    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }


}
