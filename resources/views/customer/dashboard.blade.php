@extends('layouts.customer')

@section('title', 'My Dashboard - RKVIT REALESTATE')
@section('page-title', 'My Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name)

@section('content')

@if (session('success'))
<div class="flash-alert flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">My Properties</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['rejected'] }}</div>
        <div class="stat-label">Rejected</div>
    </div>
</div>

{{-- Quick actions --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            <p>Get started quickly</p>
        </div>
    </div>
    <div style="padding:20px; display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
        <a href="{{ route('customer.properties.create') }}" style="padding:20px; background:linear-gradient(135deg,#d8232a,#a01820); color:white; border-radius:12px; text-decoration:none; display:flex; align-items:center; gap:14px;">
            <i class="fas fa-plus-circle" style="font-size:1.8rem; opacity:0.9;"></i>
            <div>
                <div style="font-weight:700; margin-bottom:2px;">Post New Property</div>
                <div style="font-size:0.75rem; opacity:0.9;">List your property free</div>
            </div>
        </a>
        <a href="{{ route('customer.properties.index') }}" style="padding:20px; background:white; border:1px solid var(--border); border-radius:12px; display:flex; align-items:center; gap:14px;">
            <i class="fas fa-building" style="font-size:1.8rem; color:var(--primary);"></i>
            <div>
                <div style="font-weight:700; margin-bottom:2px;">My Properties</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">View all listings</div>
            </div>
        </a>
        <a href="{{ route('customer.profile') }}" style="padding:20px; background:white; border:1px solid var(--border); border-radius:12px; display:flex; align-items:center; gap:14px;">
            <i class="fas fa-user-cog" style="font-size:1.8rem; color:#3b82f6;"></i>
            <div>
                <div style="font-weight:700; margin-bottom:2px;">Profile Settings</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">Update your details</div>
            </div>
        </a>
    </div>
</div>

{{-- Recent properties --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-history"></i> Recent Properties</h3>
            <p>Your latest submissions</p>
        </div>
        <a href="{{ route('customer.properties.index') }}" class="btn-outline">View All</a>
    </div>
    @if ($recentProperties->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentProperties as $prop)
                <tr>
                    <td>
                        <div class="property-cell">
                            @php
                            $img = $prop->primaryImage
                            ? $prop->primaryImage->url
                            : ($prop->featured_image ? asset('storage/'.$prop->featured_image) : null);
                            @endphp
                            @if ($img)
                            <img src="{{ $img }}" class="property-thumb" alt="">
                            @else
                            <div class="property-thumb" style="display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fas fa-image"></i></div>
                            @endif
                            <div class="property-cell-info">
                                <strong>{{ Str::limit($prop->title, 35) }}</strong>
                                <span>{{ $prop->city->name ?? '-' }} • {{ $prop->reference_no }}</span>
                            </div>
                        </div>
                    </td>
                    <td><strong style="color:var(--primary);">{{ $prop->formatted_price }}</strong></td>
                    <td>
                        @php $badge = $prop->status_badge; @endphp
                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </td>
                    <td style="font-size:0.78rem; color:var(--text-muted);">{{ $prop->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('customer.properties.show', $prop) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('customer.properties.edit', $prop) }}" class="action-btn edit"><i class="fas fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center; padding:50px 20px;">
        <i class="fas fa-building" style="font-size:3rem; color:#cbd5e1; margin-bottom:14px;"></i>
        <h4 style="font-size:1rem; margin-bottom:6px;">No properties yet</h4>
        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:16px;">Start by posting your first property.</p>
        <a href="{{ route('customer.properties.create') }}" class="btn-solid"><i class="fas fa-plus"></i> Post Property</a>
    </div>
    @endif
</div>

@endsection