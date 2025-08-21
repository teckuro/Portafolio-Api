<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = [
        'company',
        'position',
        'description',
        'start_date',
        'end_date',
        'location',
        'tech',
        'achievements',
        'is_current',
        'company_url',
        'status',
    ];

    protected $casts = [
        'tech' => 'array',
        'achievements' => 'array',
        'is_current' => 'boolean',
    ];
}
