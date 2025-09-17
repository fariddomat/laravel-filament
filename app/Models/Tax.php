<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 'rate', 'country', 'created_by', 'custom_fields', 'is_active'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
