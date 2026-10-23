@extends('layouts.admin')

@section('title', 'Property Categories - RKVIT REALESTATE')
@section('page-title', 'Property Categories')
@section('page-subtitle', 'Manage all your property categories')

@section('content')

{{-- Flash Messages --}}
@if (session('success'))
<div class="flash-alert flash-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card red">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Categories</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-pause-circle"></i></div>
        </div>
        <div class="stat-value">{{ $stats['inactive'] }}</div>
        <div class="stat-label">Inactive</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-trash-alt"></i></div>
        </div>
        <div class="stat-value">{{ $stats['trashed'] }}</div>
        <div class="stat-label">Deleted</div>
    </div>
</div>

{{-- Main Panel --}}
<div class="panel">
    <div class="panel-header">
        <div>
            <h3><i class="fas fa-layer-group"></i> All Categories</h3>
            <p>Manage property categories — {{ $categories->total() }} total</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('admin.categories.create') }}" class="btn-solid">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); background: #fafbfc;">
        <form method="GET" action="{{ route('admin.categories.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:200px;">
                <i class="fas fa-search" style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.85rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name, slug or description..."
                    style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none;">
            </div>

            <select name="status" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none; background:white;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="sort" style="padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:0.85rem; outline:none; background:white;">
                <option value="sort_order" {{ request('sort') === 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Created</option>
            </select>

            <button type="submit" class="btn-outline"><i class="fas fa-filter"></i> Apply</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline"><i class="fas fa-redo"></i> Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Category</th>
                    <th>Slug</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:200px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr>
                    <td>#{{ $category->id }}</td>
                    <td>
                        <div class="property-cell">
                            <div class="category-icon-sm" style="background: {{ $category->color }}15; color: {{ $category->color }};">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <div class="property-cell-info">
                                <strong>{{ $category->name }}</strong>
                                <span>{{ Str::limit($category->description, 40) ?: 'No description' }}</span>
                            </div>
                        </div>
                    </td>
                    <td><code style="background:#f1f5f9; padding:3px 8px; border-radius:5px; font-size:0.78rem;">{{ $category->slug }}</code></td>
                    <td><strong>{{ $category->sort_order }}</strong></td>
                    <td>
                        @if ($category->is_active)
                        <span class="badge badge-success"><i class="fas fa-check"></i> Active</span>
                        @else
                        <span class="badge badge-warning"><i class="fas fa-pause"></i> Inactive</span>
                        @endif
                    </td>
                    <td>{{ $category->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.categories.show', $category) }}" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn toggle" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}"
                                    style="background:{{ $category->is_active ? '#fffbeb' : '#ecfdf5' }}; color:{{ $category->is_active ? '#d97706' : '#059669' }}; border-color:{{ $category->is_active ? '#fde68a' : '#a7f3d0' }};">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete {{ $category->name }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px 20px;">
                        <i class="fas fa-layer-group" style="font-size:3rem; color:#cbd5e1; margin-bottom:14px;"></i>
                        <h4 style="font-size:1rem; color:var(--text-dark); margin-bottom:6px;">No categories found</h4>
                        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:16px;">Get started by adding your first category.</p>
                        <a href="{{ route('admin.categories.create') }}" class="btn-solid">
                            <i class="fas fa-plus"></i> Add New Category
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($categories->hasPages())
    <div class="pagination-wrap">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<style>
    .flash-alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }

    .flash-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .flash-close {
        margin-left: auto;
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        opacity: 0.6;
    }

    .flash-close:hover {
        opacity: 1;
    }

    .category-icon-sm {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pagination-wrap {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-wrap svg {
        width: 18px;
        height: 18px;
    }
</style>

@endsection