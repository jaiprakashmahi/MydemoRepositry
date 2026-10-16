<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDocument extends Model
{
    protected $fillable = [
        'property_id',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'document_type',
        'is_public',
        'is_verified',
        'issued_date',
        'expiry_date',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_verified' => 'boolean',
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
