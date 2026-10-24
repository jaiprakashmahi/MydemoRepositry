@extends('layouts.admin')

@section('title', 'Edit User - Admin')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user account details')

@section('content')

<div style="max-width:900px; margin:0 auto;">

    <div style="margin-bottom:20px; font-size:0.85rem;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--primary); font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    @if ($errors->any())
    <div style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Please fix these errors:</strong>
        <ul style="margin-top:8px; padding-left:20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <div>
                    <h3><i class="fas fa-user-edit"></i> Account Information</h3>
                    <p>Editing: <strong>{{ $user->name ?? 'User' }}</strong> (ID: #{{ $user->id }})</p>
                </div>
            </div>

            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Full Name <span style="color:var(--primary);">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem; outline:none;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Email <span style="color:var(--primary);">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem; outline:none;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem; outline:none;">
                </div>

                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:7px;">Role <span style="color:var(--primary);">*</span></label>
                    <select name="role" required
                        style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.9rem; outline:none; background:#fff;">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div style="grid-column:span 2; padding-top:16px; border-top:1px solid var(--border);">
                    <div style="background:#f8fafc; padding:14px; border-radius:9px; border:1px solid var(--border);">
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem; margin-bottom:8px;">
                            <span style="color:var(--text-soft);">Account Status:</span>
                            <strong>{{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem; margin-bottom:8px;">
                            <span style="color:var(--text-soft);">Properties:</span>
                            <strong>{{ $user->properties_count ?? 0 }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem;">
                            <span style="color:var(--text-soft);">Registered:</span>
                            <strong>{{ $user->created_at ? $user->created_at->format('d M Y, h:i A') : '-' }}</strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <a href="{{ route('admin.users.index') }}" class="btn-outline" style="padding:12px 28px;">Cancel</a>
            <button type="submit" class="btn-solid" style="padding:12px 32px;">
                <i class="fas fa-save"></i> Update User
            </button>
        </div>
    </form>
</div>

<style>
    @media (max-width: 640px) {
        form>div>div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }

        form>div>div>div[style*="grid-column:span 2"] {
            grid-column: span 1 !important;
        }
    }
</style>

@endsection