<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\PropertyType;
use App\Models\Amenity;
use App\Models\User;
use App\Models\City;

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
}
