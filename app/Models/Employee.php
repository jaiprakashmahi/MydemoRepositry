<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'designation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function locationLogs(): HasMany
    {
        return $this->hasMany(LocationLog::class);
    }

    /**
     * Label used in the employee select dropdown, e.g. "Deepak Kumar (7526972793)".
     */
    public function getLabelAttribute(): string
    {
        return "{$this->name} ({$this->phone})";
    }
}
