<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'customer_id',
        'description',
        'start_date',
        'deadline',
        'budget',
        'total_billed',
        'created_by',
        'status_id',
        'billing_type',
        'hourly_rate',
        'is_visible_to_client',
        'allow_client_comments',
        'custom_fields',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
        'allow_client_comments' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }


    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function customerFeedback()
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    public function ticketReplies()
    {
        return $this->hasManyThrough(TicketReply::class, Ticket::class);
    }
}
