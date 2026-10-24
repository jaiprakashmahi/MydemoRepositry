@extends('layouts.admin')

@section('title', 'Admin Profile - RKVIT REALESTATE')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your administrator account and security')

@section('content')

{{-- ==================== FLASH MESSAGES ==================== --}}
@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button onclick="this.parentElement.remove()" style="margin-left:auto; background:none; border:none; color:inherit; cursor:pointer; opacity:0.6;">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if (session('password_success'))
<div class="flash-alert flash-success">
    <i class="fas fa-lock"></i> {{ session('password_success') }}
    <button onclick="this.parentElement.remove()" style="margin-left:auto; background:none; border:none; color:inherit; cursor:pointer; opacity:0.6;">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if (session('error'))
<div class="flash-alert flash-error">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ==================== ADMIN BANNER ==================== --}}
<div style="background:linear-gradient(135deg, #1a2332, #2c3e50); border-radius:16px; padding:28px; margin-bottom:24px; color:white; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:-40px; width:180px; height:180px; background:rgba(216,35,42,0.15); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-60px; left:30%; width:140px; height:140px; background:rgba(216,35,42,0.08); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; gap:20px; position:relative; z-index:2; flex-wrap:wrap;">
        @if ($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}"
            style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,0.2);">
        @else
        <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#d8232a,#a01820); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.8rem; border:3px solid rgba(255,255,255,0.2); flex-shrink:0;">
            {{ $user->initials ?? strtoupper(substr($user->name ?? 'A', 0, 2)) }}
        </div>
        @endif

        <div style="flex:1; min-width:200px;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                <h2 style="font-size:1.5rem; font-weight:800; letter-spacing:-0.02em;">{{ $user->name ?? 'Admin' }}</h2>
                <span style="background:rgba(216,35,42,0.9); padding:4px 12px; border-radius:20px; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                    <i class="fas fa-user-shield"></i> Administrator
                </span>
            </div>
            <p style="font-size:0.88rem; opacity:0.85;">
                <i class="fas fa-envelope"></i> {{ $user->email ?? '-' }}
                @if ($user->phone)
                &nbsp;•&nbsp; <i class="fas fa-phone"></i> {{ $user->phone }}
                @endif
            </p>
        </div>

        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <div style="text-align:center; padding:12px 20px; background:rgba(255,255,255,0.1); border-radius:10px; backdrop-filter:blur(10px);">
                <div style="font-size:1.4rem; font-weight:800;">{{ $stats['total_properties'] ?? 0 }}</div>
                <div style="font-size:0.68rem; opacity:0.8; text-transform:uppercase; letter-spacing:0.5px;">Properties</div>
            </div>
            <div style="text-align:center; padding:12px 20px; background:rgba(255,255,255,0.1); border-radius:10px; backdrop-filter:blur(10px);">
                <div style="font-size:1.4rem; font-weight:800;">{{ $stats['total_users'] ?? 0 }}</div>
                <div style="font-size:0.68rem; opacity:0.8; text-transform:uppercase; letter-spacing:0.5px;">Users</div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== LAYOUT ==================== --}}
<div style="display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start;">

    {{-- ==================== LEFT: FORMS ==================== --}}
    <div>

        {{-- ========== BASIC DETAILS CARD ========== --}}
        <div class="panel" style="margin-bottom:24px;">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-user-circle"></i> Basic Details</h3>
                    <p>Update your personal information and profile photo</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="basicForm">
                @csrf
                @method('PUT')

                <div style="padding:28px;">

                    {{-- Validation Errors for Basic --}}
                    @if ($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
                        <strong><i class="fas fa-exclamation-circle"></i> Please fix:</strong>
                        <ul style="margin-top:8px; padding-left:20px; font-size:0.85rem;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Avatar Upload --}}
                    <div style="display:flex; align-items:center; gap:24px; margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--border);">
                        <div style="position:relative; flex-shrink:0;">
                            @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                id="avatarPreview"
                                style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid white; box-shadow:0 4px 16px rgba(0,0,0,0.12);">
                            @else
                            <div id="avatarPreview"
                                style="width:100px; height:100px; border-radius:50%; background:linear-gradient(135deg,#d8232a,#a01820); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:2rem; border:3px solid white; box-shadow:0 4px 16px rgba(0,0,0,0.12);">
                                {{ $user->initials ?? strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                            </div>
                            @endif
                            <label for="avatarInput"
                                style="position:absolute; bottom:0; right:0; width:32px; height:32px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; cursor:pointer; border:2.5px solid white; box-shadow:0 2px 8px rgba(0,0,0,0.15); transition:all 0.2s;"
                                title="Change avatar">
                                <i class="fas fa-camera" style="font-size:0.75rem;"></i>
                            </label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;">
                        </div>
                        <div style="flex:1; min-width:0;">
                            <h4 style="font-size:1rem; font-weight:700; margin-bottom:4px;">Profile Photo</h4>
                            <p style="font-size:0.82rem; color:var(--text-muted); margin-bottom:10px;">
                                Click the camera icon to upload a new photo. JPG, PNG, WEBP — Max 2MB.
                            </p>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <span class="badge badge-purple" style="font-size:0.68rem;">
                                    <i class="fas fa-user-shield"></i> Administrator
                                </span>
                                <span class="badge badge-success" style="font-size:0.68rem;">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $user->is_active ?? true ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Fields --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                        <div style="grid-column:span 2;">
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Full Name <span style="color:var(--primary);">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                            @error('name')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Email Address <span style="color:var(--primary);">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                            @error('email')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Phone Number
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="+91-9835634422" class="form-input">
                            @error('phone')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Admin Since
                            </label>
                            <input type="text" value="{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}" readonly
                                class="form-input" style="background:#f1f5f9; cursor:not-allowed; color:var(--text-muted);">
                        </div>

                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Last Login
                            </label>
                            <input type="text" value="{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}" readonly
                                class="form-input" style="background:#f1f5f9; cursor:not-allowed; color:var(--text-muted);">
                        </div>

                    </div>

                    {{-- Save Button --}}
                    <div style="display:flex; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid var(--border);">
                        <button type="submit" class="btn-solid" style="padding:12px 32px;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ========== PASSWORD CARD ========== --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-lock"></i> Change Password</h3>
                    <p>Update your administrator account password</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" id="passwordForm">
                @csrf
                @method('PUT')

                <div style="padding:28px;">

                    {{-- Validation Errors for Password --}}
                    @if ($errors->has('current_password') || $errors->has('password'))
                    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
                        <strong><i class="fas fa-exclamation-circle"></i> Please fix:</strong>
                        <ul style="margin-top:8px; padding-left:20px; font-size:0.85rem;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Admin Warning --}}
                    <div style="background:#fffbeb; border:1px solid #fde68a; padding:12px 16px; border-radius:9px; margin-bottom:24px; font-size:0.82rem; color:#92400e; display:flex; align-items:flex-start; gap:10px;">
                        <i class="fas fa-exclamation-triangle" style="margin-top:2px;"></i>
                        <div>
                            <strong>Admin Account Security:</strong> As an administrator, use a strong, unique password. Never share it with anyone.
                        </div>
                    </div>

                    <div style="display:grid; gap:20px;">

                        {{-- Current Password --}}
                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Current Password <span style="color:var(--primary);">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" name="current_password" id="currentPassword" required
                                    placeholder="Enter your current password" class="form-input" style="padding-right:44px;">
                                <button type="button" class="toggle-password" data-target="#currentPassword"
                                    style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                New Password <span style="color:var(--primary);">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="newPassword" required
                                    placeholder="Enter new password (min 8 characters)" class="form-input" style="padding-right:44px;">
                                <button type="button" class="toggle-password" data-target="#newPassword"
                                    style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror

                            {{-- Password strength meter --}}
                            <div style="margin-top:8px;">
                                <div style="display:flex; gap:4px; height:4px; margin-bottom:4px;">
                                    <div id="strengthBar1" style="flex:1; height:4px; background:#e2e8f0; border-radius:2px; transition:all 0.3s;"></div>
                                    <div id="strengthBar2" style="flex:1; height:4px; background:#e2e8f0; border-radius:2px; transition:all 0.3s;"></div>
                                    <div id="strengthBar3" style="flex:1; height:4px; background:#e2e8f0; border-radius:2px; transition:all 0.3s;"></div>
                                    <div id="strengthBar4" style="flex:1; height:4px; background:#e2e8f0; border-radius:2px; transition:all 0.3s;"></div>
                                </div>
                                <div id="strengthText" style="font-size:0.72rem; color:var(--text-muted);">
                                    Password strength: <strong>—</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px; color:var(--text-dark);">
                                Confirm New Password <span style="color:var(--primary);">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" name="password_confirmation" id="confirmPassword" required
                                    placeholder="Re-enter new password" class="form-input" style="padding-right:44px;">
                                <button type="button" class="toggle-password" data-target="#confirmPassword"
                                    style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:0.9rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="matchIndicator" style="font-size:0.72rem; margin-top:6px; display:none;"></div>
                        </div>

                    </div>

                    {{-- Save Button --}}
                    <div style="display:flex; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid var(--border);">
                        <button type="submit" class="btn-solid" style="padding:12px 32px;">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    {{-- ==================== RIGHT: PLATFORM OVERVIEW ==================== --}}
    <div>

        {{-- Platform Stats --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-chart-line"></i> Platform Overview</h3>
                </div>
            </div>
            <div style="padding:20px;">

                <div style="display:grid; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-building" style="color:var(--primary);"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Total Properties</span>
                        </div>
                        <strong style="font-size:1rem; color:var(--text-dark);">{{ $stats['total_properties'] ?? 0 }}</strong>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-check-circle" style="color:#10b981;"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Active Listings</span>
                        </div>
                        <strong style="font-size:1rem; color:var(--text-dark);">{{ $stats['active_properties'] ?? 0 }}</strong>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#fffbeb; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-clock" style="color:#d97706;"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Pending Approval</span>
                        </div>
                        <strong style="font-size:1rem; color:#d97706;">{{ $stats['pending_properties'] ?? 0 }}</strong>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-users" style="color:#3b82f6;"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Total Customers</span>
                        </div>
                        <strong style="font-size:1rem; color:var(--text-dark);">{{ $stats['total_users'] ?? 0 }}</strong>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-eye" style="color:#8b5cf6;"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Total Views</span>
                        </div>
                        <strong style="font-size:1rem; color:var(--text-dark);">{{ number_format($stats['total_views'] ?? 0) }}</strong>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; background:#f8fafc; border-radius:9px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-layer-group" style="color:#f59e0b;"></i>
                            <span style="font-size:0.85rem; font-weight:500;">Categories</span>
                        </div>
                        <strong style="font-size:1rem; color:var(--text-dark);">{{ $stats['total_categories'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                </div>
            </div>
            <div style="padding:16px; display:grid; gap:8px;">
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}"
                    style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:#fffbeb; border-radius:8px; border:1px solid #fde68a; text-decoration:none; color:#92400e; font-size:0.85rem; font-weight:600;">
                    <i class="fas fa-clock"></i> Review Pending ({{ $stats['pending_properties'] ?? 0 }})
                </a>
                <a href="{{ route('admin.users.index') }}"
                    style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:#eff6ff; border-radius:8px; border:1px solid #bfdbfe; text-decoration:none; color:#1e40af; font-size:0.85rem; font-weight:600;">
                    <i class="fas fa-users"></i> Manage Users
                </a>
                <a href="{{ route('admin.properties.index') }}"
                    style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:#f8fafc; border-radius:8px; border:1px solid var(--border); text-decoration:none; color:var(--text-soft); font-size:0.85rem; font-weight:600;">
                    <i class="fas fa-building"></i> All Properties
                </a>
            </div>
        </div>

        {{-- Security Tips --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-shield-alt"></i> Security Tips</h3>
                </div>
            </div>
            <div style="padding:20px;">
                <div style="display:grid; gap:14px;">
                    <div style="display:flex; gap:10px;">
                        <i class="fas fa-check-circle" style="color:#10b981; margin-top:2px;"></i>
                        <div style="font-size:0.82rem; color:var(--text-soft); line-height:1.5;">
                            Use a unique password with 12+ characters for your admin account.
                        </div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <i class="fas fa-check-circle" style="color:#10b981; margin-top:2px;"></i>
                        <div style="font-size:0.82rem; color:var(--text-soft); line-height:1.5;">
                            Log out when using a shared or public computer.
                        </div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <i class="fas fa-check-circle" style="color:#10b981; margin-top:2px;"></i>
                        <div style="font-size:0.82rem; color:var(--text-soft); line-height:1.5;">
                            Never share your admin credentials with anyone.
                        </div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <i class="fas fa-check-circle" style="color:#10b981; margin-top:2px;"></i>
                        <div style="font-size:0.82rem; color:var(--text-soft); line-height:1.5;">
                            Regularly review admin users and their permissions.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ==================== STYLES ==================== --}}
<style>
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
        color: var(--text-dark);
    }

    .form-input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
    }

    .form-input::placeholder {
        color: var(--text-muted);
    }

    .field-error {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 6px;
    }

    .flash-alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .flash-error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .toggle-password:hover {
        color: var(--primary) !important;
    }

    @media (max-width: 991px) {
        div[style*="grid-template-columns:1fr 320px"] {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 640px) {
        form>div[style*="padding:28px"]>div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="grid-column:span 2"] {
            grid-column: span 1 !important;
        }
    }
</style>

{{-- ==================== SCRIPT ==================== --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== Password toggle =====
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.target);
                if (!target) return;
                const isPassword = target.type === 'password';
                target.type = isPassword ? 'text' : 'password';
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        });

        // ===== Avatar preview =====
        const avatarInput = document.getElementById('avatarInput');
        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image must be under 2MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('avatarPreview');
                    if (!preview) return;

                    if (preview.tagName === 'IMG') {
                        preview.src = event.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.src = event.target.result;
                        img.style.cssText = 'width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid white; box-shadow:0 4px 16px rgba(0,0,0,0.12);';
                        preview.replaceWith(img);
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // ===== Password strength meter =====
        const newPassword = document.getElementById('newPassword');
        const strengthBars = [
            document.getElementById('strengthBar1'),
            document.getElementById('strengthBar2'),
            document.getElementById('strengthBar3'),
            document.getElementById('strengthBar4')
        ];
        const strengthText = document.getElementById('strengthText');

        function calculateStrength(pwd) {
            let score = 0;
            if (pwd.length >= 8) score++;
            if (pwd.length >= 12) score++;
            if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
            if (/\d/.test(pwd)) score++;
            if (/[^a-zA-Z0-9]/.test(pwd)) score++;
            return Math.min(score, 4);
        }

        function updateStrength() {
            if (!newPassword || !strengthText) return;
            const pwd = newPassword.value;
            const strength = pwd.length === 0 ? 0 : calculateStrength(pwd);

            const colors = ['#e2e8f0', '#dc2626', '#f59e0b', '#3b82f6', '#10b981'];
            const labels = ['—', 'Weak', 'Fair', 'Good', 'Strong'];

            strengthBars.forEach((bar, i) => {
                if (bar) bar.style.background = i < strength ? colors[strength] : '#e2e8f0';
            });

            strengthText.innerHTML = pwd.length === 0 ?
                'Password strength: <strong>—</strong>' :
                `Password strength: <strong style="color:${colors[strength]}">${labels[strength]}</strong>`;
        }

        if (newPassword) newPassword.addEventListener('input', updateStrength);

        // ===== Password match indicator =====
        const confirmPassword = document.getElementById('confirmPassword');
        const matchIndicator = document.getElementById('matchIndicator');

        function checkMatch() {
            if (!confirmPassword || !matchIndicator || !newPassword) return;
            if (confirmPassword.value.length === 0) {
                matchIndicator.style.display = 'none';
                return;
            }

            matchIndicator.style.display = 'block';
            if (newPassword.value === confirmPassword.value) {
                matchIndicator.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981;"></i> <span style="color:#10b981;">Passwords match</span>';
            } else {
                matchIndicator.innerHTML = '<i class="fas fa-times-circle" style="color:#dc2626;"></i> <span style="color:#dc2626;">Passwords do not match</span>';
            }
        }

        if (confirmPassword) confirmPassword.addEventListener('input', checkMatch);
        if (newPassword) newPassword.addEventListener('input', checkMatch);

        // ===== Client-side validation =====
        const basicForm = document.getElementById('basicForm');
        if (basicForm) {
            basicForm.addEventListener('submit', function(e) {
                const name = this.querySelector('input[name="name"]');
                const email = this.querySelector('input[name="email"]');
                if (!name.value.trim()) {
                    e.preventDefault();
                    alert('Please enter your name.');
                    name.focus();
                    return;
                }
                if (!email.value.trim()) {
                    e.preventDefault();
                    alert('Please enter your email.');
                    email.focus();
                    return;
                }
            });
        }

        const passwordForm = document.getElementById('passwordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const current = this.querySelector('input[name="current_password"]');
                const newPwd = this.querySelector('input[name="password"]');
                const confirm = this.querySelector('input[name="password_confirmation"]');

                if (!current.value.trim()) {
                    e.preventDefault();
                    alert('Please enter your current password.');
                    current.focus();
                    return;
                }
                if (newPwd.value.length < 8) {
                    e.preventDefault();
                    alert('New password must be at least 8 characters.');
                    newPwd.focus();
                    return;
                }
                if (newPwd.value !== confirm.value) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    confirm.focus();
                    return;
                }
            });
        }
    });
</script>

@endsection