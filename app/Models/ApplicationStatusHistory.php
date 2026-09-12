<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatusHistory extends Model
{
     // Fields allowed for mass assignment.
    protected $fillable = [
        'application_id',
        'old_status',
        'new_status',
        'changed_by',
    ];

    // Application associated with this history record.
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // User who changed the application status.
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
