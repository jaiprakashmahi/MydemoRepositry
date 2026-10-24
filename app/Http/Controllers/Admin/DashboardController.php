<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\PropertyType;
use App\Models\Amenity;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties'   => Property::count(),
            'active_properties'  => Property::where('status', 'active')->count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'rejected_properties' => Property::where('status', 'rejected')->count(),
            'total_users'        => User::where('role', 'customer')->count(),
            'total_categories'   => Category::count(),
            'total_amenities'    => Amenity::count(),
            'total_views'        => Property::sum('views_count'),
        ];

        // Recent pending (needs approval)
        $pendingProperties = Property::where('status', 'pending')
            ->with(['category', 'propertyType', 'city', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::where('role', 'customer')
            ->latest()
            ->limit(5)
            ->get();

        // Recent activities (recently added properties)
        $recentProperties = Property::with(['user', 'city'])
            ->latest()
            ->limit(6)
            ->get();

        // Charts data — properties per category
        $categoryStats = Category::withCount('properties')
            ->orderByDesc('properties_count')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'pendingProperties',
            'recentUsers',
            'recentProperties',
            'categoryStats'
        ));
    }

    /**
     * Show admin profile page.
     */
    public function profile()
    {
        $user = Auth::user();

        // Admin-specific stats (platform-wide)
        $stats = [
            'total_properties'  => Property::count(),
            'active_properties' => Property::where('status', 'active')->count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'total_users'       => User::where('role', 'customer')->count(),
            'total_views'       => Property::sum('views_count'),
            'total_categories'  => Category::count(),
        ];

        return view('admin.profile', compact('user', 'stats'));
    }

    /**
     * Update admin basic profile (name, email, phone, avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'Please enter your name.',
            'email.required' => 'Email is required.',
            'email.unique'   => 'This email is already registered.',
            'avatar.image'   => 'Avatar must be an image.',
            'avatar.max'     => 'Avatar must be under 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile details updated successfully!');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'      => 'required|current_password',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required'         => 'Please enter your current password.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.required'                 => 'Please enter a new password.',
            'password.min'                      => 'New password must be at least 8 characters.',
            'password.confirmed'                => 'Password confirmation does not match.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.profile')
            ->with('password_success', 'Password changed successfully!');
    }
}
