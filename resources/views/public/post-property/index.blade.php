@extends('layouts.public')

@section('title', 'Post Your Property - RKVIT REALESTATE')

@section('content')

<div class="page-container">

    {{-- Header --}}
    <div class="page-header">
        <div style="display:inline-block; background:var(--primary-light); color:var(--primary); padding:8px 18px; border-radius:40px; font-size:0.8rem; font-weight:700; margin-bottom:16px;">
            <i class="fas fa-bullhorn"></i> FREE PROPERTY LISTING
        </div>
        <h1>Post Your Property</h1>
        <p>Reach thousands of buyers and tenants across India. It's completely free!</p>
    </div>

    {{-- Welcome card --}}
    <div style="background:linear-gradient(135deg,#d8232a,#a01820); border-radius:16px; padding:24px; color:white; margin-bottom:24px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
        <div style="width:56px; height:56px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:700; flex-shrink:0;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div style="flex:1; min-width:200px;">
            <div style="font-size:1rem; font-weight:700; margin-bottom:2px;">Hi {{ Auth::user()->name }} 👋</div>
            <div style="font-size:0.85rem; opacity:0.9;">You're logged in as {{ Auth::user()->email }}</div>
        </div>
        <div style="font-size:0.8rem; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:20px;">
            <i class="fas fa-shield-alt"></i> Verified User
        </div>
    </div>

    {{-- Server errors --}}
    @if ($errors->any())
    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:16px 20px; border-radius:12px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
        <ul style="margin-top:8px; padding-left:20px; font-size:0.9rem;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Session error --}}
    @if (session('error'))
    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:16px 20px; border-radius:12px; margin-bottom:20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Client error summary --}}
    <div id="clientErrors" style="display:none; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:16px 20px; border-radius:12px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix these errors:</strong>
        <ul id="errorList" style="margin-top:8px; padding-left:20px; font-size:0.9rem;"></ul>
    </div>

    {{-- Info banner --}}
    <div style="background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; padding:14px 18px; border-radius:10px; margin-bottom:24px; font-size:0.88rem; display:flex; align-items:flex-start; gap:12px;">
        <i class="fas fa-info-circle" style="font-size:1.1rem; margin-top:2px; flex-shrink:0;"></i>
        <div>
            <strong>Note:</strong> Your property will be reviewed by our team before it goes live. This usually takes up to 24 hours. You'll be notified once approved.
        </div>
    </div>

    {{-- ==================== FORM ==================== --}}
    <form id="publicPropertyForm" action="{{ route('public.post-property.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Tabs --}}
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="basic"><i class="fas fa-info-circle"></i> Basic</button>
            <button type="button" class="tab-btn" data-tab="pricing"><i class="fas fa-rupee-sign"></i> Pricing</button>
            <button type="button" class="tab-btn" data-tab="location"><i class="fas fa-map-marker-alt"></i> Location</button>
            <button type="button" class="tab-btn" data-tab="amenities"><i class="fas fa-star"></i> Amenities</button>
            <button type="button" class="tab-btn" data-tab="media"><i class="fas fa-images"></i> Photos</button>
            <button type="button" class="tab-btn" data-tab="contact"><i class="fas fa-address-card"></i> Contact</button>
        </div>

        {{-- TAB: BASIC --}}
        <div class="tab-content active" data-tab="basic">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-info-circle"></i> Property Details</h3>
                        <p>Tell us about your property</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field col-2">
                            <label>Property Title <span class="req">*</span></label>
                            <input type="text" name="title" data-required="true" value="{{ old('title') }}" class="form-input" placeholder="e.g. 3 BHK Luxury Apartment in Kankarbagh">
                            <span class="field-error" data-error-for="title"></span>
                        </div>

                        <div class="form-field">
                            <label>I want to <span class="req">*</span></label>
                            <select name="listing_type" data-required="true" class="form-input">
                                <option value="sale" {{ old('listing_type') === 'sale' ? 'selected' : '' }}>Sell my property</option>
                                <option value="rent" {{ old('listing_type') === 'rent' ? 'selected' : '' }}>Rent out my property</option>
                            </select>
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

                        <div class="form-field col-2">
                            <label>Category <span class="req">*</span></label>
                            <select name="category_id" data-required="true" class="form-input">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-error-for="category_id"></span>
                        </div>

                        <div class="form-field col-2">
                            <label>Description <span class="req">*</span></label>
                            <textarea name="description" data-required="true" rows="4" class="form-input" placeholder="Describe your property: location, nearby facilities, condition, etc. (minimum 20 characters)">{{ old('description') }}</textarea>
                            <span class="field-error" data-error-for="description"></span>
                            <small style="color:var(--text-muted); font-size:0.75rem; margin-top:4px;">Minimum 20 characters</small>
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
                                <option value="unfurnished">Unfurnished</option>
                                <option value="semi-furnished">Semi Furnished</option>
                                <option value="furnished">Furnished</option>
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

        {{-- TAB: PRICING --}}
        <div class="tab-content" data-tab="pricing">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-rupee-sign"></i> Price & Area</h3>
                        <p>Set your expected price</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field">
                            <label>Expected Price (₹) <span class="req">*</span></label>
                            <input type="number" name="price" data-required="true" value="{{ old('price') }}" class="form-input" placeholder="e.g. 5000000">
                            <span class="field-error" data-error-for="price"></span>
                        </div>

                        <div class="form-field">
                            <label style="display:flex; align-items:center; gap:10px; margin-top:22px; cursor:pointer;">
                                <input type="hidden" name="is_negotiable" value="0">
                                <input type="checkbox" name="is_negotiable" value="1" {{ old('is_negotiable', true) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                                <span>Price is negotiable</span>
                            </label>
                        </div>

                        <div class="form-field">
                            <label>Built-Up Area</label>
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
                            <label>Age of Property (years)</label>
                            <input type="number" name="age_of_property" value="{{ old('age_of_property') }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Parking Spaces</label>
                            <input type="number" name="parking_spaces" value="{{ old('parking_spaces', 0) }}" min="0" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Total Floors</label>
                            <input type="number" name="total_floors" value="{{ old('total_floors') }}" class="form-input">
                        </div>

                        <div class="form-field">
                            <label>Your Floor</label>
                            <input type="number" name="floor_number" value="{{ old('floor_number') }}" class="form-input">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: LOCATION --}}
        <div class="tab-content" data-tab="location">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-map-marker-alt"></i> Property Location</h3>
                        <p>Where is your property located?</p>
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
                            <label>Locality <span class="req">*</span></label>
                            <input type="text" name="locality" data-required="true" value="{{ old('locality') }}" class="form-input" placeholder="e.g. Kankarbagh, Bailey Road">
                            <span class="field-error" data-error-for="locality"></span>
                        </div>

                        <div class="form-field">
                            <label>Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-input" maxlength="10">
                        </div>

                        <div class="form-field col-2">
                            <label>Full Address <span class="req">*</span></label>
                            <textarea name="address" data-required="true" rows="2" class="form-input" placeholder="Building name, street, area...">{{ old('address') }}</textarea>
                            <span class="field-error" data-error-for="address"></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: AMENITIES --}}
        <div class="tab-content" data-tab="amenities">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                        <p>Select all that apply</p>
                    </div>
                </div>
                <div class="form-body">
                    @forelse ($amenities as $group => $items)
                    <div style="margin-bottom:20px;">
                        <h4 style="font-size:0.85rem; font-weight:700; color:var(--text-dark); margin-bottom:12px; text-transform:uppercase; letter-spacing:0.5px;">{{ ucfirst($group) }}</h4>
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:10px;">
                            @foreach ($items as $amenity)
                            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1.5px solid var(--border); border-radius:9px; cursor:pointer; background:white; transition:all 0.2s;">
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
                    <p style="color:var(--text-muted); font-size:0.85rem;">No amenities available.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: MEDIA --}}
        <div class="tab-content" data-tab="media">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-image"></i> Featured Image</h3>
                        <p>Upload a hero photo for your listing</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Featured Image</label>
                        <input type="file" name="featured_image" accept="image/*" class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">JPG, PNG, WEBP — Max 5MB</small>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-camera"></i> Gallery Photos</h3>
                        <p>Upload up to 10 photos</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Gallery Images</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">Select multiple. First image becomes primary.</small>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-file-pdf"></i> Documents (Optional)</h3>
                        <p>Deeds, NOCs, brochures etc.</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-field">
                        <label>Upload Documents</label>
                        <input type="file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple class="form-input">
                        <small style="color:var(--text-muted); font-size:0.75rem;">PDF, DOC, DOCX, JPG, PNG — Max 10MB each</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: CONTACT --}}
        <div class="tab-content" data-tab="contact">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-address-card"></i> Contact Details</h3>
                        <p>How should buyers reach you?</p>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-grid-2">

                        <div class="form-field">
                            <label>Contact Name <span class="req">*</span></label>
                            <input type="text" name="contact_name" data-required="true" value="{{ old('contact_name', Auth::user()->name) }}" class="form-input">
                            <span class="field-error" data-error-for="contact_name"></span>
                        </div>

                        <div class="form-field">
                            <label>Contact Email <span class="req">*</span></label>
                            <input type="email" name="contact_email" data-required="true" value="{{ old('contact_email', Auth::user()->email) }}" class="form-input">
                            <span class="field-error" data-error-for="contact_email"></span>
                        </div>

                        <div class="form-field">
                            <label>Phone Number <span class="req">*</span></label>
                            <input type="text" name="contact_phone" data-required="true" value="{{ old('contact_phone') }}" class="form-input" placeholder="+91-9835634422">
                            <span class="field-error" data-error-for="contact_phone"></span>
                        </div>

                        <div class="form-field">
                            <label>WhatsApp (Optional)</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp') }}" class="form-input" placeholder="Same as phone if left blank">
                        </div>

                    </div>

                    <div style="background:#f8fafc; border:1px solid var(--border); padding:16px; border-radius:10px; margin-top:20px; font-size:0.85rem; color:var(--text-soft);">
                        <i class="fas fa-lock" style="color:var(--primary);"></i>
                        Your contact details will be shown only to interested buyers. We never share your information with third parties.
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="form-footer">
            <div class="tab-progress">
                <span id="progressText">Tab 1 of 6</span>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="{{ url('/') }}" class="btn-cancel">Cancel</a>
                <button type="submit" id="submitBtn" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Property
                </button>
            </div>
        </div>
    </form>

    {{-- Trust badges --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:32px;">
        <div style="text-align:center; padding:20px; background:white; border:1px solid var(--border); border-radius:12px;">
            <i class="fas fa-shield-alt" style="font-size:1.8rem; color:var(--primary); margin-bottom:8px;"></i>
            <div style="font-weight:700; font-size:0.88rem; margin-bottom:4px;">100% Free</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">No hidden charges</div>
        </div>
        <div style="text-align:center; padding:20px; background:white; border:1px solid var(--border); border-radius:12px;">
            <i class="fas fa-bolt" style="font-size:1.8rem; color:var(--primary); margin-bottom:8px;"></i>
            <div style="font-weight:700; font-size:0.88rem; margin-bottom:4px;">Fast Approval</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Within 24 hours</div>
        </div>
        <div style="text-align:center; padding:20px; background:white; border:1px solid var(--border); border-radius:12px;">
            <i class="fas fa-users" style="font-size:1.8rem; color:var(--primary); margin-bottom:8px;"></i>
            <div style="font-weight:700; font-size:0.88rem; margin-bottom:4px;">Wide Reach</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Thousands of buyers</div>
        </div>
    </div>
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

    .panel {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .panel-header h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-header h3 i {
        color: var(--primary);
    }

    .panel-header p {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 2px;
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
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
    }

    .tab-progress {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .btn-cancel {
        padding: 12px 24px;
        border-radius: 9px;
        background: white;
        border: 1.5px solid var(--border);
        color: var(--text-soft);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-cancel:hover {
        border-color: var(--text-soft);
    }

    .btn-submit {
        padding: 12px 28px;
        border-radius: 9px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(216, 35, 42, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(216, 35, 42, 0.4);
    }

    .btn-submit.loading {
        opacity: 0.7;
        pointer-events: none;
    }

    .btn-submit.loading::before {
        content: '';
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-right: 4px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width:768px) {
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
        const form = document.getElementById('publicPropertyForm');
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const errorSummary = document.getElementById('clientErrors');
        const errorList = document.getElementById('errorList');
        const submitBtn = document.getElementById('submitBtn');
        const progressText = document.getElementById('progressText');

        const fieldTabMap = {
            'title': 'basic',
            'category_id': 'basic',
            'property_type_id': 'basic',
            'listing_type': 'basic',
            'description': 'basic',
            'price': 'pricing',
            'state_id': 'location',
            'city_id': 'location',
            'locality': 'location',
            'address': 'location',
            'contact_name': 'contact',
            'contact_email': 'contact',
            'contact_phone': 'contact',
        };

        tabBtns.forEach(btn => btn.addEventListener('click', function() {
            switchTab(this.dataset.tab);
        }));

        function switchTab(tabName) {
            tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === tabName));
            tabContents.forEach(c => c.classList.toggle('active', c.dataset.tab === tabName));
            updateProgress();
        }

        function updateProgress() {
            const activeIndex = Array.from(tabBtns).findIndex(b => b.classList.contains('active'));
            progressText.textContent = `Tab ${activeIndex + 1} of ${tabBtns.length}`;
        }

        function getTabForField(field) {
            if (fieldTabMap[field.name]) return fieldTabMap[field.name];
            const tabContent = field.closest('.tab-content');
            return tabContent ? tabContent.dataset.tab : 'basic';
        }

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
            }
            // Description min length
            if (field.name === 'description' && value.length < 20) {
                field.classList.add('is-invalid');
                if (errorEl) {
                    errorEl.textContent = 'Please write at least 20 characters';
                    errorEl.classList.add('show');
                }
                return false;
            }
            field.classList.remove('is-invalid');
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.remove('show');
            }
            return true;
        }

        function validateAll() {
            const required = form.querySelectorAll('[data-required="true"]');
            const errors = [];
            const tabsWithErrors = new Set();

            required.forEach(field => {
                if (!validateField(field)) {
                    const label = field.closest('.form-field')?.querySelector('label')?.textContent.trim() || field.name;
                    const cleanLabel = label.replace(/\*/g, '').trim();
                    errors.push({
                        field,
                        tab: getTabForField(field),
                        message: cleanLabel
                    });
                    tabsWithErrors.add(getTabForField(field));
                }
            });

            tabBtns.forEach(btn => btn.classList.toggle('has-error', tabsWithErrors.has(btn.dataset.tab)));
            return errors;
        }

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

        form.querySelectorAll('[data-required="true"]').forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                if (field.classList.contains('is-invalid')) validateField(field);
            });
        });

        form.addEventListener('submit', function(e) {
            const errors = validateAll();
            if (errors.length > 0) {
                e.preventDefault();
                showErrors(errors);
                switchTab(errors[0].tab);
                setTimeout(() => {
                    errors[0].field.focus();
                    errors[0].field.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 150);
                return false;
            }
            errorSummary.style.display = 'none';
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submitting...';
            return true;
        });

        // State → City filter
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
                    if (opt.dataset.state === stateId) citySelect.appendChild(opt.cloneNode(true));
                });
            });
            if (stateSelect.value) stateSelect.dispatchEvent(new Event('change'));
        }

        updateProgress();
    });
</script>

@endsection