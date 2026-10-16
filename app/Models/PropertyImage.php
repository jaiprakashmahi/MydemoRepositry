<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $fillable = [
        'property_id',
        'image',
        'thumbnail',
        'title',
        'alt_text',
        'caption',
        'image_type',
        'is_primary',
        'sort_order',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
