@extends('layouts.customer')

@section('title', $property->title . ' - My Property')
@section('page-title', 'Property Details')
@section('page-subtitle', 'View and manage your property listing')

@section('content')

{{-- ==================== FLASH MESSAGES ==================== --}}
@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="flash-alert flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ==================== HEADER ACTIONS ==================== --}}
<div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <a href="{{ route('customer.properties.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
        <i class="fas fa-arrow-left"></i> Back to My Properties
    </a>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        {{-- ✅ CUSTOMER-SAFE actions only --}}
        <a href="{{ route('customer.properties.edit', $property) }}" class="btn-solid">
            <i class="fas fa-edit"></i> Edit Property
        </a>
        <form action="{{ route('customer.properties.destroy', $property) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.');"
            style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn-outline" style="color:#dc2626; border-color:#fecaca;">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

{{-- ==================== STATUS BANNER ==================== --}}
@php
$statusMap = [
'active' => ['bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-check-circle', 'label' => 'Active & Live'],
'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fa-clock', 'label' => 'Pending Review'],
'draft' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => 'fa-file', 'label' => 'Draft'],
'inactive' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'icon' => 'fa-pause-circle', 'label' => 'Inactive'],
'sold' => ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => 'fa-handshake', 'label' => 'Sold'],
'rented' => ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => 'fa-key', 'label' => 'Rented'],
'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => 'fa-times-circle', 'label' => 'Rejected'],
];
$currentStatus = $statusMap[$property->status ?? 'draft'] ?? $statusMap['draft'];
@endphp

<div style="background:{{ $currentStatus['bg'] }}; border:1px solid {{ $currentStatus['color'] }}30; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
    <i class="fas {{ $currentStatus['icon'] }}" style="font-size:1.5rem; color:{{ $currentStatus['color'] }};"></i>
    <div style="flex:1;">
        <div style="font-weight:700; font-size:0.95rem; color:{{ $currentStatus['color'] }};">
            {{ $currentStatus['label'] }}
        </div>
        <div style="font-size:0.8rem; color:var(--text-soft); margin-top:2px;">
            @if ($property->status === 'pending')
            Your property is being reviewed. It usually takes up to 24 hours for approval.
            @elseif ($property->status === 'active')
            Your property is live and visible to buyers on the public site.
            @elseif ($property->status === 'rejected')
            This property was not approved. Please edit the details and resubmit, or contact support.
            @elseif ($property->status === 'draft')
            This property is saved as draft. Edit and submit it for review.
            @else
            Your property is currently {{ $property->status }}.
            @endif
        </div>
    </div>
</div>

{{-- ==================== HERO IMAGE ==================== --}}
@php
$heroImg = null;
if ($property->featured_image) {
$heroImg = asset('storage/' . $property->featured_image);
} elseif ($property->primaryImage) {
$heroImg = $property->primaryImage->url;
} elseif ($property->images && $property->images->count() > 0) {
$heroImg = $property->images->first()->url;
}
@endphp

@if ($heroImg)
<div style="border-radius:16px; overflow:hidden; height:360px; margin-bottom:20px; box-shadow:var(--shadow-md); position:relative;">
    <img src="{{ $heroImg }}" alt="{{ $property->title }}" style="width:100%; height:100%; object-fit:cover;">
    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.7), transparent 50%);"></div>
    <div style="position:absolute; bottom:20px; left:24px; right:24px; color:white;">
        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
            <span class="badge" style="background:rgba(255,255,255,0.2); color:white; backdrop-filter:blur(8px);">
                {{ ucfirst($property->listing_type ?? 'sale') }}
            </span>
            @if ($property->is_featured)
            <span class="badge" style="background:rgba(245,158,11,0.9); color:white;">
                <i class="fas fa-star"></i> Featured
            </span>
            @endif
            @if ($property->is_verified)
            <span class="badge" style="background:rgba(16,185,129,0.9); color:white;">
                <i class="fas fa-shield-alt"></i> Verified
            </span>
            @endif
        </div>
        <h1 style="font-size:1.7rem; font-weight:800; margin-bottom:4px; letter-spacing:-0.02em;">
            {{ $property->title ?? 'Untitled Property' }}
        </h1>
        <p style="font-size:0.9rem; opacity:0.9;">
            <i class="fas fa-map-marker-alt"></i>
            {{ $property->locality ?? '' }}{{ $property->locality && $property->city ? ', ' : '' }}{{ $property->city->name ?? '' }}
            @if ($property->state)
            , {{ $property->state->name }}
            @endif
        </p>
    </div>
</div>
@endif

{{-- ==================== PRICE + QUICK STATS ==================== --}}
<div class="panel" style="margin-bottom:20px;">
    <div style="padding:22px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
        <div>
            <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; font-weight:700; margin-bottom:4px;">Price</div>
            <div style="font-size:2rem; font-weight:800; color:var(--primary); letter-spacing:-0.02em;">
                {{ $property->formatted_price ?? ('₹ ' . number_format($property->price ?? 0)) }}
            </div>
            @if ($property->is_negotiable)
            <span style="font-size:0.78rem; color:var(--text-muted);">Negotiable</span>
            @endif
        </div>
        <div style="display:flex; gap:28px; flex-wrap:wrap;">
            @if ($property->bedrooms > 0)
            <div style="text-align:center;">
                <div style="font-size:1.4rem; font-weight:800; color:var(--text-dark);">{{ $property->bedrooms }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">
                    <i class="fas fa-bed"></i> Beds
                </div>
            </div>
            @endif
            @if ($property->bathrooms > 0)
            <div style="text-align:center;">
                <div style="font-size:1.4rem; font-weight:800; color:var(--text-dark);">{{ $property->bathrooms }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">
                    <i class="fas fa-bath"></i> Baths
                </div>
            </div>
            @endif
            @if ($property->area)
            <div style="text-align:center;">
                <div style="font-size:1.4rem; font-weight:800; color:var(--text-dark);">{{ number_format($property->area) }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">
                    <i class="fas fa-vector-square"></i> {{ strtoupper($property->area_unit ?? 'SQFT') }}
                </div>
            </div>
            @endif
            @if ($property->parking_spaces)
            <div style="text-align:center;">
                <div style="font-size:1.4rem; font-weight:800; color:var(--text-dark);">{{ $property->parking_spaces }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">
                    <i class="fas fa-parking"></i> Parking
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ==================== MAIN LAYOUT ==================== --}}
<div style="display:grid; grid-template-columns:1.6fr 1fr; gap:20px; margin-bottom:24px;">

    {{-- LEFT COLUMN --}}
    <div>
        {{-- Description --}}
        @if ($property->description)
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                </div>
            </div>
            <div style="padding:22px;">
                <p style="font-size:0.9rem; color:var(--text-soft); line-height:1.7;">
                    {{ $property->description }}
                </p>
                @if ($property->highlights)
                <div style="margin-top:18px; padding-top:18px; border-top:1px solid var(--border);">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:10px;">Highlights</h4>
                    <ul style="padding-left:20px; font-size:0.88rem; color:var(--text-soft);">
                        @foreach (explode("\n", $property->highlights) as $line)
                        @if (trim($line))
                        <li style="margin-bottom:6px;">{{ trim($line) }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Property Details --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-list-alt"></i> Property Details</h3>
                </div>
            </div>
            <div style="padding:22px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                @php
                $details = [
                ['Reference No', $property->reference_no ?? '-'],
                ['Category', $property->category->name ?? '-'],
                ['Property Type', $property->propertyType->name ?? '-'],
                ['Listing Type', ucfirst($property->listing_type ?? '-')],
                ['Furnishing', ucwords(str_replace('-', ' ', $property->furnishing ?? '-'))],
                ['Facing', $property->facing ? ucwords(str_replace('-', ' ', $property->facing)) : '-'],
                ['Ownership', $property->ownership ? ucfirst(str_replace('-', ' ', $property->ownership)) : '-'],
                ['Age', $property->age_of_property ? $property->age_of_property . ' years' : '-'],
                ['Floor Number', $property->floor_number ?? '-'],
                ['Total Floors', $property->total_floors ?? '-'],
                ['Area', $property->area ? $property->area . ' ' . ($property->area_unit ?? 'sqft') : '-'],
                ['Carpet Area', $property->carpet_area ? $property->carpet_area . ' ' . ($property->area_unit ?? 'sqft') : '-'],
                ];
                @endphp
                @foreach ($details as $d)
                <div style="padding:12px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                    <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">{{ $d[0] }}</div>
                    <div style="font-size:0.88rem; font-weight:600; color:var(--text-dark);">{{ $d[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Amenities --}}
        @if ($property->amenities && $property->amenities->count() > 0)
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-star"></i> Amenities ({{ $property->amenities->count() }})</h3>
                </div>
            </div>
            <div style="padding:22px; display:grid; grid-template-columns:repeat(auto-fill, minmax(170px, 1fr)); gap:10px;">
                @foreach ($property->amenities as $amenity)
                <div style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                    <i class="{{ $amenity->icon ?? 'fas fa-check' }}" style="color:{{ $amenity->color ?? 'var(--primary)' }};"></i>
                    <span style="font-size:0.85rem;">{{ $amenity->name ?? 'Amenity' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Gallery --}}
        @if ($property->images && $property->images->count() > 0)
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-images"></i> Gallery ({{ $property->images->count() }})</h3>
                </div>
            </div>
            <div style="padding:22px; display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:12px;">
                @foreach ($property->images as $img)
                <div style="border-radius:10px; overflow:hidden; border:1px solid var(--border); aspect-ratio:4/3;">
                    <img src="{{ $img->url ?? asset('storage/'.$img->image) }}" alt="Property image" style="width:100%; height:100%; object-fit:cover;">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Documents --}}
        @if ($property->documents && $property->documents->count() > 0)
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-file-pdf"></i> Documents ({{ $property->documents->count() }})</h3>
                </div>
            </div>
            <div style="padding:22px;">
                @foreach ($property->documents as $doc)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border); margin-bottom:8px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <i class="fas fa-file-pdf" style="color:#dc2626; font-size:1.2rem;"></i>
                        <div>
                            <strong style="font-size:0.85rem;">{{ $doc->title ?? $doc->file_name ?? 'Document' }}</strong>
                            <div style="font-size:0.72rem; color:var(--text-muted);">
                                {{ strtoupper($doc->document_type ?? 'FILE') }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                        class="action-btn view" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT COLUMN --}}
    <div>
        {{-- Contact Card --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-address-card"></i> Contact Info</h3>
                </div>
            </div>
            <div style="padding:22px;">
                @if ($property->contact_name)
                <div style="margin-bottom:14px;">
                    <div style="font-size:0.68rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Contact Name</div>
                    <div style="font-size:0.9rem; font-weight:600;">{{ $property->contact_name }}</div>
                </div>
                @endif
                @if ($property->contact_email)
                <div style="margin-bottom:14px;">
                    <div style="font-size:0.68rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Email</div>
                    <div style="font-size:0.9rem; font-weight:600;">
                        <i class="fas fa-envelope" style="color:var(--primary);"></i> {{ $property->contact_email }}
                    </div>
                </div>
                @endif
                @if ($property->contact_phone)
                <div style="margin-bottom:14px;">
                    <div style="font-size:0.68rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Phone</div>
                    <div style="font-size:0.9rem; font-weight:600;">
                        <i class="fas fa-phone" style="color:var(--primary);"></i> {{ $property->contact_phone }}
                    </div>
                </div>
                @endif
                @if ($property->contact_whatsapp)
                <div>
                    <div style="font-size:0.68rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">WhatsApp</div>
                    <div style="font-size:0.9rem; font-weight:600;">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i> {{ $property->contact_whatsapp }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Stats Card --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-chart-bar"></i> Performance</h3>
                </div>
            </div>
            <div style="padding:22px;">
                <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border); margin-bottom:10px;">
                    <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Views</div>
                    <div style="font-size:1.3rem; font-weight:800; color:var(--text-dark);">
                        {{ number_format($property->views_count ?? 0) }}
                    </div>
                </div>
                <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border); margin-bottom:10px;">
                    <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Inquiries</div>
                    <div style="font-size:1.3rem; font-weight:800; color:var(--text-dark);">
                        {{ number_format($property->inquiries_count ?? 0) }}
                    </div>
                </div>
                <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                    <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Listed On</div>
                    <div style="font-size:0.88rem; font-weight:700;">
                        {{ $property->created_at ? $property->created_at->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== BOTTOM ACTIONS ==================== --}}
<div style="display:flex; gap:12px; justify-content:flex-end; flex-wrap:wrap; margin-bottom:24px;">
    <a href="{{ route('customer.properties.index') }}" class="btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
    <a href="{{ route('customer.properties.edit', $property) }}" class="btn-solid">
        <i class="fas fa-edit"></i> Edit Property
    </a>
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

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .panel>div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection