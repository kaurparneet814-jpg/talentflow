<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    // Fields allowed for mass assignment.
    protected $fillable = [
        'application_id',
        'interviewer_id',
        'scheduled_at',
        'meeting_link',
        'status',
    ];

    // Application associated with this interview.
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // User assigned as interviewer.
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
