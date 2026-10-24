@extends('layouts.admin')

@section('title', 'Users - Admin')
@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage all customers and administrators')

@section('content')

@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="flash-alert flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ==================== STATS ==================== --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
        </div>
        <div class="stat-value">{{ $stats['customers'] ?? 0 }}</div>
        <div class="stat-label">Customers</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
        </div>
        <div class="stat-value">{{ $stats['admins'] ?? 0 }}</div>
        <div class="stat-label">Administrators</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-user-slash"></i></div>
        </div>
        <div class="stat-value">{{ $stats['inactive'] ?? 0 }}</div>
        <div class="stat-label">Inactive</div>
    </div>
</div>

{{-- ==================== MAIN PANEL ==================== --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-users"></i> All Users</h3>
            <p>{{ $users->total() ?? 0 }} registered users</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="btn-outline">
                <i class="fas fa-user"></i> Customers
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn-outline">
                <i class="fas fa-user-shield"></i> Admins
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div style="padding:18px 24px; border-bottom:1px solid var(--border); background:#fafbfc;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:200px;">
                <i class="fas fa-search" style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none;">
            </div>

            <select name="role" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; background:white;">
                <option value="">All Roles</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customers</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrators</option>
            </select>

            <button type="submit" class="btn-outline"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline"><i class="fas fa-redo"></i> Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Properties</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    {{-- User Info --}}
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,#d8232a,#a01820); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.9rem; flex-shrink:0;">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <strong style="font-size:0.9rem; display:block;">{{ $user->name ?? 'Unknown' }}</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">{{ $user->email ?? '-' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td>
                        @if (($user->role ?? 'customer') === 'admin')
                        <span class="badge badge-purple">
                            <i class="fas fa-user-shield"></i> Admin
                        </span>
                        @else
                        <span class="badge badge-info">
                            <i class="fas fa-user"></i> Customer
                        </span>
                        @endif
                    </td>

                    {{-- Properties Count --}}
                    <td>
                        <span style="font-weight:700; color:var(--text-dark);">{{ $user->properties_count ?? 0 }}</span>
                        <span style="font-size:0.75rem; color:var(--text-muted);">listings</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if ($user->is_active ?? true)
                        <span class="badge badge-success"><i class="fas fa-check"></i> Active</span>
                        @else
                        <span class="badge badge-warning"><i class="fas fa-pause"></i> Inactive</span>
                        @endif
                    </td>

                    {{-- Joined --}}
                    <td>
                        <span style="font-size:0.82rem; color:var(--text-soft);">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.users.show', $user) }}"
                                class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Toggle active --}}
                            @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn" title="{{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}"
                                    style="background:{{ ($user->is_active ?? true) ? '#fffbeb' : '#ecfdf5' }}; color:{{ ($user->is_active ?? true) ? '#d97706' : '#059669' }};">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:60px 20px;">
                        <i class="fas fa-users" style="font-size:3rem; color:#cbd5e1; margin-bottom:14px;"></i>
                        <h4 style="font-size:1rem; margin-bottom:6px;">No users found</h4>
                        <p style="color:var(--text-muted); font-size:0.85rem;">Try adjusting your filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
    <div style="padding:20px 24px; border-top:1px solid var(--border);">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection