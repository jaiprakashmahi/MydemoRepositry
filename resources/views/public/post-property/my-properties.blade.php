@extends('layouts.public')

@section('title', 'My Properties - RKVIT REALESTATE')

@section('content')
<div class="page-container">

    <div class="page-header" style="text-align:left; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="font-size:1.6rem;">My Properties</h1>
            <p>Manage all your submitted properties</p>
        </div>
        <a href="{{ route('public.post-property') }}" class="btn-submit" style="padding:11px 22px;">
            <i class="fas fa-plus"></i> Post New
        </a>
    </div>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px;">
        <div style="background:white; border:1px solid var(--border); border-radius:12px; padding:18px; text-align:center;">
            <div style="font-size:1.6rem; font-weight:800; color:var(--text-dark);">{{ $stats['total'] }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Total</div>
        </div>
        <div style="background:white; border:1px solid var(--border); border-radius:12px; padding:18px; text-align:center;">
            <div style="font-size:1.6rem; font-weight:800; color:#10b981;">{{ $stats['active'] }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Active</div>
        </div>
        <div style="background:white; border:1px solid var(--border); border-radius:12px; padding:18px; text-align:center;">
            <div style="font-size:1.6rem; font-weight:800; color:#d97706;">{{ $stats['pending'] }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Pending</div>
        </div>
        <div style="background:white; border:1px solid var(--border); border-radius:12px; padding:18px; text-align:center;">
            <div style="font-size:1.6rem; font-weight:800; color:#dc2626;">{{ $stats['rejected'] }}</div>
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Rejected</div>
        </div>
    </div>

    {{-- List --}}
    @forelse ($properties as $property)
    <div style="background:white; border:1px solid var(--border); border-radius:12px; padding:18px; margin-bottom:14px; display:flex; gap:18px; align-items:center; flex-wrap:wrap;">
        @php
        $img = $property->featured_image
        ? asset('storage/'.$property->featured_image)
        : ($property->primaryImage ? $property->primaryImage->url
        : 'https://via.placeholder.com/120x80/eef2f6/94a3b8?text=No+Image');
        @endphp
        <img src="{{ $img }}" style="width:120px; height:80px; object-fit:cover; border-radius:8px; flex-shrink:0;">
        <div style="flex:1; min-width:200px;">
            <div style="font-weight:700; font-size:0.95rem; margin-bottom:4px;">{{ $property->title }}</div>
            <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:6px;">
                <i class="fas fa-map-marker-alt"></i> {{ $property->city->name ?? '' }} • Ref: {{ $property->reference_no }}
            </div>
            <div style="font-size:0.9rem; font-weight:700; color:var(--primary);">{{ $property->formatted_price }}</div>
        </div>
        <div style="text-align:right; min-width:120px;">
            @php $badge = $property->status_badge; @endphp
            <span style="display:inline-block; padding:5px 12px; border-radius:20px; font-size:0.72rem; font-weight:700;
                        @if($property->status === 'active') background:#ecfdf5; color:#059669;
                        @elseif($property->status === 'pending') background:#fffbeb; color:#d97706;
                        @elseif($property->status === 'rejected') background:#fef2f2; color:#dc2626;
                        @else background:#f1f5f9; color:#64748b; @endif">
                {{ $badge['label'] }}
            </span>
            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:6px;">
                {{ $property->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:60px 20px; background:white; border-radius:16px; border:1px solid var(--border);">
        <i class="fas fa-building" style="font-size:3rem; color:#cbd5e1; margin-bottom:14px;"></i>
        <h3 style="font-size:1.1rem; color:var(--text-dark); margin-bottom:6px;">No properties yet</h3>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:20px;">You haven't submitted any properties.</p>
        <a href="{{ route('public.post-property') }}" class="btn-submit" style="padding:12px 28px; display:inline-flex;">
            <i class="fas fa-plus"></i> Post Your First Property
        </a>
    </div>
    @endforelse

    @if ($properties->hasPages())
    <div style="margin-top:20px;">{{ $properties->links() }}</div>
    @endif
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
        box-shadow: 0 4px 12px rgba(216, 35, 42, 0.25);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
    }

    @media (max-width:768px) {
        div[style*="grid-template-columns:repeat(4"] {
            grid-template-columns: 1fr 1fr !important;
        }
    }
</style>
@endsection