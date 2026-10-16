@extends('layouts.dashboard')

@section('title', 'Add Category - RKVIT REALESTATE')
@section('page-title', 'Add New Category')
@section('page-subtitle', 'Create a new property category')

@section('content')

<div style="max-width:900px; margin:0 auto;">

    {{-- Breadcrumb --}}
    <div style="margin-bottom:20px; font-size:0.85rem; color:var(--text-muted);">
        <a href="{{ route('categories.index') }}" style="color:var(--primary); font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    @if ($errors->any())
    <div class="flash-alert" style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:6px; padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns: 1fr 340px; gap:24px;">

            {{-- Main Details --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-info-circle"></i> Category Details</h3>
                        <p>Basic information about this category</p>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <div class="form-field">
                        <label for="name">Category Name <span style="color:var(--primary);">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-input @error('name') is-invalid @enderror"
                            placeholder="e.g. Apartments, Villas, Plots" required autofocus>
                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-field">
                        <label for="slug">Slug <small style="color:var(--text-muted); font-weight:400;">(leave blank to auto-generate)</small></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                            class="form-input @error('slug') is-invalid @enderror"
                            placeholder="auto-generated-from-name">
                        @error('slug')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-input @error('description') is-invalid @enderror"
                            placeholder="Short description about this category...">{{ old('description') }}</textarea>
                        @error('description')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar Settings --}}
            <div>
                <div class="panel" style="margin-bottom:16px;">
                    <div class="panel-header">
                        <div>
                            <h3><i class="fas fa-cog"></i> Settings</h3>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-field">
                            <label for="icon">Icon (Font Awesome)</label>
                            <div style="position:relative;">
                                <input type="text" id="icon" name="icon" value="{{ old('icon', 'fas fa-building') }}"
                                    class="form-input" placeholder="fas fa-building" style="padding-left:44px;">
                                <i id="iconPreview" class="{{ old('icon', 'fas fa-building') }}"
                                    style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--primary); font-size:1rem;"></i>
                            </div>
                            <small class="form-help">e.g. <code>fas fa-building</code>, <code>fas fa-home</code></small>
                        </div>

                        <div class="form-field">
                            <label for="color">Color</label>
                            <div style="display:flex; gap:10px; align-items:center;">
                                <input type="color" id="color" name="color" value="{{ old('color', '#d8232a') }}"
                                    style="width:48px; height:42px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px;">
                                <input type="text" id="colorText" value="{{ old('color', '#d8232a') }}"
                                    class="form-input" style="flex:1;" readonly>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                class="form-input" min="0">
                            <small class="form-help">Lower numbers appear first</small>
                        </div>

                        <div class="form-field">
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    style="width:18px; height:18px; accent-color:var(--primary); cursor:pointer;">
                                <span style="font-weight:600;">Active</span>
                            </label>
                            <small class="form-help">Inactive categories won't appear in listings</small>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:10px;">
                    <a href="{{ route('categories.index') }}" class="btn-outline" style="flex:1; justify-content:center; padding:12px;">
                        Cancel
                    </a>
                    <button type="submit" class="btn-solid" style="flex:2; justify-content:center; padding:12px;">
                        <i class="fas fa-save"></i> Save Category
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .form-field {
        margin-bottom: 20px;
    }

    .form-field:last-child {
        margin-bottom: 0;
    }

    .form-field label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 9px;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
        background: #fafbfc;
        font-family: inherit;
    }

    .form-input:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
    }

    .form-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .form-help {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 5px;
    }

    .form-help code {
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
    }

    .field-error {
        display: block;
        color: #ef4444;
        font-size: 0.78rem;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        form>div {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    // Auto-generate slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let slugTouched = false;

    slugInput.addEventListener('input', () => {
        slugTouched = true;
    });
    nameInput.addEventListener('input', () => {
        if (!slugTouched && nameInput.value) {
            slugInput.value = nameInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });

    // Icon preview
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    iconInput.addEventListener('input', () => {
        iconPreview.className = iconInput.value || 'fas fa-building';
    });

    // Color sync
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('colorText');
    colorPicker.addEventListener('input', () => {
        colorText.value = colorPicker.value;
    });
</script>

@endsection