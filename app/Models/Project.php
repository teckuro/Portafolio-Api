<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'image_url',
        'project_url',
        'github_url',
        'tech_stack',
        'features',
        'status',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];
}
