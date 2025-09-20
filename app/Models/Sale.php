<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Sale extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'project_id',
        'contract_id',
        'quote_id',
        'sale_number',
        'sale_date',
        'total_amount',
        'currency',
        'created_by',
        'items',
        'commission',
        'commission_user_id',
        'notes',
        'custom_fields',
        'discount',
        'tax_id',
        'status_id',
        'is_visible_to_client',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'items' => 'array',
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
    ];

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

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function commissionUser()
    {
        return $this->belongsTo(User::class, 'commission_user_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

        public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

}
