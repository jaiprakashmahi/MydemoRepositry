@extends('layouts.customer')

@section('title', 'My Properties - RKVIT REALESTATE')
@section('page-title', 'My Properties')
@section('page-subtitle', 'Manage all your property listings')

@section('content')

{{-- ==================== FLASH MESSAGES ==================== --}}
@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="flash-close" onclick="this.parentElement.remove()" style="margin-left:auto; background:none; border:none; color:inherit; cursor:pointer; opacity:0.6;">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if (session('error'))
<div class="flash-alert flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ==================== STATS ==================== --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-label">Total Properties</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active'] ?? 0 }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['rejected'] ?? 0 }}</div>
        <div class="stat-label">Rejected</div>
    </div>
</div>

{{-- ==================== MAIN PANEL ==================== --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-building"></i> All My Properties</h3>
            <p>Manage your listings — {{ $properties->total() ?? 0 }} total</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('customer.properties.create') }}" class="btn-solid">
                <i class="fas fa-plus"></i> Post New Property
            </a>
        </div>
    </div>

    {{-- ==================== FILTERS ==================== --}}
    <div style="padding:18px 24px; border-bottom:1px solid var(--border); background:#fafbfc;">
        <form method="GET" action="{{ route('customer.properties.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">

            {{-- Search --}}
            <div style="position:relative; flex:1; min-width:200px;">
                <i class="fas fa-search" style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search title, reference..."
                    style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none;">
            </div>

            {{-- Status --}}
            <select name="status" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white; outline:none;">
                <option value="">All Status</option>
                @foreach (['draft', 'pending', 'active', 'inactive', 'sold', 'rented', 'rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            {{-- Category (safe — uses isset) --}}
            @isset($categories)
            @if ($categories->count() > 0)
            <select name="category_id" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white; outline:none;">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
            @endif
            @endisset

            {{-- Sort --}}
            <select name="sort" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white; outline:none;">
                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Newest</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
            </select>

            <button type="submit" class="btn-outline"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('customer.properties.index') }}" class="btn-outline"><i class="fas fa-redo"></i> Reset</a>
        </form>
    </div>

    {{-- ==================== TABLE ==================== --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $property)
                <tr>
                    {{-- Property Cell --}}
                    <td>
                        <div class="property-cell">
                            @php
                            $img = null;
                            if ($property->primaryImage) {
                            $img = $property->primaryImage->url;
                            } elseif ($property->featured_image) {
                            $img = asset('storage/' . $property->featured_image);
                            }
                            @endphp
                            @if ($img)
                            <img src="{{ $img }}" class="property-thumb" alt="{{ $property->title }}">
                            @else
                            <div class="property-thumb" style="display:flex; align-items:center; justify-content:center; color:#94a3b8; background:#eef2f6;">
                                <i class="fas fa-image"></i>
                            </div>
                            @endif
                            <div class="property-cell-info">
                                <strong>{{ Str::limit($property->title ?? 'Untitled', 40) }}</strong>
                                <span>{{ $property->reference_no ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Type --}}
                    <td>
                        <span class="badge badge-info">
                            {{ $property->propertyType->name ?? 'N/A' }}
                        </span>
                    </td>

                    {{-- Location --}}
                    <td>
                        <div style="font-size:0.82rem;">
                            <strong>{{ $property->city->name ?? '-' }}</strong>
                            <span style="color:var(--text-muted); font-size:0.72rem; display:block;">
                                {{ $property->locality ?? '' }}
                            </span>
                        </div>
                    </td>

                    {{-- Price --}}
                    <td>
                        <strong style="color:var(--primary);">
                            {{ $property->formatted_price ?? ('₹ ' . number_format($property->price ?? 0)) }}
                        </strong>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php
                        $statusBadge = [
                        'active' => ['class' => 'badge-success', 'label' => 'Active'],
                        'pending' => ['class' => 'badge-warning', 'label' => 'Pending'],
                        'draft' => ['class' => 'badge-info', 'label' => 'Draft'],
                        'inactive' => ['class' => 'badge-warning', 'label' => 'Inactive'],
                        'sold' => ['class' => 'badge-purple', 'label' => 'Sold'],
                        'rented' => ['class' => 'badge-purple', 'label' => 'Rented'],
                        'rejected' => ['class' => 'badge-danger', 'label' => 'Rejected'],
                        ][$property->status ?? 'draft'] ?? ['class' => 'badge-info', 'label' => ucfirst($property->status ?? 'Draft')];
                        @endphp
                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                    </td>

                    {{-- Views --}}
                    <td>
                        <span style="font-size:0.82rem; color:var(--text-soft);">
                            <i class="fas fa-eye" style="color:var(--primary); font-size:0.72rem;"></i>
                            {{ number_format($property->views_count ?? 0) }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('customer.properties.show', $property) }}"
                                class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('customer.properties.edit', $property) }}"
                                class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('customer.properties.destroy', $property) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Delete this property? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:60px 20px;">
                        <div style="display:inline-flex; width:80px; height:80px; border-radius:50%; background:#fef2f2; align-items:center; justify-content:center; margin-bottom:16px;">
                            <i class="fas fa-building" style="font-size:2rem; color:var(--primary); opacity:0.6;"></i>
                        </div>
                        <h4 style="font-size:1.1rem; color:var(--text-dark); margin-bottom:8px;">
                            @if (request()->hasAny(['search', 'status', 'category_id']))
                            No properties match your filters
                            @else
                            You haven't posted any properties yet
                            @endif
                        </h4>
                        <p style="color:var(--text-muted); font-size:0.88rem; margin-bottom:20px; max-width:400px; margin-left:auto; margin-right:auto;">
                            @if (request()->hasAny(['search', 'status', 'category_id']))
                            Try adjusting your search or clearing the filters.
                            @else
                            Post your first property to start reaching thousands of buyers.
                            @endif
                        </p>
                        @if (request()->hasAny(['search', 'status', 'category_id']))
                        <a href="{{ route('customer.properties.index') }}" class="btn-outline">
                            <i class="fas fa-redo"></i> Clear Filters
                        </a>
                        @else
                        <a href="{{ route('customer.properties.create') }}" class="btn-solid">
                            <i class="fas fa-plus"></i> Post Your First Property
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== PAGINATION ==================== --}}
    @if ($properties->hasPages())
    <div style="padding:20px 24px; border-top:1px solid var(--border);">
        {{ $properties->links() }}
    </div>
    @endif
</div>

{{-- ==================== STYLES ==================== --}}
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

    .badge-purple {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .badge-warning {
        background: #fffbeb;
        color: #d97706;
    }
</style>

@endsection