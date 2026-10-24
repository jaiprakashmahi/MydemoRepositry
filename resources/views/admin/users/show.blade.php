@extends('layouts.admin')

@section('title', $user->name . ' - User Details')
@section('page-title', 'User Details')
@section('page-subtitle', 'View user profile and their properties')

@section('content')

<div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <a href="{{ route('admin.users.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-solid">
            <i class="fas fa-edit"></i> Edit User
        </a>
        @if ($user->id !== auth()->id())
        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" style="display:inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn-outline" style="color:{{ ($user->is_active ?? true) ? '#d97706' : '#059669' }};">
                <i class="fas fa-power-off"></i>
                {{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        @endif
    </div>
</div>

{{-- User Header Card --}}
<div class="panel" style="margin-bottom:24px;">
    <div style="padding:28px; display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
        <div style="width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg,#d8232a,#a01820); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:2rem; flex-shrink:0;">
            {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
        </div>
        <div style="flex:1; min-width:200px;">
            <h1 style="font-size:1.6rem; font-weight:800; color:var(--text-dark); margin-bottom:4px;">
                {{ $user->name ?? 'Unknown User' }}
            </h1>
            <p style="font-size:0.9rem; color:var(--text-soft); margin-bottom:10px;">
                <i class="fas fa-envelope"></i> {{ $user->email ?? '-' }}
            </p>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                @if (($user->role ?? 'customer') === 'admin')
                <span class="badge badge-purple"><i class="fas fa-user-shield"></i> Administrator</span>
                @else
                <span class="badge badge-info"><i class="fas fa-user"></i> Customer</span>
                @endif

                @if ($user->is_active ?? true)
                <span class="badge badge-success"><i class="fas fa-check"></i> Active</span>
                @else
                <span class="badge badge-warning"><i class="fas fa-pause"></i> Inactive</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Details Grid --}}
<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
    <div class="panel" style="padding:20px; text-align:center;">
        <div style="font-size:1.8rem; font-weight:800; color:var(--primary);">{{ $user->properties_count ?? 0 }}</div>
        <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:700;">Properties</div>
    </div>
    <div class="panel" style="padding:20px; text-align:center;">
        <div style="font-size:1.8rem; font-weight:800; color:var(--text-dark);">
            {{ $user->created_at ? $user->created_at->format('M Y') : '-' }}
        </div>
        <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:700;">Joined</div>
    </div>
    <div class="panel" style="padding:20px; text-align:center;">
        <div style="font-size:1.8rem; font-weight:800; color:var(--text-dark);">
            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
        </div>
        <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:700;">Last Login</div>
    </div>
    <div class="panel" style="padding:20px; text-align:center;">
        <div style="font-size:1.8rem; font-weight:800; color:var(--text-dark);">
            {{ $user->phone ?? '-' }}
        </div>
        <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; font-weight:700;">Phone</div>
    </div>
</div>

{{-- User's Properties --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-building"></i> Properties by {{ $user->name ?? 'User' }}</h3>
            <p>Recent listings from this user</p>
        </div>
    </div>

    @if ($user->properties && $user->properties->count() > 0)
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->properties as $property)
                <tr>
                    <td>
                        <strong style="font-size:0.88rem;">{{ Str::limit($property->title ?? 'Untitled', 45) }}</strong>
                        <div style="font-size:0.72rem; color:var(--text-muted);">{{ $property->reference_no ?? '-' }}</div>
                    </td>
                    <td>
                        <strong style="color:var(--primary);">
                            {{ $property->formatted_price ?? ('₹ ' . number_format($property->price ?? 0)) }}
                        </strong>
                    </td>
                    <td>
                        @php
                        $statusMap = [
                        'active' => ['class' => 'badge-success', 'label' => 'Active'],
                        'pending' => ['class' => 'badge-warning', 'label' => 'Pending'],
                        'draft' => ['class' => 'badge-info', 'label' => 'Draft'],
                        'inactive' => ['class' => 'badge-warning', 'label' => 'Inactive'],
                        'sold' => ['class' => 'badge-purple', 'label' => 'Sold'],
                        'rented' => ['class' => 'badge-purple', 'label' => 'Rented'],
                        'rejected' => ['class' => 'badge-danger', 'label' => 'Rejected'],
                        ];
                        $badge = $statusMap[$property->status ?? 'draft'] ?? ['class' => 'badge-info', 'label' => ucfirst($property->status ?? 'Draft')];
                        @endphp
                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.properties.show', $property) }}" class="action-btn view">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center; padding:50px 20px;">
        <i class="fas fa-building" style="font-size:2.5rem; color:#cbd5e1; margin-bottom:12px;"></i>
        <p style="color:var(--text-muted); font-size:0.9rem;">This user hasn't posted any properties yet.</p>
    </div>
    @endif
</div>

{{-- Danger Zone --}}
@if ($user->id !== auth()->id())
<div class="panel" style="border-color:#fecaca; margin-top:24px;">
    <div style="padding:20px 24px; border-bottom:1px solid #fecaca; background:#fef2f2;">
        <h3 style="font-size:0.95rem; font-weight:700; color:#dc2626;">
            <i class="fas fa-exclamation-triangle"></i> Danger Zone
        </h3>
    </div>
    <div style="padding:20px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
            <strong style="font-size:0.88rem;">Delete this user</strong>
            <p style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;">
                This will permanently remove the user and all their data. This action cannot be undone.
            </p>
        </div>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to permanently delete this user? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit" style="background:#dc2626; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:600; font-size:0.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                <i class="fas fa-trash"></i> Delete User
            </button>
        </form>
    </div>
</div>
@endif

@endsection