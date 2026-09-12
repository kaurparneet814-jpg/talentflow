<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalTask extends Model
{
    // Fields allowed for mass assignment.
    protected $fillable = [
        'application_id',
        'assigned_by',
        'title',
        'description',
        'deadline',
        'status',
    ];

    // Application associated with this technical task.
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // Recruiter/Admin who assigned this task.
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
