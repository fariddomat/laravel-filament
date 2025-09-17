<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use Notifiable;

    protected $fillable = [
        'customer_id', 'invoice_id', 'project_id', 'transaction_id',
        'amount', 'currency', 'payment_method', 'payment_date',
        'notes', 'created_by', 'custom_fields', 'is_refunded',
        'receipt_url', 'status_id'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'custom_fields' => 'array',
        'is_refunded' => 'boolean',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
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
