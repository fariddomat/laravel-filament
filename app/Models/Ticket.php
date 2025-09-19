<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    use Notifiable;

    protected $fillable = [
        'project_id',
        'customer_id',
        'assigned_to',
        'subject',
        'content',
        'priority',
        'category',
        'due_date',
        'status_id',
        'custom_fields',
        'created_by',
        'is_visible_to_client',
        'allow_client_comments',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
        'allow_client_comments' => 'boolean',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function customerFeedback()
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    public function ticketReplies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
