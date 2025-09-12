<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'file_path',
        'comments'
    ];

    protected static function booted(): void
    {
        self::deleting(function (Document $document) {
            Storage::disk('public')->delete($document->file_path);
        });
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
