<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->title }} - RKVIT REALESTATE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        :root {
            --primary: #d8232a;
            --primary-dark: #b71c1c;
            --secondary: #2c3e50;
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        body {
            background: #f5f7fa;
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            background: #fff;
            box-shadow: var(--shadow-sm);
            padding: 14px 0;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .logo-text h1 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .logo-text span {
            font-size: 0.65rem;
            color: var(--text-muted);
        }

        .btn-nav {
            padding: 9px 18px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.82rem;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-nav:hover {
            background: var(--primary);
            color: #fff;
        }

        /* Main */
        .breadcrumb {
            padding: 20px 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--primary);
            font-weight: 600;
        }

        .hero-img {
            background: #cbd5e1;
            border-radius: 16px;
            overflow: hidden;
            height: 420px;
            position: relative;
            margin-bottom: 24px;
        }

        .hero-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-badges {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
        }

        .badge-featured {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .badge-sale {
            background: var(--primary);
        }

        .badge-rent {
            background: #3b82f6;
        }

        .layout {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 24px;
            margin-bottom: 40px;
        }

        .panel {
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h3 {
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-header h3 i {
            color: var(--primary);
        }

        .panel-body {
            padding: 22px;
        }

        .title-section {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .title-section h1 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .title-section .location {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 16px;
        }

        .title-section .location i {
            color: var(--primary);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .price-big {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.02em;
        }

        .price-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .quick-stats {
            display: flex;
            gap: 24px;
        }

        .quick-stat {
            text-align: center;
        }

        .quick-stat strong {
            display: block;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .quick-stat span {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .description {
            color: var(--text-soft);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .detail-item {
            padding: 12px 14px;
            background: #f8fafc;
            border-radius: 9px;
            border: 1px solid var(--border);
        }

        .detail-item .label {
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-item .value {
            font-size: 0.88rem;
            font-weight: 600;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 9px;
            border: 1px solid var(--border);
        }

        .amenity-item span {
            font-size: 0.85rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .gallery-item {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Sidebar */
        .contact-card {
            background: linear-gradient(135deg, #1a2332, #2c3e50);
            color: #fff;
            border-radius: 14px;
            padding: 24px;
            position: sticky;
            top: 20px;
        }

        .contact-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .contact-info {
            margin-bottom: 20px;
        }

        .contact-info div {
            margin-bottom: 12px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-info i {
            color: #f87171;
            width: 18px;
        }

        .contact-btn {
            display: block;
            padding: 12px;
            text-align: center;
            border-radius: 9px;
            font-weight: 700;
            font-size: 0.88rem;
            margin-bottom: 8px;
        }

        .btn-call {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
        }

        .btn-whatsapp {
            background: #25d366;
            color: #fff;
        }

        .contact-btn:hover {
            opacity: 0.95;
        }

        /* Similar */
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .similar-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .similar-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .similar-card .info {
            padding: 12px;
        }

        .similar-card h4 {
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .similar-card p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .similar-card .price {
            color: var(--primary);
            font-weight: 800;
            font-size: 0.95rem;
        }

        @media (max-width:991px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .contact-card {
                position: static;
            }
        }

        @media (max-width:640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .similar-grid {
                grid-template-columns: 1fr;
            }

            .hero-img {
                height: 250px;
            }

            .title-section h1 {
                font-size: 1.3rem;
            }

            .price-big {
                font-size: 1.6rem;
            }

            .quick-stats {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="logo-area">
                <div class="logo-img">R</div>
                <div class="logo-text">
                    <h1>RKVIT REALESTATE</h1>
                    <span>PRIVATE LIMITED</span>
                </div>
            </a>
            <a href="{{ url('/') }}" class="btn-nav">Back to Home</a>
        </div>
    </header>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a> / <a href="{{ route('public.search') }}">Properties</a> / {{ Str::limit($property->title, 40) }}
        </div>

        {{-- Hero Image --}}
        @php
        $heroImg = $property->featured_image
        ? asset('storage/'.$property->featured_image)
        : ($property->primaryImage ? $property->primaryImage->url
        : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&h=600&fit=crop');
        @endphp
        <div class="hero-img">
            <img src="{{ $heroImg }}" alt="{{ $property->title }}">
            <div class="hero-badges">
                <span class="badge {{ $property->listing_type === 'rent' ? 'badge-rent' : 'badge-sale' }}">
                    <i class="fas fa-tag"></i> For {{ ucfirst($property->listing_type) }}
                </span>
                @if ($property->is_featured)
                <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                @endif
            </div>
        </div>

        {{-- Title & Price --}}
        <div class="title-section">
            <h1>{{ $property->title }}</h1>
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                {{ $property->locality }}, {{ $property->city->name ?? '' }}, {{ $property->state->name ?? '' }}
            </div>
            <div class="price-row">
                <div>
                    <div class="price-label">Price</div>
                    <div class="price-big">{{ $property->formatted_price }}</div>
                    @if ($property->is_negotiable)
                    <span style="font-size:0.75rem; color:var(--text-muted);">Negotiable</span>
                    @endif
                </div>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <strong>{{ $property->bedrooms }}</strong>
                        <span><i class="fas fa-bed"></i> Beds</span>
                    </div>
                    <div class="quick-stat">
                        <strong>{{ $property->bathrooms }}</strong>
                        <span><i class="fas fa-bath"></i> Baths</span>
                    </div>
                    <div class="quick-stat">
                        <strong>{{ number_format($property->area) }}</strong>
                        <span>{{ $property->area_unit }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="layout">
            {{-- Main Content --}}
            <div>
                @if ($property->description)
                <div class="panel">
                    <div class="panel-header">
                        <h3><i class="fas fa-align-left"></i> Description</h3>
                    </div>
                    <div class="panel-body">
                        <p class="description">{{ $property->description }}</p>
                    </div>
                </div>
                @endif

                <div class="panel">
                    <div class="panel-header">
                        <h3><i class="fas fa-list-alt"></i> Property Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="details-grid">
                            @foreach ([
                            ['Category', $property->category->name ?? '-'],
                            ['Type', $property->propertyType->name ?? '-'],
                            ['Reference', $property->reference_no],
                            ['Furnishing', ucwords(str_replace('-', ' ', $property->furnishing ?? '-'))],
                            ['Facing', $property->facing ? ucwords(str_replace('-', ' ', $property->facing)) : '-'],
                            ['Ownership', $property->ownership ? ucfirst(str_replace('-', ' ', $property->ownership)) : '-'],
                            ['Age', $property->age_of_property ? $property->age_of_property . ' years' : '-'],
                            ['Floor', $property->floor_number ?? '-'],
                            ['Total Floors', $property->total_floors ?? '-'],
                            ['Parking', $property->parking_spaces ?: '-'],
                            ] as $d)
                            <div class="detail-item">
                                <div class="label">{{ $d[0] }}</div>
                                <div class="value">{{ $d[1] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($property->amenities->count())
                <div class="panel">
                    <div class="panel-header">
                        <h3><i class="fas fa-star"></i> Amenities</h3>
                    </div>
                    <div class="panel-body">
                        <div class="amenities-grid">
                            @foreach ($property->amenities as $amenity)
                            <div class="amenity-item">
                                <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }};"></i>
                                <span>{{ $amenity->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if ($property->images->count() > 1)
                <div class="panel">
                    <div class="panel-header">
                        <h3><i class="fas fa-images"></i> Gallery ({{ $property->images->count() }})</h3>
                    </div>
                    <div class="panel-body">
                        <div class="gallery-grid">
                            @foreach ($property->images as $img)
                            <div class="gallery-item">
                                <img src="{{ $img->url }}" alt="Gallery image" loading="lazy">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div>
                <div class="contact-card">
                    <h3><i class="fas fa-headset"></i> Contact Owner</h3>
                    <div class="contact-info">
                        @if ($property->contact_name)
                        <div><i class="fas fa-user"></i> {{ $property->contact_name }}</div>
                        @endif
                        @if ($property->contact_phone)
                        <div><i class="fas fa-phone"></i> {{ $property->contact_phone }}</div>
                        @endif
                        @if ($property->contact_email)
                        <div><i class="fas fa-envelope"></i> {{ $property->contact_email }}</div>
                        @endif
                    </div>

                    @if ($property->contact_phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $property->contact_phone) }}" class="contact-btn btn-call">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                    @endif
                    @if ($property->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->contact_whatsapp) }}" target="_blank" class="contact-btn btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    @endif
                </div>

                <div class="panel" style="margin-top:20px;">
                    <div class="panel-header">
                        <h3><i class="fas fa-chart-bar"></i> Quick Stats</h3>
                    </div>
                    <div class="panel-body">
                        <div class="detail-item" style="margin-bottom:10px;">
                            <div class="label">Views</div>
                            <div class="value">{{ number_format($property->views_count) }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="label">Listed On</div>
                            <div class="value">{{ $property->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Similar Properties --}}
        @if ($similarProperties->count())
        <div style="margin-bottom:48px;">
            <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:20px;">Similar Properties</h2>
            <div class="similar-grid">
                @foreach ($similarProperties as $sim)
                @php
                $simg = $sim->primaryImage
                ? $sim->primaryImage->url
                : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=400&h=300&fit=crop';
                @endphp
                <a href="{{ route('public.property.show', $sim) }}" class="similar-card">
                    <img src="{{ $simg }}" alt="{{ $sim->title }}">
                    <div class="info">
                        <h4>{{ Str::limit($sim->title, 40) }}</h4>
                        <p><i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> {{ $sim->city->name ?? '-' }}</p>
                        <div class="price">{{ $sim->formatted_price }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</body>

</html>