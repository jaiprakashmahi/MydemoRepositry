@extends('layouts.admin')

@section('title', 'Edit Category - RKVIT REALESTATE')
@section('page-title', 'Edit Category')
@section('page-subtitle', 'Update category information')

@section('content')

<div style="max-width:900px; margin:0 auto;">

    <div style="margin-bottom:20px; font-size:0.85rem; color:var(--text-muted);">
        <a href="{{ route('admin.categories.index') }}" style="color:var(--primary); font-weight:600;">
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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns: 1fr 340px; gap:24px;">

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3><i class="fas fa-info-circle"></i> Category Details</h3>
                        <p>Editing: <strong>{{ $category->name }}</strong> (#{{ $category->id }})</p>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <div class="form-field">
                        <label for="name">Category Name <span style="color:var(--primary);">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                            class="form-input @error('name') is-invalid @enderror"
                            placeholder="e.g. Apartments, Villas, Plots" required>
                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-field">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                            class="form-input @error('slug') is-invalid @enderror">
                        @error('slug')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-input @error('description') is-invalid @enderror"
                            placeholder="Short description...">{{ old('description', $category->description) }}</textarea>
                        @error('description')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

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
                                <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}"
                                    class="form-input" style="padding-left:44px;">
                                <i id="iconPreview" class="{{ old('icon', $category->icon) }}"
                                    style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:{{ $category->color }}; font-size:1rem;"></i>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="color">Color</label>
                            <div style="display:flex; gap:10px; align-items:center;">
                                <input type="color" id="color" name="color" value="{{ old('color', $category->color) }}"
                                    style="width:48px; height:42px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px;">
                                <input type="text" id="colorText" value="{{ old('color', $category->color) }}"
                                    class="form-input" style="flex:1;" readonly>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order"
                                value="{{ old('sort_order', $category->sort_order) }}"
                                class="form-input" min="0">
                        </div>

                        <div class="form-field">
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                    style="width:18px; height:18px; accent-color:var(--primary); cursor:pointer;">
                                <span style="font-weight:600;">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-bottom:12px;">
                    <a href="{{ route('admin.categories.index') }}" class="btn-outline" style="flex:1; justify-content:center; padding:12px;">
                        Cancel
                    </a>
                    <button type="submit" class="btn-solid" style="flex:2; justify-content:center; padding:12px;">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                </div>

                {{-- Delete --}}
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                    onsubmit="return confirm('Delete this category permanently?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-outline"
                        style="width:100%; justify-content:center; padding:12px; color:#dc2626; border-color:#fecaca;">
                        <i class="fas fa-trash"></i> Delete Category
                    </button>
                </form>
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
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    iconInput.addEventListener('input', () => {
        iconPreview.className = iconInput.value || 'fas fa-building';
    });

    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('colorText');
    colorPicker.addEventListener('input', () => {
        colorText.value = colorPicker.value;
        iconPreview.style.color = colorPicker.value;
    });
</script>

@endsection