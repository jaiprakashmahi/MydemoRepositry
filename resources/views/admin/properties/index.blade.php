@extends('layouts.admin')

@section('title', 'All Properties - RKVIT REALESTATE')
@section('page-title', 'Property Management')
@section('page-subtitle', 'Manage all your property listings')

@section('content')

@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif

@if (session('error'))
<div class="flash-alert flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Properties</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
        </div>
        <div class="stat-value">{{ $stats['featured'] }}</div>
        <div class="stat-label">Featured</div>
    </div>
</div>

{{-- Main Panel --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-building"></i> All Properties</h3>
            <p>Manage your listings — {{ $properties->total() }} total</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('admin.properties.create') }}" class="btn-solid">
                <i class="fas fa-plus"></i> Add New Property
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div style="padding:18px 24px; border-bottom:1px solid var(--border); background:#fafbfc;">
        <form method="GET" action="{{ route('admin.properties.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:200px;">
                <i class="fas fa-search" style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search title, reference, address..."
                    style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none;">
            </div>

            <select name="status" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white;">
                <option value="">All Status</option>
                @foreach (['draft','pending','active','inactive','sold','rented','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <select name="listing_type" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white;">
                <option value="">Sale / Rent</option>
                <option value="sale" {{ request('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                <option value="rent" {{ request('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
            </select>

            <select name="category_id" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white;">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-outline"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.properties.index') }}" class="btn-outline"><i class="fas fa-redo"></i> Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $property)
                <tr>
                    <td>
                        <div class="property-cell">
                            @if ($property->primaryImage)
                            <img class="property-thumb" src="{{ $property->primaryImage->url }}" alt="{{ $property->title }}">
                            @elseif ($property->featured_image)
                            <img class="property-thumb" src="{{ asset('storage/' . $property->featured_image) }}" alt="{{ $property->title }}">
                            @else
                            <div class="property-thumb" style="background:#eef2f6; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                <i class="fas fa-image"></i>
                            </div>
                            @endif
                            <div class="property-cell-info">
                                <strong>{{ Str::limit($property->title, 40) }}</strong>
                                <span>{{ $property->reference_no }} • {{ $property->views_count }} views</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-info" style="background: {{ $property->propertyType->color ?? '#3b82f6' }}15; color: {{ $property->propertyType->color ?? '#3b82f6' }};">
                            <i class="{{ $property->propertyType->icon ?? 'fas fa-home' }}"></i>
                            {{ $property->propertyType->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size:0.82rem;">
                            <strong>{{ $property->city->name ?? '-' }}</strong>
                            <span style="color:var(--text-muted); font-size:0.72rem; display:block;">{{ $property->state->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td><strong style="color:var(--primary);">{{ $property->formatted_price }}</strong></td>
                    <td>
                        <span class="badge {{ $property->status_badge['class'] }}">
                            {{ $property->status_badge['label'] }}
                        </span>
                        @if ($property->is_featured)
                        <span class="badge badge-warning" style="margin-left:4px;">
                            <i class="fas fa-star"></i>
                        </span>
                        @endif
                    </td>
                    <td>
                        @if ($property->is_active)
                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                        <span class="badge badge-warning"><i class="fas fa-pause"></i></span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.properties.show', $property) }}" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.properties.edit', $property) }}" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.properties.toggle-status', $property) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn" title="{{ $property->is_active ? 'Deactivate' : 'Activate' }}"
                                    style="background:{{ $property->is_active ? '#fffbeb' : '#ecfdf5' }}; color:{{ $property->is_active ? '#d97706' : '#059669' }};">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Delete this property?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px 20px;">
                        <i class="fas fa-building" style="font-size:3rem; color:#cbd5e1; margin-bottom:14px;"></i>
                        <h4 style="font-size:1rem; color:var(--text-dark); margin-bottom:6px;">No properties found</h4>
                        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:16px;">Get started by adding your first property.</p>
                        <a href="{{ route('admin.properties.create') }}" class="btn-solid">
                            <i class="fas fa-plus"></i> Add New Property
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($properties->hasPages())
    <div style="padding:20px 24px; border-top:1px solid var(--border);">
        {{ $properties->links() }}
    </div>
    @endif
</div>

<style>
    .flash-alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .flash-error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .flash-close {
        margin-left: auto;
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        opacity: 0.6;
    }
</style>

@endsection