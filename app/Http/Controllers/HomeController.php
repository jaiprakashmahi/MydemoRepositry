<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Dynamic homepage with categories + featured properties.
     */
    public function index()
    {
        // ===== Categories =====
        $categories = Category::active()
            ->ordered()
            ->withCount(['properties' => fn($q) => $q->where('status', 'active')->where('is_active', true)])
            ->limit(6)
            ->get();

        // ===== Featured Properties =====
        $featuredProperties = Property::where('status', 'active')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'propertyType', 'city', 'state', 'primaryImage'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($featuredProperties->isEmpty()) {
            $featuredProperties = Property::where('status', 'active')
                ->where('is_active', true)
                ->with(['category', 'propertyType', 'city', 'state', 'primaryImage'])
                ->latest('published_at')
                ->limit(3)
                ->get();
        }

        // ===== Property Types =====
        $propertyTypes = PropertyType::active()->ordered()->get();

        // ===== CITIES (dynamic — popular first, then all active) =====
        $popularCities = City::active()
            ->where('is_popular', true)
            ->withCount(['properties' => fn($q) => $q->where('status', 'active')])
            ->ordered()
            ->get();

        $allCities = City::active()
            ->withCount(['properties' => fn($q) => $q->where('status', 'active')])
            ->ordered()
            ->get();

        $states = State::active()->ordered()->get();

        // ===== Stats =====
        $stats = [
            'total_properties' => Property::where('status', 'active')->count(),
            'total_users'      => \App\Models\User::where('role', 'customer')->count(),
            'total_cities'     => City::where('is_active', true)->count(),
        ];

        return view('welcome', compact(
            'categories',
            'featuredProperties',
            'propertyTypes',
            'popularCities',
            'allCities',
            'states',
            'stats'
        ));
    }

    /**
     * Search results page with filters.
     */
    public function search(Request $request)
    {
        $query = Property::where('status', 'active')
            ->where('is_active', true)
            ->with(['category', 'propertyType', 'city', 'state', 'primaryImage']);

        // Keyword search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('locality', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('reference_no', 'like', "%{$q}%");
            });
        }

        // Filter: city
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter: property type
        if ($request->filled('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        // Filter: category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter: listing type (sale/rent)
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        // Filter: bedrooms
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', (int) $request->bedrooms);
        }

        // Filter: price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderByDesc('views_count');
                break;
            default:
                $query->latest('published_at');
        }

        $properties = $query->paginate(12)->withQueryString();

        // Dropdown data
        $propertyTypes = PropertyType::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        $categories = Category::active()->ordered()->get();

        return view('public.search-results', compact(
            'properties',
            'propertyTypes',
            'cities',
            'categories'
        ));
    }

    /**
     * Category-specific properties.
     */
    public function categoryProperties(Category $category)
    {
        $properties = Property::where('status', 'active')
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->with(['category', 'propertyType', 'city', 'state', 'primaryImage'])
            ->latest('published_at')
            ->paginate(12);

        $propertyTypes = PropertyType::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        $categories = Category::active()->ordered()->get();

        return view('public.search-results', [
            'properties'      => $properties,
            'propertyTypes'   => $propertyTypes,
            'cities'          => $cities,
            'categories'      => $categories,
            'categoryFilter'  => $category,
        ]);
    }

    /**
     * Public property detail view.
     */
    public function showProperty(Property $property)
    {
        // Only allow viewing active properties publicly
        if ($property->status !== 'active' || !$property->is_active) {
            abort(404, 'Property not available.');
        }

        $property->load([
            'category',
            'propertyType',
            'state',
            'city',
            'images',
            'documents' => fn($q) => $q->where('is_public', true),
            'amenities',
            'user',
        ]);

        $property->increment('views_count');

        // Similar properties
        $similarProperties = Property::where('status', 'active')
            ->where('is_active', true)
            ->where('id', '!=', $property->id)
            ->where(function ($q) use ($property) {
                $q->where('category_id', $property->category_id)
                    ->orWhere('city_id', $property->city_id);
            })
            ->with(['primaryImage', 'city', 'propertyType'])
            ->limit(3)
            ->get();

        return view('public.property-show', compact('property', 'similarProperties'));
    }
}
