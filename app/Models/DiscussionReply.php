<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionReply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'discussion_id',
        'created_by',
        'content',
        'is_client_reply',
        'custom_fields',
    ];

    protected $casts = [
        'is_client_reply' => 'boolean',
        'custom_fields' => 'array',
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
