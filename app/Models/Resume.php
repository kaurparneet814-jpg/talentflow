<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    // Attributes that can be mass assigned.
    protected $fillable = [
        'candidate_id',
        'original_name',
        'file_path',
        'processing_status',
        'extracted_data',
        'skill_score',
    ];

    // Cast JSON data into an array automatically.
    protected $casts = [
        'extracted_data' => 'array',
        'skill_score' => 'decimal:2',
    ];

    
    // Get the candidate who owns this resume.
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Get all applications that use this resume.
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
