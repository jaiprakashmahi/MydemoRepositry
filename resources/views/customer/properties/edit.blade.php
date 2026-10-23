@extends('layouts.customer')

@section('title', 'Edit Property - Customer')
@section('page-title', 'Edit Property')
@section('page-subtitle', 'Editing: ' . $property->reference_no)

@section('content')

<div style="max-width:1100px; margin:0 auto;">

    <div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('customer.properties.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Properties
        </a>
        <a href="{{ route('customer.properties.show', $property) }}" class="btn-outline">
            <i class="fas fa-eye"></i> View Property
        </a>
    </div>

    @if ($errors->any())
    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix these errors:</strong>
        <ul style="margin-top:8px; padding-left:20px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('customer.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Basic --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                </div>
            </div>
            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Title</label>
                    <input type="text" name="title" value="{{ old('title', $property->title) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Category</label>
                    <select name="category_id" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $property->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Property Type</label>
                    <select name="property_type_id" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        @foreach ($propertyTypes as $type)
                        <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Listing Type</label>
                    <select name="listing_type" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        <option value="sale" {{ $property->listing_type === 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ $property->listing_type === 'rent' ? 'selected' : '' }}>For Rent</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Status</label>
                    <select name="status" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        @foreach (['draft','pending','active','inactive','sold','rented','rejected'] as $s)
                        <option value="{{ $s }}" {{ old('status', $property->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $property->price) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Area</label>
                    <input type="number" step="0.01" name="area" value="{{ old('area', $property->area) }}"
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Description</label>
                    <textarea name="description" rows="4" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">{{ old('description', $property->description) }}</textarea>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">State</label>
                    <select name="state_id" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ old('state_id', $property->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">City</label>
                    <select name="city_id" required style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                        @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id', $property->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Locality</label>
                    <input type="text" name="locality" value="{{ old('locality', $property->locality) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $property->pincode) }}"
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Address</label>
                    <textarea name="address" rows="2" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">{{ old('address', $property->address) }}</textarea>
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Contact Name</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $property->contact_name) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $property->contact_email) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $property->contact_phone) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*"
                        style="width:100%; padding:8px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem;">
                    @if ($property->featured_image)
                    <img src="{{ asset('storage/'.$property->featured_image) }}" style="width:100px; height:70px; object-fit:cover; border-radius:6px; margin-top:8px;">
                    @endif
                </div>

                <div style="grid-column:span 2; border-top:1px solid var(--border); padding-top:16px; display:grid; grid-template-columns:repeat(2, 1fr); gap:16px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ $property->is_featured ? 'checked' : '' }}
                            style="width:18px; height:18px; accent-color:var(--primary);">
                        <span style="font-weight:600; font-size:0.85rem;"><i class="fas fa-star" style="color:#f59e0b;"></i> Featured</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="hidden" name="is_verified" value="0">
                        <input type="checkbox" name="is_verified" value="1" {{ $property->is_verified ? 'checked' : '' }}
                            style="width:18px; height:18px; accent-color:var(--primary);">
                        <span style="font-weight:600; font-size:0.85rem;"><i class="fas fa-shield-alt" style="color:#10b981;"></i> Verified</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $property->is_active ? 'checked' : '' }}
                            style="width:18px; height:18px; accent-color:var(--primary);">
                        <span style="font-weight:600; font-size:0.85rem;"><i class="fas fa-power-off" style="color:var(--primary);"></i> Active</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="hidden" name="is_negotiable" value="0">
                        <input type="checkbox" name="is_negotiable" value="1" {{ $property->is_negotiable ? 'checked' : '' }}
                            style="width:18px; height:18px; accent-color:var(--primary);">
                        <span style="font-weight:600; font-size:0.85rem;">Negotiable</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-star"></i> Amenities</h3>
                </div>
            </div>
            <div style="padding:24px;">
                @php $selected = old('amenities', $property->amenities->pluck('id')->toArray()); @endphp
                @foreach ($amenities as $group => $items)
                <div style="margin-bottom:16px;">
                    <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:10px; text-transform:uppercase; letter-spacing:0.5px;">{{ ucfirst($group) }}</h4>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:10px;">
                        @foreach ($items as $amenity)
                        <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                {{ in_array($amenity->id, $selected) ? 'checked' : '' }}
                                style="width:16px; height:16px; accent-color:var(--primary);">
                            <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }};"></i>
                            <span style="font-size:0.85rem;">{{ $amenity->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end; margin-bottom:24px;">
            <a href="{{ route('customer.properties.index') }}" class="btn-outline" style="padding:12px 28px;">Cancel</a>
            <button type="submit" class="btn-solid" style="padding:12px 32px;">
                <i class="fas fa-save"></i> Update Property
            </button>
        </div>
    </form>
</div>

@endsection