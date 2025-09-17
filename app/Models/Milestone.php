<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'project_id', 'title', 'description', 'due_date',
        'created_by', 'status_id', 'custom_fields', 'is_visible_to_client'
    ];

    protected $casts = [
        'due_date' => 'date',
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
