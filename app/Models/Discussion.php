<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'customer_id',
        'subject',
        'content',
        'created_by',
        'visibility',
        'category',
        'custom_fields',
        'is_visible_to_client',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'is_visible_to_client' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class);
    }
}
