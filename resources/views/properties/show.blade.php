@extends('layouts.dashboard')

@section('title', $property->title . ' - RKVIT REALESTATE')
@section('page-title', 'Property Details')
@section('page-subtitle', 'View full property information')

@section('content')

<div style="max-width:1100px; margin:0 auto;">

    <div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('properties.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Properties
        </a>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('properties.edit', $property) }}" class="btn-solid"><i class="fas fa-edit"></i> Edit</a>
            <form action="{{ route('properties.toggle-status', $property) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn-outline">
                    <i class="fas fa-power-off"></i> {{ $property->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Hero --}}
    <div class="panel" style="overflow:hidden; margin-bottom:24px;">
        <div style="position:relative; height:340px; background:#e2e8f0; overflow:hidden;">
            @php
            $heroImg = $property->featured_image
            ? asset('storage/' . $property->featured_image)
            : ($property->primaryImage ? $property->primaryImage->url
            : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&h=600&fit=crop');
            @endphp
            <img src="{{ $heroImg }}" style="width:100%; height:100%; object-fit:cover;">
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.8), transparent 60%);"></div>
            <div style="position:absolute; bottom:24px; left:32px; right:32px; color:white;">
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
                    <span class="badge {{ $property->status_badge['class'] }}">{{ $property->status_badge['label'] }}</span>
                    <span class="badge badge-info">{{ ucfirst($property->listing_type) }}</span>
                    @if ($property->is_featured)<span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>@endif
                    @if ($property->is_verified)<span class="badge badge-success"><i class="fas fa-shield-alt"></i> Verified</span>@endif
                </div>
                <h1 style="font-size:2rem; font-weight:800; margin-bottom:8px; letter-spacing:-0.02em;">{{ $property->title }}</h1>
                <p style="font-size:0.95rem; opacity:0.95;">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->city->name ?? '' }}, {{ $property->state->name ?? '' }}
                </p>
            </div>
        </div>

        <div style="padding:24px 28px; display:grid; grid-template-columns:1fr auto; gap:24px; align-items:center; border-bottom:1px solid var(--border);">
            <div>
                <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">Price</div>
                <div style="font-size:2.2rem; font-weight:800; color:var(--primary); letter-spacing:-0.02em;">{{ $property->formatted_price }}</div>
                @if ($property->is_negotiable)<span style="font-size:0.8rem; color:var(--text-muted);">Negotiable</span>@endif
            </div>
            <div style="display:flex; gap:20px;">
                <div style="text-align:center;">
                    <div style="font-size:1.4rem; font-weight:700; color:var(--text-dark);">{{ $property->bedrooms }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;"><i class="fas fa-bed"></i> Beds</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem; font-weight:700; color:var(--text-dark);">{{ $property->bathrooms }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;"><i class="fas fa-bath"></i> Baths</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.4rem; font-weight:700; color:var(--text-dark);">{{ $property->area }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;"><i class="fas fa-vector-square"></i> {{ $property->area_unit }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:24px;">

        {{-- Left column --}}
        <div>
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
                    @if ($property->highlights)
                    <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--border);">
                        <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px;">Highlights</h4>
                        <ul style="padding-left:20px;">
                            @foreach (explode("\n", $property->highlights) as $line)
                            @if (trim($line)) <li style="font-size:0.88rem; margin-bottom:6px; color:var(--text-soft);">{{ trim($line) }}</li> @endif
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
                <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @php
                    $details = [
                    ['Reference No', $property->reference_no],
                    ['Category', $property->category->name ?? '-'],
                    ['Property Type', $property->propertyType->name ?? '-'],
                    ['Listing Type', ucfirst($property->listing_type)],
                    ['Furnishing', ucwords(str_replace('-', ' ', $property->furnishing ?? '-'))],
                    ['Facing', $property->facing ? ucwords(str_replace('-', ' ', $property->facing)) : '-'],
                    ['Ownership', $property->ownership ? ucfirst(str_replace('-', ' ', $property->ownership)) : '-'],
                    ['Age', $property->age_of_property ? $property->age_of_property . ' years' : '-'],
                    ['Total Floors', $property->total_floors ?? '-'],
                    ['Floor Number', $property->floor_number ?? '-'],
                    ['Parking', $property->parking_spaces ?: '-'],
                    ['Area', $property->area . ' ' . $property->area_unit],
                    ['Carpet Area', $property->carpet_area ? $property->carpet_area . ' ' . $property->area_unit : '-'],
                    ['Built-Up Area', $property->built_up_area ? $property->built_up_area . ' ' . $property->area_unit : '-'],
                    ['Price / Sqft', $property->price_per_sqft ? '₹ ' . number_format($property->price_per_sqft, 2) : '-'],
                    ['Views', number_format($property->views_count)],
                    ];
                    @endphp
                    @foreach ($details as $d)
                    <div style="padding:12px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">{{ $d[0] }}</div>
                        <div style="font-size:0.88rem; font-weight:600; color:var(--text-dark);">{{ $d[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

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

            {{-- Amenities --}}
            @if ($property->amenities->count())
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                    </div>
                </div>
                <div style="padding:24px; display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:12px;">
                    @foreach ($property->amenities as $amenity)
                    <div style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                        <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }}; font-size:1rem;"></i>
                        <span style="font-size:0.85rem; font-weight:500;">{{ $amenity->name }}</span>
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
                        <h3><i class="fas fa-file-pdf"></i> Documents</h3>
                    </div>
                </div>
                <div style="padding:24px;">
                    @foreach ($property->documents as $doc)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border); margin-bottom:8px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <i class="fas fa-file-pdf" style="color:#dc2626; font-size:1.2rem;"></i>
                            <div>
                                <strong style="font-size:0.85rem;">{{ $doc->title }}</strong>
                                <div style="font-size:0.72rem; color:var(--text-muted);">
                                    {{ strtoupper($doc->document_type) }} • {{ number_format($doc->file_size / 1024, 1) }} MB
                                </div>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="action-btn view"><i class="fas fa-download"></i></a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right column --}}
        <div>
            {{-- Contact Card --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-address-card"></i> Contact</h3>
                    </div>
                </div>
                <div style="padding:24px;">
                    @if ($property->contact_name)
                    <div style="margin-bottom:14px;">
                        <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Contact Name</div>
                        <div style="font-size:0.9rem; font-weight:600;">{{ $property->contact_name }}</div>
                    </div>
                    @endif
                    @if ($property->contact_phone)
                    <div style="margin-bottom:14px;">
                        <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Phone</div>
                        <div style="font-size:0.9rem; font-weight:600;">
                            <i class="fas fa-phone" style="color:var(--primary);"></i> {{ $property->contact_phone }}
                        </div>
                    </div>
                    @endif
                    @if ($property->contact_email)
                    <div style="margin-bottom:14px;">
                        <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Email</div>
                        <div style="font-size:0.9rem; font-weight:600;">
                            <i class="fas fa-envelope" style="color:var(--primary);"></i> {{ $property->contact_email }}
                        </div>
                    </div>
                    @endif
                    @if ($property->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->contact_whatsapp) }}" target="_blank"
                        style="display:block; text-align:center; padding:11px; background:#25d366; color:white; border-radius:9px; font-weight:600; font-size:0.85rem; margin-top:14px; text-decoration:none;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    @endif
                </div>
            </div>

            {{-- Location Card --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                    </div>
                </div>
                <div style="padding:24px;">
                    <div style="font-size:0.85rem; color:var(--text-soft); line-height:1.6;">
                        @if ($property->address)<p>{{ $property->address }}</p>@endif
                        @if ($property->locality)<p><strong>Locality:</strong> {{ $property->locality }}</p>@endif
                        @if ($property->landmark)<p><strong>Landmark:</strong> {{ $property->landmark }}</p>@endif
                        @if ($property->pincode)<p><strong>Pincode:</strong> {{ $property->pincode }}</p>@endif
                    </div>
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-chart-bar"></i> Stats</h3>
                    </div>
                </div>
                <div style="padding:24px; display:grid; gap:12px;">
                    <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                        <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Views</div>
                        <div style="font-size:1.2rem; font-weight:800;">{{ number_format($property->views_count) }}</div>
                    </div>
                    <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                        <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Inquiries</div>
                        <div style="font-size:1.2rem; font-weight:800;">{{ number_format($property->inquiries_count) }}</div>
                    </div>
                    <div style="padding:14px; background:#f8fafc; border-radius:9px; border:1px solid var(--border);">
                        <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Created</div>
                        <div style="font-size:0.9rem; font-weight:700;">{{ $property->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- Delete --}}
            <form action="{{ route('properties.destroy', $property) }}" method="POST"
                onsubmit="return confirm('Delete this property permanently?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline" style="width:100%; justify-content:center; padding:12px; color:#dc2626; border-color:#fecaca;">
                    <i class="fas fa-trash"></i> Delete Property
                </button>
            </form>
        </div>
    </div>
</div>

@endsection