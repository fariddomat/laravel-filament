<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'noteable_id', 'noteable_type', 'created_by', 'content',
        'visibility', 'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    public function noteable()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
