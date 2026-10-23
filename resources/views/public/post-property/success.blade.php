@extends('layouts.public')

@section('title', 'Property Submitted - RKVIT REALESTATE')

@section('content')
<div class="page-container" style="max-width:700px;">
    <div style="background:white; border-radius:20px; padding:48px 32px; text-align:center; box-shadow:var(--shadow-md); border:1px solid var(--border);">

        <div style="width:90px; height:90px; border-radius:50%; background:#ecfdf5; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; position:relative;">
            <div style="position:absolute; inset:-10px; border:2px solid #a7f3d0; border-radius:50%; animation:pulse 2s ease infinite;"></div>
            <i class="fas fa-check-circle" style="font-size:3rem; color:#10b981; position:relative; z-index:1;"></i>
        </div>
        @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(1.08); }
        }

        <h1 style="font-size:1.8rem; font-weight:800; color:var(--secondary); margin-bottom:10px;">
            Property Submitted Successfully! 🎉
        </h1>
        <p style="color:var(--text-soft); font-size:0.95rem; margin-bottom:24px;">
            Thank you for listing your property with RKVIT Realestate.
            Your submission is now under review.
        </p>

        {{-- Reference --}}
        <div style="background:#f8fafc; border:1.5px dashed var(--border); border-radius:12px; padding:20px; margin-bottom:24px;">
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; font-weight:700; margin-bottom:6px;">
                Your Reference Number
            </div>
            <div style="font-size:1.6rem; font-weight:800; color:var(--primary); letter-spacing:0.02em;">
                {{ $property->reference_no }}
            </div>
            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:6px;">
                Save this for future reference
            </div>
        </div>

        {{-- Property summary --}}
        <div style="text-align:left; background:#f8fafc; border-radius:12px; padding:20px; border:1px solid var(--border); margin-bottom:24px;">
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.6px; font-weight:700; margin-bottom:10px;">
                Property Summary
            </div>
            <div style="display:grid; gap:10px; font-size:0.88rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-soft);">Title:</span>
                    <strong style="text-align:right;">{{ Str::limit($property->title, 50) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-soft);">Type:</span>
                    <strong>{{ $property->propertyType->name ?? '-' }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-soft);">Price:</span>
                    <strong style="color:var(--primary);">{{ $property->formatted_price }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-soft);">Location:</span>
                    <strong style="text-align:right;">{{ $property->locality }}, {{ $property->city->name ?? '' }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; padding-top:10px; border-top:1px solid var(--border);">
                    <span style="color:var(--text-soft);">Status:</span>
                    <span style="background:#fffbeb; color:#d97706; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700;">
                        <i class="fas fa-clock"></i> Pending Review
                    </span>
                </div>
            </div>
        </div>

        {{-- What happens next --}}
        <div style="text-align:left; margin-bottom:28px;">
            <h3 style="font-size:0.95rem; font-weight:700; margin-bottom:14px; color:var(--text-dark);">
                <i class="fas fa-info-circle" style="color:var(--primary);"></i> What happens next?
            </h3>
            <div style="display:grid; gap:12px;">
                <div style="display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; flex-shrink:0;">1</div>
                    <div style="font-size:0.88rem; color:var(--text-soft);">
                        <strong style="color:var(--text-dark);">Under Review</strong><br>
                        Our team will verify your property details within 24 hours.
                    </div>
                </div>
                <div style="display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; flex-shrink:0;">2</div>
                    <div style="font-size:0.88rem; color:var(--text-soft);">
                        <strong style="color:var(--text-dark);">Approval Notification</strong><br>
                        You'll receive an email at <strong>{{ $property->contact_email }}</strong> once approved.
                    </div>
                </div>
                <div style="display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; flex-shrink:0;">3</div>
                    <div style="font-size:0.88rem; color:var(--text-soft);">
                        <strong style="color:var(--text-dark);">Goes Live</strong><br>
                        Your property becomes visible to thousands of potential buyers.
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center;">
            <a href="{{ route('public.my-properties') }}" class="btn-submit" style="padding:12px 24px;">
                <i class="fas fa-list"></i> My Properties
            </a>
            <a href="{{ route('public.post-property') }}" class="btn-cancel" style="padding:12px 24px;">
                <i class="fas fa-plus"></i> Post Another
            </a>
            <a href="{{ url('/') }}" class="btn-cancel" style="padding:12px 24px;">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
    </div>
</div>

<style>
    .btn-submit {
        padding: 12px 24px;
        border-radius: 9px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 6px 16px rgba(216, 35, 42, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
    }

    .btn-cancel {
        padding: 12px 24px;
        border-radius: 9px;
        background: white;
        border: 1.5px solid var(--border);
        color: var(--text-soft);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        border-color: var(--text-soft);
    }
</style>
@endsection