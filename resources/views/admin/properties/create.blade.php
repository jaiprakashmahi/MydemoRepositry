@extends('layouts.admin')

@section('title', 'Add Property - RKVIT REALESTATE')
@section('page-title', 'Add New Property')
@section('page-subtitle', 'Create a new property listing')

@section('content')

<div style="max-width:1100px; margin:0 auto;">

    {{-- Back --}}
    <div style="margin-bottom:20px; font-size:0.85rem;">
        <a href="{{ route('admin.properties.index') }}" style="color:var(--primary); font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Properties
        </a>
    </div>

    {{-- Server-side errors --}}
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

    {{-- Client-side error summary --}}
    <div id="clientErrors" style="display:none; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix these errors before saving:</strong>
        <ul id="errorList" style="margin-top:8px; padding-left:20px;"></ul>
    </div>

    {{-- Success --}}
    @if (session('success'))
    <div style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ==================== FORM ==================== --}}
    <form id="propertyForm" action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Tab Nav --}}
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="basic"><i class="fas fa-info-circle"></i> Basic Info</button>
            <button type="button" class="tab-btn" data-tab="pricing"><i class="fas fa-rupee-sign"></i> Pricing & Area</button>
            <button type="button" class="tab-btn" data-tab="location"><i class="fas fa-map-marker-alt"></i> Location</button>
            <button type="button" class="tab-btn" data-tab="amenities"><i class="fas fa-star"></i> Amenities</button>
            <button type="button" class="tab-btn" data-tab="media"><i class="fas fa-images"></i> Media</button>
            <button type="button" class="tab-btn" data-tab="contact"><i class="fas fa-address-card"></i> Contact</button>
        </div>

        {{-- ==================== TAB 1: BASIC ==================== --}}
        <div class="tab-content active" data-tab="basic">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field col-2">
                            <label>Property Title <span class="req">*</span></label>
                            <input type="text" name="title" data-required="true" value="{{ old('title') }}"
                                class="form-input" placeholder="e.g. 3 BHK Luxury Apartment in Kankarbagh">
                            <span class="field-error" data-error-for="title"></span>
                        </div>

                        <div class="form-field col-2">
                            <label>Slug <small style="color:var(--text-muted); font-weight:400;">(optional — auto-generated)</small></label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-input" placeholder="auto-generated">
                        </div>

                        <div class="form-field">
                            <label>Category <span class="req">*</span></label>
                            <select name="category_id" data-required="true" class="form-input">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-error-for="category_id"></span>
                        </div>

                        <div class="form-field">
                            <label>Property Type <span class="req">*</span></label>
                            <select name="property_type_id" data-required="true" class="form-input">
                                <option value="">-- Select Type --</option>
                                @foreach ($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-error-for="property_type_id"></span>
                        </div>

                        <div class="form-field">
                            <label>Listing Type <span class="req">*</span></label>
                            <select name="listing_type" data-required="true" class="form-input">
                                <option value="sale" {{ old('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ old('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label>Status <span class="req">*</span></label>
                            <select name="status" data-required="true" class="form-input">
                                @foreach (['draft','pending','active','inactive','sold','rented'] as $s)
                                <option value="{{ $s }}" {{ old('status', 'pending') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field col-2">
                            <label>Description</label>
                            <textarea name="description" rows="4" class="form-input" placeholder="Detailed description...">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-field col-2">
                            <label>Highlights (one per line)</label>
                            <textarea name="highlights" rows="3" class="form-input" placeholder="Near metro&#10;24x7 security">{{ old('highlights') }}</textarea>
                        </div>

                        <div class="form-field">
                            <label>Bedrooms</label>
                            <input type="number" name="bedrooms" value="{{ old('bedrooms', 0) }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Bathrooms</label>
                            <input type="number" name="bathrooms" value="{{ old('bathrooms', 0) }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Balconies</label>
                            <input type="number" name="balconies" value="{{ old('balconies', 0) }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Furnishing</label>
                            <select name="furnishing" class="form-input">
                                <option value="unfurnished" {{ old('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                <option value="semi-furnished" {{ old('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi Furnished</option>
                                <option value="furnished" {{ old('furnishing') === 'furnished' ? 'selected' : '' }}>Furnished</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label>Facing</label>
                            <select name="facing" class="form-input">
                                <option value="">-- Select --</option>
                                @foreach (['north','south','east','west','north-east','north-west','south-east','south-west'] as $f)
                                <option value="{{ $f }}" {{ old('facing') === $f ? 'selected' : '' }}>{{ ucwords(str_replace('-', ' ', $f)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label>Ownership</label>
                            <select name="ownership" class="form-input">
                                <option value="">-- Select --</option>
                                @foreach (['freehold','leasehold','co-operative','power-of-attorney'] as $o)
                                <option value="{{ $o }}" {{ old('ownership') === $o ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $o)) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 2: PRICING ==================== --}}
        <div class="tab-content" data-tab="pricing">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-rupee-sign"></i> Pricing & Area Details</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field">
                            <label>Price (₹) <span class="req">*</span></label>
                            <input type="number" step="0.01" name="price" data-required="true" value="{{ old('price') }}" class="form-input" placeholder="e.g. 5000000">
                            <span class="field-error" data-error-for="price"></span>
                        </div>

                        <div class="form-field">
                            <label>Price Per Sqft (₹)</label>
                            <input type="number" step="0.01" name="price_per_sqft" value="{{ old('price_per_sqft') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Maintenance Charges (₹)</label>
                            <input type="number" step="0.01" name="maintenance_charges" value="{{ old('maintenance_charges') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Security Deposit (₹)</label>
                            <input type="number" step="0.01" name="security_deposit" value="{{ old('security_deposit') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Area</label>
                            <input type="number" step="0.01" name="area" value="{{ old('area') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Area Unit</label>
                            <select name="area_unit" class="form-input">
                                @foreach (['sqft','sqm','sqyd','acre','hectare'] as $u)
                                <option value="{{ $u }}" {{ old('area_unit', 'sqft') === $u ? 'selected' : '' }}>{{ strtoupper($u) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label>Carpet Area</label>
                            <input type="number" step="0.01" name="carpet_area" value="{{ old('carpet_area') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Built-Up Area</label>
                            <input type="number" step="0.01" name="built_up_area" value="{{ old('built_up_area') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Plot Area</label>
                            <input type="number" step="0.01" name="plot_area" value="{{ old('plot_area') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Age of Property (years)</label>
                            <input type="number" name="age_of_property" value="{{ old('age_of_property') }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Total Floors</label>
                            <input type="number" name="total_floors" value="{{ old('total_floors') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Floor Number</label>
                            <input type="number" name="floor_number" value="{{ old('floor_number') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Parking Spaces</label>
                            <input type="number" name="parking_spaces" value="{{ old('parking_spaces', 0) }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label style="display:flex; align-items:center; gap:10px; margin-top:22px; cursor:pointer;">
                                <input type="hidden" name="is_negotiable" value="0">
                                <input type="checkbox" name="is_negotiable" value="1" {{ old('is_negotiable', true) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                                <span>Price Negotiable</span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 3: LOCATION ==================== --}}
        <div class="tab-content" data-tab="location">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-map-marker-alt"></i> Location Details</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field">
                            <label>State <span class="req">*</span></label>
                            <select name="state_id" id="state_id" data-required="true" class="form-input">
                                <option value="">-- Select State --</option>
                                @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-error-for="state_id"></span>
                        </div>

                        <div class="form-field">
                            <label>City <span class="req">*</span></label>
                            <select name="city_id" id="city_id" data-required="true" class="form-input">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" data-state="{{ $city->state_id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-error-for="city_id"></span>
                        </div>

                        <div class="form-field">
                            <label>Locality</label>
                            <input type="text" name="locality" value="{{ old('locality') }}" class="form-input" placeholder="e.g. Kankarbagh">
                        </div>

                        <div class="form-field">
                            <label>Landmark</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" class="form-input">
                        </div>

                        <div class="form-field col-2">
                            <label>Full Address</label>
                            <textarea name="address" rows="2" class="form-input" placeholder="Complete address">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-field">
                            <label>Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-input" maxlength="10">
                        </div>

                        <div class="form-field">
                            <label>Latitude</label>
                            <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Longitude</label>
                            <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude') }}" class="form-input">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 4: AMENITIES ==================== --}}
        <div class="tab-content" data-tab="amenities">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                        <p>Select all amenities available for this property</p>
                    </div>
                </div>
                <div class="form-body">
                    @forelse ($amenities as $group => $items)
                    <div style="margin-bottom:20px;">
                        <h4 style="font-size:0.85rem; font-weight:700; color:var(--text-dark); margin-bottom:12px; text-transform:uppercase; letter-spacing:0.5px;">
                            {{ ucfirst($group) }}
                        </h4>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:10px;">
                            @foreach ($items as $amenity)
                            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer; transition:all 0.2s; background:white;">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                    {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                                    style="width:16px; height:16px; accent-color:var(--primary);">
                                <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }}; font-size:0.9rem;"></i>
                                <span style="font-size:0.85rem; font-weight:500;">{{ $amenity->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--text-muted); font-size:0.85rem;">No amenities available. Please add some amenities first.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==================== TAB 5: MEDIA ==================== --}}
        <div class="tab-content" data-tab="media">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-image"></i> Featured Image</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Upload Featured Image</label>
                        <input type="file" name="featured_image" accept="image/*" class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">JPG, PNG, WEBP — Max 5MB</small>
                    </div>
                    <div class="form-field">
                        <label>Video URL</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}" class="form-input" placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <div class="form-field">
                        <label>Virtual Tour URL</label>
                        <input type="url" name="virtual_tour_url" value="{{ old('virtual_tour_url') }}" class="form-input">
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-camera"></i> Gallery Images</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Upload Gallery Images (multiple)</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">First image becomes primary. JPG, PNG, WEBP — Max 5MB each</small>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-file-pdf"></i> Documents</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Upload Documents (multiple)</label>
                        <input type="file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">PDF, DOC, DOCX, JPG, PNG — Max 10MB each</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 6: CONTACT ==================== --}}
        <div class="tab-content" data-tab="contact">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-address-card"></i> Contact & Settings</h3>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name', Auth::user()->name ?? '') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', Auth::user()->email ?? '') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="form-input" placeholder="+91-9835634422">
                        </div>

                        <div class="form-field">
                            <label>WhatsApp Number</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp') }}" class="form-input">
                        </div>

                        <div class="form-field col-2">
                            <label>Meta Title (SEO)</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-input">
                        </div>

                        <div class="form-field col-2">
                            <label>Meta Description (SEO)</label>
                            <textarea name="meta_description" rows="2" class="form-input">{{ old('meta_description') }}</textarea>
                        </div>

                        <div class="form-field col-2">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-input" placeholder="apartment, patna, 3bhk">
                        </div>

                    </div>

                    <div style="border-top:1px solid var(--border); padding-top:20px; margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div>
                                <strong style="font-size:0.85rem; display:block;"><i class="fas fa-star" style="color:#f59e0b;"></i> Featured</strong>
                                <span style="font-size:0.72rem; color:var(--text-muted);">Highlight on homepage</span>
                            </div>
                        </label>

                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_verified" value="0">
                            <input type="checkbox" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div>
                                <strong style="font-size:0.85rem; display:block;"><i class="fas fa-shield-alt" style="color:#10b981;"></i> Verified</strong>
                                <span style="font-size:0.72rem; color:var(--text-muted);">Mark as verified</span>
                            </div>
                        </label>

                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="show_contact_publicly" value="0">
                            <input type="checkbox" name="show_contact_publicly" value="1" {{ old('show_contact_publicly', true) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div>
                                <strong style="font-size:0.85rem; display:block;"><i class="fas fa-eye" style="color:#3b82f6;"></i> Show Contact Publicly</strong>
                                <span style="font-size:0.72rem; color:var(--text-muted);">Visible to visitors</span>
                            </div>
                        </label>

                        <label style="display:flex; align-items:center; gap:10px; padding:14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                            <div>
                                <strong style="font-size:0.85rem; display:block;"><i class="fas fa-power-off" style="color:var(--primary);"></i> Active</strong>
                                <span style="font-size:0.72rem; color:var(--text-muted);">Live on site</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== FORM FOOTER ==================== --}}
        <div class="form-footer">
            <div class="tab-progress">
                <span id="progressText">Tab 1 of 6</span>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="{{ route('admin.properties.index') }}" class="btn-outline" style="padding:12px 28px;">Cancel</a>
                <button type="submit" id="submitBtn" class="btn-solid" style="padding:12px 32px;">
                    <i class="fas fa-save"></i> Save Property
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ==================== STYLES ==================== --}}
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
        position: sticky;
        top: 70px;
        z-index: 100;
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
        position: relative;
    }

    .tab-btn:hover {
        background: #f1f5f9;
    }

    .tab-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(216, 35, 42, 0.25);
    }

    .tab-btn.has-error::after {
        content: '';
        position: absolute;
        top: 6px;
        right: 6px;
        width: 8px;
        height: 8px;
        background: #dc2626;
        border-radius: 50%;
        border: 2px solid white;
    }

    .tab-btn.has-error:not(.active) {
        color: #dc2626;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        color: var(--text-dark);
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
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
    }

    .form-input.is-invalid {
        border-color: #dc2626;
        background: #fef2f2;
    }

    .field-error {
        display: none;
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 5px;
        align-items: center;
        gap: 4px;
    }

    .field-error.show {
        display: flex;
    }

    .field-error::before {
        content: '\f06a';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 16px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        position: sticky;
        bottom: 12px;
        z-index: 50;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
    }

    .tab-progress {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    #submitBtn.loading {
        opacity: 0.7;
        pointer-events: none;
    }

    #submitBtn.loading::before {
        content: '';
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }

        .form-field.col-2 {
            grid-column: span 1;
        }

        .form-footer {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>

{{-- ==================== SCRIPT ==================== --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('propertyForm');
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const errorSummary = document.getElementById('clientErrors');
        const errorList = document.getElementById('errorList');
        const submitBtn = document.getElementById('submitBtn');
        const progressText = document.getElementById('progressText');

        // ===== Map each field to its tab =====
        const fieldTabMap = {
            'title': 'basic',
            'category_id': 'basic',
            'property_type_id': 'basic',
            'listing_type': 'basic',
            'status': 'basic',
            'price': 'pricing',
            'state_id': 'location',
            'city_id': 'location',
        };

        // ===== Tab switching =====
        tabBtns.forEach((btn, index) => {
            btn.addEventListener('click', function() {
                switchTab(this.dataset.tab);
            });
        });

        function switchTab(tabName) {
            tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === tabName));
            tabContents.forEach(c => c.classList.toggle('active', c.dataset.tab === tabName));
            updateProgress();
        }

        function updateProgress() {
            const activeIndex = Array.from(tabBtns).findIndex(b => b.classList.contains('active'));
            progressText.textContent = `Tab ${activeIndex + 1} of ${tabBtns.length}`;
        }

        // ===== Get tab for a given field =====
        function getTabForField(field) {
            // First check the map by name
            if (fieldTabMap[field.name]) return fieldTabMap[field.name];

            // Otherwise find which tab content contains it
            const tabContent = field.closest('.tab-content');
            return tabContent ? tabContent.dataset.tab : 'basic';
        }

        // ===== Validate a single field =====
        function validateField(field) {
            if (!field.hasAttribute('data-required')) return true;

            const value = field.value.trim();
            const errorEl = document.querySelector(`[data-error-for="${field.name}"]`);

            if (!value) {
                field.classList.add('is-invalid');
                if (errorEl) {
                    errorEl.textContent = 'This field is required';
                    errorEl.classList.add('show');
                }
                return false;
            } else {
                field.classList.remove('is-invalid');
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.remove('show');
                }
                return true;
            }
        }

        // ===== Validate all required fields =====
        function validateAll() {
            const requiredFields = form.querySelectorAll('[data-required="true"]');
            const errors = [];
            const tabsWithErrors = new Set();

            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    const label = field.closest('.form-field')?.querySelector('label')?.textContent.trim() || field.name;
                    const cleanLabel = label.replace(/\*/g, '').trim();
                    errors.push({
                        field: field,
                        tab: getTabForField(field),
                        message: cleanLabel
                    });
                    tabsWithErrors.add(getTabForField(field));
                }
            });

            // Highlight tabs with errors
            tabBtns.forEach(btn => {
                btn.classList.toggle('has-error', tabsWithErrors.has(btn.dataset.tab));
            });

            return errors;
        }

        // ===== Show error summary =====
        function showErrors(errors) {
            errorList.innerHTML = '';
            if (errors.length === 0) {
                errorSummary.style.display = 'none';
                return;
            }

            errors.forEach(err => {
                const li = document.createElement('li');
                li.textContent = err.message + ' (in "' + capitalize(err.tab) + '" tab)';
                li.style.cursor = 'pointer';
                li.style.marginBottom = '4px';
                li.addEventListener('click', () => {
                    switchTab(err.tab);
                    setTimeout(() => {
                        err.field.focus();
                        err.field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 100);
                });
                errorList.appendChild(li);
            });

            errorSummary.style.display = 'block';
            errorSummary.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        // ===== Real-time validation on input blur =====
        form.querySelectorAll('[data-required="true"]').forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                }
            });
            field.addEventListener('change', () => {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                }
            });
        });

        // ===== Form submit =====
        form.addEventListener('submit', function(e) {
            const errors = validateAll();

            if (errors.length > 0) {
                e.preventDefault();
                showErrors(errors);

                // Auto-switch to the first tab with errors
                const firstTab = errors[0].tab;
                switchTab(firstTab);

                setTimeout(() => {
                    errors[0].field.focus();
                    errors[0].field.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 150);

                return false;
            }

            // All valid — hide error summary
            errorSummary.style.display = 'none';

            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Saving...';

            // Let the form submit
            return true;
        });

        // ===== City filter by state =====
        const stateSelect = document.getElementById('state_id');
        const citySelect = document.getElementById('city_id');

        if (stateSelect && citySelect) {
            const allOptions = Array.from(citySelect.options);

            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">-- Select City --</option>';

                if (!stateId) return;

                allOptions.forEach(opt => {
                    if (!opt.value) return;
                    if (opt.dataset.state === stateId) {
                        citySelect.appendChild(opt.cloneNode(true));
                    }
                });
            });

            // Trigger on load if state already selected
            if (stateSelect.value) {
                stateSelect.dispatchEvent(new Event('change'));
            }
        }

        // ===== Initialize progress =====
        updateProgress();

        // ===== Keyboard shortcut: Ctrl+S to save =====
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                form.requestSubmit();
            }
        });
    });
</script>

@endsection