<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'color',
        'position',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'status_id');
    }
}
