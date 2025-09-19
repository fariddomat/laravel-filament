<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Contract extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'project_id',
        'quote_id',
        'title',
        'content',
        'contract_template_id',
        'start_date',
        'end_date',
        'value',
        'currency',
        'is_signed',
        'signed_at',
        'signed_by',
        'signature_path',
        'created_by',
        'status_id',
        'custom_fields',
        'notes',
        'is_visible_to_client',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'custom_fields' => 'array',
        'is_signed' => 'boolean',
        'is_visible_to_client' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function template()
    {
        return $this->belongsTo(ContractTemplate::class, 'contract_template_id');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }
     public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
