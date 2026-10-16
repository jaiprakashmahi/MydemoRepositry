@extends('layouts.dashboard')

@section('title', 'Dashboard - RKVIT REALESTATE PRIVATE LIMITED')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Welcome back, ' . (Auth::user()->name ?? 'User') . ' 👋')

@section('content')

{{-- ==================== FLASH MESSAGES ==================== --}}
@if (session('success'))
<div style="padding:14px 18px; border-radius:10px; margin-bottom:20px; font-size:0.9rem; display:flex; align-items:center; gap:10px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ==================== STATS CARDS ==================== --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 12%</span>
        </div>
        <div class="stat-value">{{ $stats['total_properties'] ?? 248 }}</div>
        <div class="stat-label">Total Properties</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-eye"></i></div>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 8.4%</span>
        </div>
        <div class="stat-value">{{ number_format($stats['total_views'] ?? 12540) }}</div>
        <div class="stat-label">Total Views</div>
    </div>

    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
            <span class="stat-trend up"><i class="fas fa-arrow-up"></i> 22%</span>
        </div>
        <div class="stat-value">{{ $stats['new_inquiries'] ?? 186 }}</div>
        <div class="stat-label">New Inquiries</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-handshake"></i></div>
            <span class="stat-trend down"><i class="fas fa-arrow-down"></i> 2.1%</span>
        </div>
        <div class="stat-value">{{ $stats['closed_deals'] ?? 32 }}</div>
        <div class="stat-label">Closed Deals</div>
    </div>
</div>

{{-- ==================== PROPERTY CATEGORIES ==================== --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-layer-group"></i> Property Categories</h3>
            <p>Manage your property categories and listings</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('categories.index') }}" class="btn-outline">
                <i class="fas fa-cog"></i> Manage
            </a>
            <a href="{{ route('categories.create') }}" class="btn-solid">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    <div class="category-grid">
        @php
        $demoCategories = [
        ['name' => 'Apartments', 'icon' => 'fas fa-building', 'count' => 86],
        ['name' => 'Independent House', 'icon' => 'fas fa-home', 'count' => 42],
        ['name' => 'Villas', 'icon' => 'fas fa-tree', 'count' => 28],
        ['name' => 'Plots / Land', 'icon' => 'fas fa-map', 'count' => 54],
        ['name' => 'Office Spaces', 'icon' => 'fas fa-briefcase', 'count' => 18],
        ['name' => 'Shops / Retail', 'icon' => 'fas fa-store', 'count' => 20],
        ];

        $liveCategories = collect();
        if (class_exists(\App\Models\Category::class)) {
        try {
        $liveCategories = \App\Models\Category::active()->ordered()->limit(6)->get();
        } catch (\Exception $e) {
        $liveCategories = collect();
        }
        }
        @endphp

        @if ($liveCategories->count() > 0)
        @foreach ($liveCategories as $cat)
        <a href="{{ route('categories.show', $cat) }}" class="category-card">
            <span class="category-count">{{ $cat->id }}</span>
            <div class="category-icon" style="background: {{ $cat->color }}15; color: {{ $cat->color }};">
                <i class="{{ $cat->icon }}"></i>
            </div>
            <h4>{{ $cat->name }}</h4>
            <span>Active Listings</span>
        </a>
        @endforeach
        @else
        @foreach ($demoCategories as $cat)
        <div class="category-card">
            <span class="category-count">{{ $cat['count'] }}</span>
            <div class="category-icon"><i class="{{ $cat['icon'] }}"></i></div>
            <h4>{{ $cat['name'] }}</h4>
            <span>Active Listings</span>
        </div>
        @endforeach
        @endif
    </div>
</div>

{{-- ==================== RECENT PROPERTIES + ACTIVITY ==================== --}}
<div class="grid-2">

    {{-- Recent Properties --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <h3><i class="fas fa-building"></i> Recent Properties</h3>
                <p>Your latest property listings</p>
            </div>
            <div class="panel-actions">
                <button class="btn-outline">
                    <i class="fas fa-eye"></i> View All
                </button>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <img class="property-thumb" src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=100&h=100&fit=crop" alt="Property">
                                <div class="property-cell-info">
                                    <strong>3 BHK Luxury Apartment</strong>
                                    <span>Kankarbagh, Patna</span>
                                </div>
                            </div>
                        </td>
                        <td><strong>₹ 85 L</strong></td>
                        <td><span class="badge badge-success"><i class="fas fa-check"></i> Active</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <img class="property-thumb" src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=100&h=100&fit=crop" alt="Property">
                                <div class="property-cell-info">
                                    <strong>4 BHK Premium Villa</strong>
                                    <span>Bailey Road, Patna</span>
                                </div>
                            </div>
                        </td>
                        <td><strong>₹ 2.1 Cr</strong></td>
                        <td><span class="badge badge-success"><i class="fas fa-check"></i> Active</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <img class="property-thumb" src="https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=100&h=100&fit=crop" alt="Property">
                                <div class="property-cell-info">
                                    <strong>2 BHK Modern Flat</strong>
                                    <span>Rajendra Nagar, Patna</span>
                                </div>
                            </div>
                        </td>
                        <td><strong>₹ 52 L</strong></td>
                        <td><span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <img class="property-thumb" src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=100&h=100&fit=crop" alt="Property">
                                <div class="property-cell-info">
                                    <strong>Office Space</strong>
                                    <span>Fraser Road, Patna</span>
                                </div>
                            </div>
                        </td>
                        <td><strong>₹ 1.2 Cr</strong></td>
                        <td><span class="badge badge-info"><i class="fas fa-star"></i> Featured</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <h3><i class="fas fa-bolt"></i> Recent Activity</h3>
                <p>What's happening</p>
            </div>
        </div>

        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon red"><i class="fas fa-plus"></i></div>
                <div class="activity-content">
                    <p><strong>New property added</strong> — 3 BHK Apartment in Kankarbagh</p>
                    <span><i class="far fa-clock"></i> 12 minutes ago</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon blue"><i class="fas fa-envelope"></i></div>
                <div class="activity-content">
                    <p><strong>New inquiry</strong> from Rahul Kumar for Premium Villa</p>
                    <span><i class="far fa-clock"></i> 1 hour ago</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="activity-content">
                    <p><strong>Property approved</strong> — 2 BHK Flat in Rajendra Nagar</p>
                    <span><i class="far fa-clock"></i> 3 hours ago</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon purple"><i class="fas fa-user-plus"></i></div>
                <div class="activity-content">
                    <p><strong>New customer registered</strong> — Priya Sharma joined</p>
                    <span><i class="far fa-clock"></i> 5 hours ago</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon red"><i class="fas fa-calendar-check"></i></div>
                <div class="activity-content">
                    <p><strong>Site visit scheduled</strong> — 4 BHK Villa, Bailey Road</p>
                    <span><i class="far fa-clock"></i> Yesterday</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon blue"><i class="fas fa-edit"></i></div>
                <div class="activity-content">
                    <p><strong>Property updated</strong> — price revised for Office Space</p>
                    <span><i class="far fa-clock"></i> 2 days ago</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== ALL PROPERTIES TABLE ==================== --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-list"></i> All Properties</h3>
            <p>Complete list of your property listings</p>
        </div>
        <div class="panel-actions">
            <button class="btn-outline">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn-outline">
                <i class="fas fa-file-export"></i> Export
            </button>
            <button class="btn-solid">
                <i class="fas fa-plus"></i> Add New Property
            </button>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Listed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="property-cell">
                            <img class="property-thumb" src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=100&h=100&fit=crop" alt="Property">
                            <div class="property-cell-info">
                                <strong>3 BHK Luxury Apartment</strong>
                                <span>1650 sqft</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info">Apartment</span></td>
                    <td>Kankarbagh, Patna</td>
                    <td><strong>₹ 85 L</strong></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>12 Jan 2025</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="property-cell">
                            <img class="property-thumb" src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=100&h=100&fit=crop" alt="Property">
                            <div class="property-cell-info">
                                <strong>4 BHK Premium Villa</strong>
                                <span>2800 sqft</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-purple">Villa</span></td>
                    <td>Bailey Road, Patna</td>
                    <td><strong>₹ 2.1 Cr</strong></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>08 Jan 2025</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="property-cell">
                            <img class="property-thumb" src="https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=100&h=100&fit=crop" alt="Property">
                            <div class="property-cell-info">
                                <strong>2 BHK Modern Flat</strong>
                                <span>1100 sqft</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info">Apartment</span></td>
                    <td>Rajendra Nagar, Patna</td>
                    <td><strong>₹ 52 L</strong></td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>05 Jan 2025</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="property-cell">
                            <img class="property-thumb" src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=100&h=100&fit=crop" alt="Property">
                            <div class="property-cell-info">
                                <strong>Premium Office Space</strong>
                                <span>2200 sqft</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-success">Commercial</span></td>
                    <td>Fraser Road, Patna</td>
                    <td><strong>₹ 1.2 Cr</strong></td>
                    <td><span class="badge badge-info">Featured</span></td>
                    <td>02 Jan 2025</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="property-cell">
                            <img class="property-thumb" src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=100&h=100&fit=crop" alt="Property">
                            <div class="property-cell-info">
                                <strong>Residential Plot</strong>
                                <span>3000 sqft</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-warning">Plot</span></td>
                    <td>Danapur, Patna</td>
                    <td><strong>₹ 45 L</strong></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>28 Dec 2024</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn view"><i class="fas fa-eye"></i></button>
                            <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection