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

class PublicPropertyController extends Controller
{
    /**
     * Show the post-property form to logged-in users.
     */
    public function index()
    {
        // Ensure user is logged in (route already has auth middleware, but be safe)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to post a property.');
        }

        $data = [
            'categories'    => Category::active()->ordered()->get(),
            'propertyTypes' => PropertyType::active()->ordered()->get(),
            'states'        => State::active()->ordered()->get(),
            'cities'        => City::active()->ordered()->get(),
            'amenities'     => Amenity::active()->ordered()->get()->groupBy('group'),
            'user'          => Auth::user(),
        ];

        return view('public.post-property.index', $data);
    }

    /**
     * Store a newly submitted property from a registered user.
     * Properties are auto-set to 'pending' status for admin review.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to post a property.');
        }

        $validated = $request->validate([
            // Basic
            'title'             => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'property_type_id'  => 'required|exists:property_types,id',
            'listing_type'      => 'required|in:sale,rent',
            'description'       => 'required|string|min:20',

            // Pricing
            'price'             => 'required|numeric|min:0',

            // Location
            'state_id'          => 'required|exists:states,id',
            'city_id'           => 'required|exists:cities,id',
            'locality'          => 'required|string|max:200',
            'address'           => 'required|string|max:500',
            'pincode'           => 'nullable|string|max:10',

            // Config
            'bedrooms'          => 'nullable|integer|min:0|max:50',
            'bathrooms'         => 'nullable|integer|min:0|max:50',
            'balconies'         => 'nullable|integer|min:0|max:50',
            'area'              => 'nullable|numeric|min:0',
            'area_unit'         => 'nullable|in:sqft,sqm,sqyd,acre,hectare',
            'furnishing'        => 'nullable|in:unfurnished,semi-furnished,furnished',
            'facing'            => 'nullable|in:north,south,east,west,north-east,north-west,south-east,south-west',
            'ownership'         => 'nullable|in:freehold,leasehold,co-operative,power-of-attorney',
            'age_of_property'   => 'nullable|integer|min:0|max:200',
            'total_floors'      => 'nullable|integer|min:0',
            'floor_number'      => 'nullable|integer',
            'parking_spaces'    => 'nullable|integer|min:0',

            // Contact
            'contact_name'      => 'required|string|max:150',
            'contact_email'     => 'required|email|max:150',
            'contact_phone'     => 'required|string|max:20',
            'contact_whatsapp'  => 'nullable|string|max:20',

            // Media
            'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery_images.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documents.*'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',

            // Amenities
            'amenities'         => 'nullable|array',
            'amenities.*'       => 'exists:amenities,id',
        ], [
            'title.required'         => 'Property title is required.',
            'description.min'        => 'Please write at least 20 characters of description.',
            'price.required'         => 'Please enter the property price.',
            'locality.required'      => 'Please enter the locality.',
            'contact_phone.required' => 'A phone number is required so buyers can contact you.',
            'featured_image.max'     => 'Featured image must be under 5MB.',
        ]);

        DB::beginTransaction();
        try {
            // ===== Force controlled values for public submissions =====
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'pending';        // Always pending — admin approves
            $validated['is_active'] = false;         // Not live until approved
            $validated['is_featured'] = false;       // Only admin can feature
            $validated['is_verified'] = false;       // Only admin can verify
            $validated['is_negotiable'] = $request->boolean('is_negotiable', true);
            $validated['show_contact_publicly'] = true;
            $validated['currency'] = 'INR';

            // Upload featured image
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $this->upload($request->file('featured_image'), 'properties/featured');
            }

            $property = Property::create($validated);

            // Amenities
            if ($request->filled('amenities')) {
                $property->amenities()->sync($request->amenities);
            }

            // Gallery
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $i => $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->upload($file, 'properties/gallery');
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
                    $path = $this->upload($file, 'properties/documents');
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
                ->route('public.post-property.success', $property)
                ->with('success', 'Your property has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to submit property: ' . $e->getMessage());
        }
    }

    /**
     * Show the success page after property is submitted.
     */
    public function success(Property $property)
    {
        // Security: only the owner can see the success page
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        return view('public.post-property.success', compact('property'));
    }

    /**
     * List properties submitted by the logged-in user.
     */
    public function myProperties()
    {
        $properties = Property::where('user_id', Auth::id())
            ->with(['category', 'propertyType', 'city', 'state', 'primaryImage'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total'    => Property::where('user_id', Auth::id())->count(),
            'active'   => Property::where('user_id', Auth::id())->where('status', 'active')->count(),
            'pending'  => Property::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', Auth::id())->where('status', 'rejected')->count(),
        ];

        // return view('public.post-property.my-properties', compact('properties', 'stats'));
        return view('customer.properties.index', compact('properties', 'stats'));
    }

    /**
     * Upload helper.
     */
    protected function upload($file, string $folder): string
    {
        $name = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $name, 'public');
    }
}
