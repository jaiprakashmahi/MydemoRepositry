@extends('layouts.admin')

@section('title', $category->name . ' - Category Details')
@section('page-title', 'Category Details')
@section('page-subtitle', 'View full category information')

@section('content')

<div style="max-width:900px; margin:0 auto;">

    <div style="margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('admin.categories.index') }}" style="color:var(--primary); font-weight:600; font-size:0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-solid">
                <i class="fas fa-edit"></i> Edit Category
            </a>
        </div>
    </div>

    {{-- Hero Card --}}
    <div class="panel" style="margin-bottom:24px; overflow:hidden;">
        <div style="background: linear-gradient(135deg, {{ $category->color }}, {{ $category->color }}dd); padding: 40px; color: white; position: relative;">
            <div style="position:absolute; top:-30px; right:-30px; width:180px; height:180px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
            <div style="position:absolute; bottom:-60px; right:40px; width:120px; height:120px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>

            <div style="display:flex; align-items:center; gap:20px; position:relative; z-index:2;">
                <div style="width:80px; height:80px; border-radius:20px; background:rgba(255,255,255,0.2); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; font-size:2.2rem; border:2px solid rgba(255,255,255,0.3);">
                    <i class="{{ $category->icon }}"></i>
                </div>
                <div>
                    <h2 style="font-size:1.8rem; font-weight:800; margin-bottom:6px; letter-spacing:-0.02em;">{{ $category->name }}</h2>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <code style="background:rgba(255,255,255,0.2); padding:4px 12px; border-radius:20px; font-size:0.8rem;">{{ $category->slug }}</code>
                        @if ($category->is_active)
                        <span style="background:rgba(16,185,129,0.3); padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                        @else
                        <span style="background:rgba(217,119,6,0.3); padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;">
                            <i class="fas fa-pause-circle"></i> Inactive
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Details Grid --}}
        <div style="padding: 28px;">
            <h3 style="font-size:1rem; font-weight:700; color:var(--text-dark); margin-bottom:18px;">
                <i class="fas fa-info-circle" style="color:var(--primary);"></i> Category Information
            </h3>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:28px;">
                <div class="info-box">
                    <span class="info-label">Category ID</span>
                    <strong class="info-value">#{{ $category->id }}</strong>
                </div>
                <div class="info-box">
                    <span class="info-label">Sort Order</span>
                    <strong class="info-value">{{ $category->sort_order }}</strong>
                </div>
                <div class="info-box">
                    <span class="info-label">Color</span>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:4px;">
                        <div style="width:22px; height:22px; border-radius:6px; background:{{ $category->color }}; border:2px solid #e2e8f0;"></div>
                        <strong class="info-value">{{ $category->color }}</strong>
                    </div>
                </div>
                <div class="info-box">
                    <span class="info-label">Icon</span>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:4px;">
                        <i class="{{ $category->icon }}" style="color:{{ $category->color }}; font-size:1rem;"></i>
                        <strong class="info-value" style="font-size:0.82rem;">{{ $category->icon }}</strong>
                    </div>
                </div>
                <div class="info-box">
                    <span class="info-label">Created</span>
                    <strong class="info-value">{{ $category->created_at->format('d M Y, h:i A') }}</strong>
                </div>
                <div class="info-box">
                    <span class="info-label">Last Updated</span>
                    <strong class="info-value">{{ $category->updated_at->diffForHumans() }}</strong>
                </div>
            </div>

            @if ($category->description)
            <div style="padding-top:20px; border-top:1px solid var(--border);">
                <span class="info-label" style="display:block; margin-bottom:8px;">Description</span>
                <p style="font-size:0.9rem; color:var(--text-soft); line-height:1.7;">{{ $category->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Action Panel --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                <p>Manage this category</p>
            </div>
        </div>
        <div style="padding:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-solid">
                <i class="fas fa-edit"></i> Edit Category
            </a>

            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn-outline">
                    @if ($category->is_active)
                    <i class="fas fa-pause-circle"></i> Deactivate
                    @else
                    <i class="fas fa-play-circle"></i> Activate
                    @endif
                </button>
            </form>

            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;"
                onsubmit="return confirm('Delete this category? This action can be undone by restoring from trash.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline" style="color:#dc2626; border-color:#fecaca;">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .info-box {
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid var(--border);
    }

    .info-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
    }
</style>

@endsection