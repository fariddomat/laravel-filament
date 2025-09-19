<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'content',
        'user_id',
        'is_internal',
        'is_client_reply',
        'custom_fields',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_client_reply' => 'boolean',
        'custom_fields' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
