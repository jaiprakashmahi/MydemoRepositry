@extends('layouts.admin')

@section('title', 'Admin Dashboard - RKVIT REALESTATE')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Overview of your real estate platform')

@section('content')

@if (session('success'))
<div class="flash-alert flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_properties'] }}</div>
        <div class="stat-label">Total Properties</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active_properties'] }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending_properties'] }}</div>
        <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Customers</div>
    </div>
</div>

{{-- Secondary stats --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:26px;">
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-eye"></i></div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
        <div class="stat-label">Total Views</div>
    </div>
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['rejected_properties'] }}</div>
        <div class="stat-label">Rejected</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
        <div class="stat-label">Categories</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_amenities'] }}</div>
        <div class="stat-label">Amenities</div>
    </div>
</div>

{{-- Pending approvals --}}
@if ($pendingProperties->count())
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-clock"></i> Pending Approvals</h3>
            <p>{{ $pendingProperties->count() }} properties waiting for review</p>
        </div>
        <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="btn-outline">
            <i class="fas fa-eye"></i> View All Pending
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Submitted</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingProperties as $prop)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ Str::limit($prop->title, 40) }}</div>
                        <div style="font-size:0.72rem; color:var(--text-muted);">Ref: {{ $prop->reference_no }}</div>
                    </td>
                    <td>{{ $prop->user->name ?? '-' }}</td>
                    <td>{{ $prop->city->name ?? '-' }}</td>
                    <td><strong style="color:var(--primary);">{{ $prop->formatted_price }}</strong></td>
                    <td style="font-size:0.82rem;">{{ $prop->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="action-btns" style="justify-content:flex-end; gap:6px;">
                            <a href="{{ route('admin.properties.show', $prop) }}" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.properties.approve', $prop) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn" title="Approve" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.properties.reject', $prop) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn delete" title="Reject" onclick="return confirm('Reject this property?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Two column: Recent Properties + Recent Users --}}
<div style="display:grid; grid-template-columns:1.5fr 1fr; gap:24px;">

    <div class="panel">
        <div class="panel-header">
            <div>
                <h3><i class="fas fa-history"></i> Recent Properties</h3>
                <p>Latest listings</p>
            </div>
            <a href="{{ route('admin.properties.index') }}" class="btn-outline">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Status</th>
                        <th>Added</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentProperties as $prop)
                    <tr>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ Str::limit($prop->title, 35) }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">
                                {{ $prop->user->name ?? '-' }} • {{ $prop->city->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @php $badge = $prop->status_badge; @endphp
                            <span class="badge badge-{{ str_replace('badge-', '', $badge['class']) }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $prop->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h3><i class="fas fa-users"></i> Recent Users</h3>
                <p>New registrations</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-outline">View All</a>
        </div>
        <div style="padding:16px 20px;">
            @foreach ($recentUsers as $user)
            <div style="display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--border);">
                <div style="width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#d8232a,#a01820); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.85rem; flex-shrink:0;">
                    {{ $user->initials }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->name }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</div>
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted);">{{ $user->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection