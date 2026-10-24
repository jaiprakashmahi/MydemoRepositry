<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total'    => Property::where('user_id', $userId)->count(),
            'active'   => Property::where('user_id', $userId)->where('status', 'active')->count(),
            'pending'  => Property::where('user_id', $userId)->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        $recentProperties = Property::where('user_id', $userId)
            ->with(['category', 'propertyType', 'city', 'primaryImage'])
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentProperties'));
    }

    public function myProperties(Request $request)
    {
        $userId = Auth::id();

        $query = Property::where('user_id', $userId)
            ->with(['category', 'propertyType', 'city', 'primaryImage']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('reference_no', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowed = ['title', 'price', 'created_at', 'status'];
        if (!in_array($sort, $allowed)) $sort = 'created_at';
        $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');

        $properties = $query->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total'    => Property::where('user_id', $userId)->count(),
            'active'   => Property::where('user_id', $userId)->where('status', 'active')->count(),
            'pending'  => Property::where('user_id', $userId)->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', $userId)->where('status', 'rejected')->count(),
            'draft'    => Property::where('user_id', $userId)->where('status', 'draft')->count(),
        ];

        // ===== FIX: Pass categories, property types, and cities for filters =====
        $categories    = Category::active()->ordered()->get();
        $propertyTypes = PropertyType::active()->ordered()->get();
        $cities        = City::active()->ordered()->get();

        return view('customer.properties.index', compact(
            'properties',
            'stats',
            'categories',
            'propertyTypes',
            'cities'
        ));
    }

    public function createProperty()
    {
        $data = $this->formData();
        return view('customer.properties.create', $data);
    }

    public function storeProperty(Request $request)
    {
        return app(PublicPropertyController::class)->store($request);
    }

    public function editProperty(Property $property)
    {
        $this->authorizeOwner($property);
        $data = $this->formData();
        $data['property'] = $property->load(['images', 'documents', 'amenities']);
        return view('customer.properties.edit', $data);
    }

    public function updateProperty(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'property_type_id' => 'required|exists:property_types,id',
            'listing_type'     => 'required|in:sale,rent',
            'description'      => 'required|string|min:20',
            'price'            => 'required|numeric|min:0',
            'state_id'         => 'required|exists:states,id',
            'city_id'          => 'required|exists:cities,id',
            'locality'         => 'required|string|max:200',
            'address'          => 'required|string|max:500',
            'contact_name'     => 'required|string|max:150',
            'contact_email'    => 'required|email|max:150',
            'contact_phone'    => 'required|string|max:20',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'amenities'        => 'nullable|array',
            'amenities.*'      => 'exists:amenities,id',
        ]);

        DB::beginTransaction();
        try {
            // If customer edits, revert to pending for re-approval
            $validated['status'] = 'pending';
            $validated['is_active'] = false;
            $validated['is_negotiable'] = $request->boolean('is_negotiable', true);

            if ($request->hasFile('featured_image')) {
                if ($property->featured_image) Storage::disk('public')->delete($property->featured_image);
                $validated['featured_image'] = $this->upload($request->file('featured_image'), 'properties/featured');
            }

            $property->update($validated);
            $property->amenities()->sync($request->amenities ?? []);

            if ($request->hasFile('gallery_images')) {
                $maxSort = $property->images()->max('sort_order') ?? 0;
                foreach ($request->file('gallery_images') as $i => $file) {
                    if (!$file->isValid()) continue;
                    $path = $this->upload($file, 'properties/gallery');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image'       => $path,
                        'is_primary'  => false,
                        'image_type'  => 'gallery',
                        'sort_order'  => $maxSort + $i + 1,
                        'file_size'   => (int) ($file->getSize() / 1024),
                        'mime_type'   => $file->getMimeType(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('customer.properties.index')
                ->with('success', 'Property updated! It will be re-reviewed before going live.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function destroyProperty(Property $property)
    {
        $this->authorizeOwner($property);
        $property->delete();
        return back()->with('success', 'Property deleted successfully!');
    }

    public function showProperty(Property $property)
    {
        $this->authorizeOwner($property);
        $property->load(['category', 'propertyType', 'state', 'city', 'images', 'documents', 'amenities', 'user']);
        return view('customer.properties.show', compact('property'));
    }

    /**
     * Show profile page.
     */
    public function profile()
    {
        $user = Auth::user();

        $stats = [
            'total_properties' => Property::where('user_id', $user->id)->count(),
            'active_properties' => Property::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_views'      => Property::where('user_id', $user->id)->sum('views_count'),
            'total_inquiries'  => Property::where('user_id', $user->id)->sum('inquiries_count'),
        ];

        return view('customer.profile', compact('user', 'stats'));
    }

    /**
     * Update basic profile details (name, email, phone, avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'Please enter your name.',
            'email.required' => 'Email is required.',
            'email.unique'   => 'This email is already registered.',
            'avatar.image'   => 'Avatar must be an image.',
            'avatar.max'     => 'Avatar must be under 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile details updated successfully!');
    }

    /**
     * Update password only.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required'       => 'Please enter your current password.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.required'               => 'Please enter a new password.',
            'password.min'                    => 'New password must be at least 8 characters.',
            'password.confirmed'              => 'Password confirmation does not match.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('customer.profile')
            ->with('password_success', 'Password changed successfully!');
    }

    // ===== Helpers =====
    protected function authorizeOwner(Property $property): void
    {
        if ($property->user_id !== Auth::id()) {
            abort(403, 'You do not own this property.');
        }
    }

    protected function formData(): array
    {
        return [
            'categories'    => Category::active()->ordered()->get(),
            'propertyTypes' => PropertyType::active()->ordered()->get(),
            'states'        => State::active()->ordered()->get(),
            'cities'        => City::active()->ordered()->get(),
            'amenities'     => Amenity::active()->ordered()->get()->groupBy('group'),
        ];
    }

    protected function upload($file, string $folder): string
    {
        $name = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $name, 'public');
    }
}
