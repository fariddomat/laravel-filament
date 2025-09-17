<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model
{
    use Notifiable;

    protected $fillable = [
        'customer_id', 'project_id', 'invoice_number', 'issue_date',
        'due_date', 'total_amount', 'paid_amount', 'currency', 'notes',
        'items', 'created_by', 'custom_fields', 'discount', 'tax_id',
        'status_id', 'is_visible_to_client'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }


}
