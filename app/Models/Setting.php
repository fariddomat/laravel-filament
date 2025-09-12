<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    public array $translatable = ['company_name', 'description', 'address'];

    protected $fillable = ['group', 'name', 'locked', 'payload'];

    protected $casts = [
        'payload' => 'array',  // Auto-decode/encode JSON for payload
        'locked' => 'boolean',
    ];

    protected $table = 'settings';
}
