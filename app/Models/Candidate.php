<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    // Attributes that can be mass assigned in candidates table 
    protected $fillable = [
        'user_id',
        'experience_years',
        'education',
    ];

    // Get the user account associated with this candidate profile.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get all resumes uploaded by this candidate.
    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    // Get all job applications submitted by this candidate.
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

}
