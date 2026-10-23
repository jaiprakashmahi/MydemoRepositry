@extends('layouts.dashboard')

@section('title', 'Edit Property - RKVIT REALESTATE')
@section('page-title', 'Edit Property')
@section('page-subtitle', 'Update property information')

@section('content')

<div style="max-width:1100px; margin:0 auto;">

    <div style="margin-bottom:20px; font-size:0.85rem;">
        <a href="{{ route('admin.properties.index') }}" style="color:var(--primary); font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Properties
        </a>
    </div>

    @if ($errors->any())
    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
        <ul style="margin-top:8px; padding-left:20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Tabs --}}
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="basic"><i class="fas fa-info-circle"></i> Basic</button>
            <button type="button" class="tab-btn" data-tab="pricing"><i class="fas fa-rupee-sign"></i> Pricing</button>
            <button type="button" class="tab-btn" data-tab="location"><i class="fas fa-map-marker-alt"></i> Location</button>
            <button type="button" class="tab-btn" data-tab="amenities"><i class="fas fa-star"></i> Amenities</button>
            <button type="button" class="tab-btn" data-tab="media"><i class="fas fa-images"></i> Media</button>
            <button type="button" class="tab-btn" data-tab="contact"><i class="fas fa-address-card"></i> Contact</button>
        </div>

        {{-- Basic --}}
        <div class="tab-content active" data-tab="basic">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                        <p>Editing: <strong>{{ $property->reference_no }}</strong></p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">
                        <div class="form-field col-2">
                            <label>Property Title <span class="req">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $property->title) }}" class="form-input" required>
                        </div>
                        <div class="form-field">
                            <label>Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $property->slug) }}" class="form-input">
                        </div>
                        <div class="form-field">
                            <label>Reference No</label>
                            <input type="text" value="{{ $property->reference_no }}" class="form-input" readonly style="background:#f1f5f9;">
                        </div>
                        <div class="form-field">
                            <label>Category <span class="req">*</span></label>
                            <select name="category_id" class="form-input" required>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $property->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Property Type <span class="req">*</span></label>
                            <select name="property_type_id" class="form-input" required>
                                @foreach ($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Listing Type <span class="req">*</span></label>
                            <select name="listing_type" class="form-input" required>
                                <option value="sale" {{ old('listing_type', $property->listing_type) === 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ old('listing_type', $property->listing_type) === 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Status <span class="req">*</span></label>
                            <select name="status" class="form-input" required>
                                @foreach (['draft','pending','active','inactive','sold','rented','rejected'] as $s)
                                <option value="{{ $s }}" {{ old('status', $property->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field col-2">
                            <label>Description</label>
                            <textarea name="description" rows="4" class="form-input">{{ old('description', $property->description) }}</textarea>
                        </div>
                        <div class="form-field col-2">
                            <label>Highlights</label>
                            <textarea name="highlights" rows="3" class="form-input">{{ old('highlights', $property->highlights) }}</textarea>
                        </div>
                        <div class="form-field"><label>Bedrooms</label><input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" class="form-input"></div>
                        <div class="form-field"><label>Bathrooms</label><input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" class="form-input"></div>
                        <div class="form-field"><label>Balconies</label><input type="number" name="balconies" value="{{ old('balconies', $property->balconies) }}" min="0" class="form-input"></div>
                        <div class="form-field">
                            <label>Furnishing</label>
                            <select name="furnishing" class="form-input">
                                @foreach (['unfurnished','semi-furnished','furnished'] as $f)
                                <option value="{{ $f }}" {{ old('furnishing', $property->furnishing) === $f ? 'selected' : '' }}>{{ ucwords(str_replace('-', ' ', $f)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Facing</label>
                            <select name="facing" class="form-input">
                                <option value="">-- Select --</option>
                                @foreach (['north','south','east','west','north-east','north-west','south-east','south-west'] as $f)
                                <option value="{{ $f }}" {{ old('facing', $property->facing) === $f ? 'selected' : '' }}>{{ ucwords(str_replace('-', ' ', $f)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Ownership</label>
                            <select name="ownership" class="form-input">
                                <option value="">-- Select --</option>
                                @foreach (['freehold','leasehold','co-operative','power-of-attorney'] as $o)
                                <option value="{{ $o }}" {{ old('ownership', $property->ownership) === $o ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $o)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="tab-content" data-tab="pricing">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-rupee-sign"></i> Pricing & Area</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">
                        <div class="form-field"><label>Price (₹) <span class="req">*</span></label><input type="number" step="0.01" name="price" value="{{ old('price', $property->price) }}" class="form-input" required></div>
                        <div class="form-field"><label>Price Per Sqft</label><input type="number" step="0.01" name="price_per_sqft" value="{{ old('price_per_sqft', $property->price_per_sqft) }}" class="form-input"></div>
                        <div class="form-field"><label>Maintenance Charges</label><input type="number" step="0.01" name="maintenance_charges" value="{{ old('maintenance_charges', $property->maintenance_charges) }}" class="form-input"></div>
                        <div class="form-field"><label>Security Deposit</label><input type="number" step="0.01" name="security_deposit" value="{{ old('security_deposit', $property->security_deposit) }}" class="form-input"></div>
                        <div class="form-field"><label>Area</label><input type="number" step="0.01" name="area" value="{{ old('area', $property->area) }}" class="form-input"></div>
                        <div class="form-field">
                            <label>Area Unit</label>
                            <select name="area_unit" class="form-input">
                                @foreach (['sqft','sqm','sqyd','acre','hectare'] as $u)
                                <option value="{{ $u }}" {{ old('area_unit', $property->area_unit) === $u ? 'selected' : '' }}>{{ strtoupper($u) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field"><label>Carpet Area</label><input type="number" step="0.01" name="carpet_area" value="{{ old('carpet_area', $property->carpet_area) }}" class="form-input"></div>
                        <div class="form-field"><label>Built-Up Area</label><input type="number" step="0.01" name="built_up_area" value="{{ old('built_up_area', $property->built_up_area) }}" class="form-input"></div>
                        <div class="form-field"><label>Plot Area</label><input type="number" step="0.01" name="plot_area" value="{{ old('plot_area', $property->plot_area) }}" class="form-input"></div>
                        <div class="form-field"><label>Age (years)</label><input type="number" name="age_of_property" value="{{ old('age_of_property', $property->age_of_property) }}" class="form-input"></div>
                        <div class="form-field"><label>Total Floors</label><input type="number" name="total_floors" value="{{ old('total_floors', $property->total_floors) }}" class="form-input"></div>
                        <div class="form-field"><label>Floor Number</label><input type="number" name="floor_number" value="{{ old('floor_number', $property->floor_number) }}" class="form-input"></div>
                        <div class="form-field"><label>Parking Spaces</label><input type="number" name="parking_spaces" value="{{ old('parking_spaces', $property->parking_spaces) }}" class="form-input"></div>
                        <div class="form-field">
                            <label style="display:flex; align-items:center; gap:10px; margin-top:22px; cursor:pointer;">
                                <input type="hidden" name="is_negotiable" value="0">
                                <input type="checkbox" name="is_negotiable" value="1" {{ old('is_negotiable', $property->is_negotiable) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                                <span>Price Negotiable</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="tab-content" data-tab="location">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">
                        <div class="form-field">
                            <label>State <span class="req">*</span></label>
                            <select name="state_id" id="state_id" class="form-input" required>
                                @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id', $property->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>City <span class="req">*</span></label>
                            <select name="city_id" id="city_id" class="form-input" required>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" data-state="{{ $city->state_id }}" {{ old('city_id', $property->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field"><label>Locality</label><input type="text" name="locality" value="{{ old('locality', $property->locality) }}" class="form-input"></div>
                        <div class="form-field"><label>Landmark</label><input type="text" name="landmark" value="{{ old('landmark', $property->landmark) }}" class="form-input"></div>
                        <div class="form-field col-2"><label>Address</label><textarea name="address" rows="2" class="form-input">{{ old('address', $property->address) }}</textarea></div>
                        <div class="form-field"><label>Pincode</label><input type="text" name="pincode" value="{{ old('pincode', $property->pincode) }}" class="form-input"></div>
                        <div class="form-field"><label>Latitude</label><input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $property->latitude) }}" class="form-input"></div>
                        <div class="form-field"><label>Longitude</label><input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $property->longitude) }}" class="form-input"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        <div class="tab-content" data-tab="amenities">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                    </div>
                </div>
                <div class="form-body">
                    @php $selected = old('amenities', $property->amenities->pluck('id')->toArray()); @endphp
                    @foreach ($amenities as $group => $items)
                    <div style="margin-bottom:20px;">
                        <h4 style="font-size:0.85rem; font-weight:700; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.5px;">{{ ucfirst($group) }}</h4>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:10px;">
                            @foreach ($items as $amenity)
                            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer; background:white;">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, $selected) ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--primary);">
                                <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }};"></i>
                                <span style="font-size:0.85rem;">{{ $amenity->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div class="tab-content" data-tab="media">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-image"></i> Featured Image</h3>
                    </div>
                </div>
                <div class="form-body">
                    @if ($property->featured_image)
                    <div style="margin-bottom:14px;">
                        <img src="{{ asset('storage/' . $property->featured_image) }}" style="width:200px; height:130px; object-fit:cover; border-radius:10px;">
                    </div>
                    @endif
                    <div class="form-field">
                        <label>Replace Featured Image</label>
                        <input type="file" name="featured_image" accept="image/*" class="form-input">
                    </div>
                    <div class="form-field"><label>Video URL</label><input type="url" name="video_url" value="{{ old('video_url', $property->video_url) }}" class="form-input"></div>
                    <div class="form-field"><label>Virtual Tour URL</label><input type="url" name="virtual_tour_url" value="{{ old('virtual_tour_url', $property->virtual_tour_url) }}" class="form-input"></div>
                </div>
            </div>

            {{-- Existing gallery --}}
            @if ($property->images->count())
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-images"></i> Existing Gallery ({{ $property->images->count() }})</h3>
                    </div>
                </div>
                <div class="form-body" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:14px;">
                    @foreach ($property->images as $img)
                    <div style="position:relative; border-radius:10px; overflow:hidden; border:1px solid var(--border);">
                        <img src="{{ $img->url }}" style="width:100%; height:110px; object-fit:cover; display:block;">
                        @if ($img->is_primary)
                        <span style="position:absolute; top:6px; left:6px; background:var(--primary); color:white; font-size:0.6rem; font-weight:700; padding:2px 6px; border-radius:4px;">PRIMARY</span>
                        @endif
                        <button type="button" onclick="if(confirm('Delete this image?')) document.getElementById('del-img-{{ $img->id }}').submit();"
                            style="position:absolute; top:6px; right:6px; width:26px; height:26px; border-radius:6px; background:rgba(0,0,0,0.7); color:white; border:none; cursor:pointer;">
                            <i class="fas fa-trash" style="font-size:0.7rem;"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-camera"></i> Add New Gallery Images</h3>
                    </div>
                </div>
                <div class="form-body">
                    <input type="file" name="gallery_images[]" accept="image/*" multiple class="form-input">
                </div>
            </div>

            @if ($property->documents->count())
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-file-pdf"></i> Documents ({{ $property->documents->count() }})</h3>
                    </div>
                </div>
                <div class="form-body">
                    @foreach ($property->documents as $doc)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:10px; border:1px solid var(--border); border-radius:8px; margin-bottom:8px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-file-pdf" style="color:#dc2626;"></i>
                            <span style="font-size:0.85rem;">{{ $doc->title }}</span>
                        </div>
                        <button type="button" onclick="if(confirm('Delete this document?')) document.getElementById('del-doc-{{ $doc->id }}').submit();"
                            class="action-btn delete"><i class="fas fa-trash"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-upload"></i> Add Documents</h3>
                    </div>
                </div>
                <div class="form-body">
                    <input type="file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple class="form-input">
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div class="tab-content" data-tab="contact">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-address-card"></i> Contact & Settings</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">
                        <div class="form-field"><label>Contact Name</label><input type="text" name="contact_name" value="{{ old('contact_name', $property->contact_name) }}" class="form-input"></div>
                        <div class="form-field"><label>Contact Email</label><input type="email" name="contact_email" value="{{ old('contact_email', $property->contact_email) }}" class="form-input"></div>
                        <div class="form-field"><label>Contact Phone</label><input type="text" name="contact_phone" value="{{ old('contact_phone', $property->contact_phone) }}" class="form-input"></div>
                        <div class="form-field"><label>WhatsApp</label><input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $property->contact_whatsapp) }}" class="form-input"></div>
                        <div class="form-field col-2"><label>Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title', $property->meta_title) }}" class="form-input"></div>
                        <div class="form-field col-2"><label>Meta Description</label><textarea name="meta_description" rows="2" class="form-input">{{ old('meta_description', $property->meta_description) }}</textarea></div>
                        <div class="form-field col-2"><label>Meta Keywords</label><input type="text" name="meta_keywords" value="{{ old('meta_keywords', $property->meta_keywords) }}" class="form-input"></div>
                    </div>

                    <div style="border-top:1px solid var(--border); padding-top:20px; margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div><strong style="font-size:0.85rem;"><i class="fas fa-star" style="color:#f59e0b;"></i> Featured</strong></div>
                        </label>
                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_verified" value="0">
                            <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $property->is_verified) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div><strong style="font-size:0.85rem;"><i class="fas fa-shield-alt" style="color:#10b981;"></i> Verified</strong></div>
                        </label>
                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="show_contact_publicly" value="0">
                            <input type="checkbox" name="show_contact_publicly" value="1" {{ old('show_contact_publicly', $property->show_contact_publicly) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div><strong style="font-size:0.85rem;">Show Contact Publicly</strong></div>
                        </label>
                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $property->is_active) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div><strong style="font-size:0.85rem;"><i class="fas fa-power-off"></i> Active</strong></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px;">
            <a href="{{ route('admin.properties.index') }}" class="btn-outline" style="padding:12px 28px;">Cancel</a>
            <button type="submit" class="btn-solid" style="padding:12px 32px;"><i class="fas fa-save"></i> Update Property</button>
        </div>
    </form>

    {{-- Hidden delete forms for images/documents --}}
    @foreach ($property->images as $img)
    <form id="del-img-{{ $img->id }}" action="{{ route('admin.properties.delete-image', $img) }}" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
    @endforeach
    @foreach ($property->documents as $doc)
    <form id="del-doc-{{ $doc->id }}" action="{{ route('admin.properties.delete-document', $doc) }}" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
    @endforeach
</div>

<style>
    .tabs-nav {
        display: flex;
        gap: 4px;
        background: white;
        padding: 8px;
        border-radius: 12px;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        overflow-x: auto;
    }

    .tab-btn {
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--text-soft);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        background: #f1f5f9;
    }

    .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .form-body {
        padding: 24px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-field {
        display: flex;
        flex-direction: column;
    }

    .form-field.col-2 {
        grid-column: span 2;
    }

    .form-field label {
        font-weight: 600;
        font-size: 0.82rem;
        margin-bottom: 7px;
    }

    .req {
        color: var(--primary);
    }

    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 9px;
        font-size: 0.9rem;
        outline: none;
        background: #fafbfc;
        font-family: inherit;
    }

    .form-input:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
    }

    @media (max-width:768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }

        .form-field.col-2 {
            grid-column: span 1;
        }
    }
</style>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.querySelector(`.tab-content[data-tab="${target}"]`).classList.add('active');
        });
    });

    const stateSelect = document.getElementById('state_id');
    const citySelect = document.getElementById('city_id');
    if (stateSelect && citySelect) {
        const allOptions = Array.from(citySelect.options);
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            citySelect.innerHTML = '<option value="">-- Select City --</option>';
            allOptions.forEach(opt => {
                if (!opt.value) return;
                if (opt.dataset.state === stateId) citySelect.appendChild(opt.cloneNode(true));
            });
        });
    }
</script>

@endsection