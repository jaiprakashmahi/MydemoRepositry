<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'color', 'description', 'listing_type', 'is_residential', 'is_active', 'sort_order'];
    protected $casts = ['is_residential' => 'boolean', 'is_active' => 'boolean'];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }
}
