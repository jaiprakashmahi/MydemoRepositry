<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['category', 'propertyType', 'city', 'state', 'primaryImage']);

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('reference_no', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured === 'yes');
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('direction', 'desc');
        $allowed = ['title', 'price', 'created_at', 'views_count', 'status'];
        if (!in_array($sort, $allowed)) $sort = 'created_at';
        $query->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc');

        $properties = $query->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total'    => Property::count(),
            'active'   => Property::where('status', 'active')->count(),
            'pending'  => Property::where('status', 'pending')->count(),
            'sold'     => Property::whereIn('status', ['sold', 'rented'])->count(),
            'featured' => Property::where('is_featured', true)->count(),
        ];

        $categories = Category::active()->ordered()->get();
        $cities = City::active()->ordered()->get();

        return view('properties.index', compact('properties', 'stats', 'categories', 'cities'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $data = $this->formData();
        return view('properties.create', $data);
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProperty($request);

        DB::beginTransaction();
        try {
            $validated['user_id'] = Auth::id() ?? 1;
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_verified'] = $request->boolean('is_verified');
            $validated['is_negotiable'] = $request->boolean('is_negotiable', true);
            $validated['show_contact_publicly'] = $request->boolean('show_contact_publicly', true);
            $validated['is_active'] = $request->boolean('is_active', true);

            if ($validated['status'] === 'active' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            // Featured image upload
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $this->uploadFile($request->file('featured_image'), 'properties/featured');
            }

            $property = Property::create($validated);

            // Amenities
            if ($request->filled('amenities')) {
                $property->amenities()->sync($request->amenities);
            }

            // Gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $i => $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->uploadFile($file, 'properties/gallery');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image'       => $path,
                        'is_primary'  => $i === 0,
                        'image_type'  => 'gallery',
                        'sort_order'  => $i,
                        'file_size'   => (int) ($file->getSize() / 1024),
                        'mime_type'   => $file->getMimeType(),
                    ]);
                }
            }

            // Documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->uploadFile($file, 'properties/documents');
                    PropertyDocument::create([
                        'property_id'   => $property->id,
                        'title'         => $file->getClientOriginalName(),
                        'file_path'     => $path,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime_type'     => $file->getMimeType(),
                        'file_size'     => (int) ($file->getSize() / 1024),
                        'document_type' => 'other',
                        'is_public'     => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('properties.index')
                ->with('success', 'Property created successfully! Reference: ' . $property->reference_no);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create property: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load([
            'category',
            'propertyType',
            'state',
            'city',
            'images',
            'documents',
            'amenities',
            'user',
        ]);

        // Increment view count
        $property->increment('views_count');

        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $data = $this->formData();
        $data['property'] = $property->load(['images', 'documents', 'amenities']);
        return view('properties.edit', $data);
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $this->validateProperty($request, $property->id);

        DB::beginTransaction();
        try {
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_verified'] = $request->boolean('is_verified');
            $validated['is_negotiable'] = $request->boolean('is_negotiable', true);
            $validated['show_contact_publicly'] = $request->boolean('show_contact_publicly', true);
            $validated['is_active'] = $request->boolean('is_active', true);

            if ($validated['status'] === 'active' && empty($property->published_at)) {
                $validated['published_at'] = now();
            }

            // Featured image
            if ($request->hasFile('featured_image')) {
                if ($property->featured_image) {
                    Storage::disk('public')->delete($property->featured_image);
                }
                $validated['featured_image'] = $this->uploadFile($request->file('featured_image'), 'properties/featured');
            }

            $property->update($validated);

            // Amenities
            $property->amenities()->sync($request->amenities ?? []);

            // New gallery images
            if ($request->hasFile('gallery_images')) {
                $maxSort = $property->images()->max('sort_order') ?? 0;
                foreach ($request->file('gallery_images') as $i => $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->uploadFile($file, 'properties/gallery');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image'       => $path,
                        'image_type'  => 'gallery',
                        'sort_order'  => $maxSort + $i + 1,
                        'file_size'   => (int) ($file->getSize() / 1024),
                        'mime_type'   => $file->getMimeType(),
                    ]);
                }
            }

            // New documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->uploadFile($file, 'properties/documents');
                    PropertyDocument::create([
                        'property_id'   => $property->id,
                        'title'         => $file->getClientOriginalName(),
                        'file_path'     => $path,
                        'file_name'     => $file->getClientOriginalName(),
                        'mime_type'     => $file->getMimeType(),
                        'file_size'     => (int) ($file->getSize() / 1024),
                        'document_type' => 'other',
                        'is_public'     => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('properties.index')
                ->with('success', 'Property updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified property (soft delete).
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()
            ->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Toggle property active/inactive status.
     */
    public function toggleStatus(Property $property)
    {
        $property->update(['is_active' => !$property->is_active]);
        $status = $property->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Property {$status} successfully!");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Property $property)
    {
        $property->update(['is_featured' => !$property->is_featured]);
        $status = $property->is_featured ? 'marked as featured' : 'removed from featured';
        return back()->with('success', "Property {$status} successfully!");
    }

    /**
     * Delete a single gallery image.
     */
    public function deleteImage(PropertyImage $image)
    {
        if ($image->image) Storage::disk('public')->delete($image->image);
        if ($image->thumbnail) Storage::disk('public')->delete($image->thumbnail);
        $image->delete();
        return back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Delete a single document.
     */
    public function deleteDocument(PropertyDocument $document)
    {
        if ($document->file_path) Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Document deleted successfully!');
    }

    // ==================== Helpers ====================

    protected function formData(): array
    {
        return [
            'categories'     => Category::active()->ordered()->get(),
            'propertyTypes'  => PropertyType::active()->ordered()->get(),
            'states'         => State::active()->ordered()->get(),
            'cities'         => City::active()->ordered()->get(),
            'amenities'      => Amenity::active()->ordered()->get()->groupBy('group'),
        ];
    }

    protected function validateProperty(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = 'nullable|string|max:300|unique:properties,slug' . ($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            // Required
            'title'             => 'required|string|max:255',
            'slug'              => $slugRule,
            'category_id'       => 'required|exists:categories,id',
            'property_type_id'  => 'required|exists:property_types,id',
            'state_id'          => 'required|exists:states,id',
            'city_id'           => 'required|exists:cities,id',
            'listing_type'      => 'required|in:sale,rent',
            'status'            => 'required|in:draft,pending,active,inactive,sold,rented,rejected',
            'price'             => 'required|numeric|min:0',

            // Optional
            'description'       => 'nullable|string',
            'highlights'        => 'nullable|string',
            'price_per_sqft'    => 'nullable|numeric|min:0',
            'maintenance_charges' => 'nullable|numeric|min:0',
            'security_deposit'  => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:5',
            'area'              => 'nullable|numeric|min:0',
            'area_unit'         => 'nullable|in:sqft,sqm,sqyd,acre,hectare',
            'carpet_area'       => 'nullable|numeric|min:0',
            'plot_area'         => 'nullable|numeric|min:0',
            'built_up_area'     => 'nullable|numeric|min:0',
            'bedrooms'          => 'nullable|integer|min:0|max:50',
            'bathrooms'         => 'nullable|integer|min:0|max:50',
            'balconies'         => 'nullable|integer|min:0|max:50',
            'total_floors'      => 'nullable|integer|min:0',
            'floor_number'      => 'nullable|integer',
            'parking_spaces'    => 'nullable|integer|min:0',
            'age_of_property'   => 'nullable|integer|min:0|max:200',
            'furnishing'        => 'nullable|in:unfurnished,semi-furnished,furnished',
            'facing'            => 'nullable|in:north,south,east,west,north-east,north-west,south-east,south-west',
            'ownership'         => 'nullable|in:freehold,leasehold,co-operative,power-of-attorney',
            'address'           => 'nullable|string|max:500',
            'locality'          => 'nullable|string|max:200',
            'landmark'          => 'nullable|string|max:200',
            'pincode'           => 'nullable|string|max:10',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'contact_name'      => 'nullable|string|max:150',
            'contact_email'     => 'nullable|email|max:150',
            'contact_phone'     => 'nullable|string|max:20',
            'contact_whatsapp'  => 'nullable|string|max:20',
            'video_url'         => 'nullable|url|max:500',
            'virtual_tour_url'  => 'nullable|url|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keywords'     => 'nullable|string|max:500',
            'published_at'      => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:published_at',

            // Files
            'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery_images.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documents.*'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',

            // Amenities
            'amenities'         => 'nullable|array',
            'amenities.*'       => 'exists:amenities,id',
        ]);
    }

    protected function uploadFile($file, string $folder): string
    {
        $name = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $name, 'public');
    }
}
