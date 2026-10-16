<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'color', 'description', 'group', 'is_featured', 'is_active', 'sort_order'];
    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'amenity_property')
            ->withPivot('value', 'note')
            ->withTimestamps();
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
