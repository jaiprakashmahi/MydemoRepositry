@extends('layouts.admin')

@section('title', $property->title . ' - Admin')
@section('page-title', 'Property Details')
@section('page-subtitle', 'Review and manage this property')

@section('content')

<div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <a href="{{ route('admin.properties.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
        <i class="fas fa-arrow-left"></i> Back to Properties
    </a>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        @if ($property->status === 'pending')
        <form action="{{ route('admin.properties.approve', $property) }}" method="POST" style="display:inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn-solid" style="background:linear-gradient(135deg,#10b981,#059669);">
                <i class="fas fa-check"></i> Approve
            </button>
        </form>
        <form action="{{ route('admin.properties.reject', $property) }}" method="POST" style="display:inline;"
            onsubmit="return confirm('Reject this property?');">
            @csrf @method('PATCH')
            <button type="submit" class="btn-outline" style="color:#dc2626; border-color:#fecaca;">
                <i class="fas fa-times"></i> Reject
            </button>
        </form>
        @endif
        <a href="{{ route('admin.properties.edit', $property) }}" class="btn-outline">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

{{-- Hero --}}
<div class="panel" style="overflow:hidden;">
    @php
    $heroImg = $property->featured_image
    ? asset('storage/'.$property->featured_image)
    : ($property->primaryImage ? $property->primaryImage->url
    : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&h=500&fit=crop');
    @endphp
    <div style="position:relative; height:320px; overflow:hidden;">
        <img src="{{ $heroImg }}" style="width:100%; height:100%; object-fit:cover;">
        <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.8), transparent 60%);"></div>
        <div style="position:absolute; bottom:24px; left:28px; right:28px; color:white;">
            <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
                @php $badge = $property->status_badge; @endphp
                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                <span class="badge badge-info">{{ ucfirst($property->listing_type) }}</span>
                @if ($property->is_featured)<span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>@endif
                @if ($property->is_verified)<span class="badge badge-success"><i class="fas fa-shield-alt"></i> Verified</span>@endif
            </div>
            <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:6px;">{{ $property->title }}</h1>
            <p style="font-size:0.9rem; opacity:0.9;">
                <i class="fas fa-map-marker-alt"></i>
                {{ $property->locality }}, {{ $property->city->name ?? '' }}, {{ $property->state->name ?? '' }}
            </p>
        </div>
    </div>

    <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; border-bottom:1px solid var(--border);">
        <div>
            <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Price</div>
            <div style="font-size:1.6rem; font-weight:800; color:var(--primary);">{{ $property->formatted_price }}</div>
        </div>
        <div>
            <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Owner</div>
            <div style="font-size:0.95rem; font-weight:600;">{{ $property->user->name ?? '-' }}</div>
            <div style="font-size:0.78rem; color:var(--text-muted);">{{ $property->user->email ?? '' }}</div>
        </div>
        <div>
            <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Reference</div>
            <div style="font-size:0.95rem; font-weight:700; color:var(--primary);">{{ $property->reference_no }}</div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div style="padding:24px; display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:14px;">
        @foreach ([
        ['Category', $property->category->name ?? '-'],
        ['Type', $property->propertyType->name ?? '-'],
        ['Bedrooms', $property->bedrooms],
        ['Bathrooms', $property->bathrooms],
        ['Balconies', $property->balconies],
        ['Area', $property->area . ' ' . $property->area_unit],
        ['Furnishing', ucwords(str_replace('-', ' ', $property->furnishing ?? '-'))],
        ['Facing', $property->facing ? ucwords(str_replace('-', ' ', $property->facing)) : '-'],
        ['Ownership', $property->ownership ? ucfirst(str_replace('-', ' ', $property->ownership)) : '-'],
        ['Parking', $property->parking_spaces ?: '-'],
        ['Age', $property->age_of_property ? $property->age_of_property . ' years' : '-'],
        ['Views', number_format($property->views_count)],
        ] as $detail)
        <div style="padding:12px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
            <div style="font-size:0.68rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">{{ $detail[0] }}</div>
            <div style="font-size:0.88rem; font-weight:600;">{{ $detail[1] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Description --}}
@if ($property->description)
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-align-left"></i> Description</h3>
        </div>
    </div>
    <div style="padding:24px;">
        <p style="font-size:0.9rem; color:var(--text-soft); line-height:1.7;">{{ $property->description }}</p>
    </div>
</div>
@endif

{{-- Contact --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-address-card"></i> Contact Information</h3>
        </div>
    </div>
    <div style="padding:24px; display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;">
        <div><strong style="font-size:0.85rem;">Name:</strong> {{ $property->contact_name ?? '-' }}</div>
        <div><strong style="font-size:0.85rem;">Email:</strong> {{ $property->contact_email ?? '-' }}</div>
        <div><strong style="font-size:0.85rem;">Phone:</strong> {{ $property->contact_phone ?? '-' }}</div>
        <div><strong style="font-size:0.85rem;">WhatsApp:</strong> {{ $property->contact_whatsapp ?? '-' }}</div>
    </div>
</div>

{{-- Amenities --}}
@if ($property->amenities->count())
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-star"></i> Amenities</h3>
        </div>
    </div>
    <div style="padding:24px; display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:10px;">
        @foreach ($property->amenities as $amenity)
        <div style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:#f8fafc; border-radius:8px; border:1px solid var(--border);">
            <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }};"></i>
            <span style="font-size:0.85rem;">{{ $amenity->name }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Gallery --}}
@if ($property->images->count())
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-images"></i> Gallery ({{ $property->images->count() }})</h3>
        </div>
    </div>
    <div style="padding:24px; display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:14px;">
        @foreach ($property->images as $img)
        <div style="border-radius:10px; overflow:hidden; border:1px solid var(--border); aspect-ratio:4/3;">
            <img src="{{ $img->url }}" style="width:100%; height:100%; object-fit:cover;">
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Documents --}}
@if ($property->documents->count())
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-file-pdf"></i> Documents ({{ $property->documents->count() }})</h3>
        </div>
    </div>
    <div style="padding:24px;">
        @foreach ($property->documents as $doc)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:9px; border:1px solid var(--border); margin-bottom:8px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <i class="fas fa-file-pdf" style="color:#dc2626; font-size:1.2rem;"></i>
                <div>
                    <strong style="font-size:0.85rem;">{{ $doc->title }}</strong>
                    <div style="font-size:0.72rem; color:var(--text-muted);">{{ strtoupper($doc->document_type) }}</div>
                </div>
            </div>
            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="action-btn view">
                <i class="fas fa-download"></i>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection