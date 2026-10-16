<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = ['state_id', 'name', 'slug', 'pincode', 'latitude', 'longitude', 'image', 'description', 'is_popular', 'is_active', 'sort_order'];
    protected $casts = ['is_popular' => 'boolean', 'is_active' => 'boolean'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
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
