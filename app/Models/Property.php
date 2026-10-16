<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'property_type_id',
        'state_id',
        'city_id',
        'title',
        'slug',
        'reference_no',
        'description',
        'highlights',
        'listing_type',
        'status',
        'is_featured',
        'is_verified',
        'published_at',
        'expires_at',
        'price',
        'price_per_sqft',
        'maintenance_charges',
        'security_deposit',
        'is_negotiable',
        'currency',
        'area',
        'area_unit',
        'carpet_area',
        'plot_area',
        'built_up_area',
        'bedrooms',
        'bathrooms',
        'balconies',
        'total_floors',
        'floor_number',
        'parking_spaces',
        'age_of_property',
        'furnishing',
        'facing',
        'ownership',
        'address',
        'locality',
        'landmark',
        'pincode',
        'latitude',
        'longitude',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'show_contact_publicly',
        'featured_image',
        'video_url',
        'virtual_tour_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_negotiable' => 'boolean',
        'show_contact_publicly' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'price' => 'decimal:2',
        'area' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // ===== Auto slug + reference number =====
    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->title);
            }
            if (empty($property->reference_no)) {
                $property->reference_no = static::generateReferenceNo();
            }
        });

        static::updating(function (Property $property) {
            if ($property->isDirty('title')) {
                $property->slug = static::generateUniqueSlug($property->title, $property->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while (
            static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function generateReferenceNo(): string
    {
        $year = date('Y');
        $last = static::withTrashed()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $next = $last ? ((int) substr($last->reference_no, -4)) + 1 : 1;

        return sprintf('RKV-%s-%04d', $year, $next);
    }

    // ===== Relationships =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_property')
            ->withPivot('value', 'note')
            ->withTimestamps();
    }

    // ===== Scopes =====
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ===== Accessors =====
    public function getFormattedPriceAttribute(): string
    {
        $price = $this->price;
        if ($price >= 10000000) {
            return '₹ ' . number_format($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹ ' . number_format($price / 100000, 2) . ' L';
        }
        return '₹ ' . number_format($price);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active'   => ['class' => 'badge-success', 'label' => 'Active'],
            'pending'  => ['class' => 'badge-warning', 'label' => 'Pending'],
            'draft'    => ['class' => 'badge-info',    'label' => 'Draft'],
            'inactive' => ['class' => 'badge-warning', 'label' => 'Inactive'],
            'sold'     => ['class' => 'badge-purple',  'label' => 'Sold'],
            'rented'   => ['class' => 'badge-purple',  'label' => 'Rented'],
            'rejected' => ['class' => 'badge-danger',  'label' => 'Rejected'],
            default    => ['class' => 'badge-info',    'label' => ucfirst($this->status)],
        };
    }
}
