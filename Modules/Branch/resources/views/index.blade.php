@extends('layouts.app')

@section('title', __('branch::branch.title'))

@push('styles')
<style>
<<<<<<< HEAD
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
        --branch-ink-900: #0f172a;
        --branch-ink-700: #334155;
        --branch-ink-500: #64748b;
=======
    .btn-branch-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }

    .btn-branch-theme:hover,
    .btn-branch-theme:focus,
    .btn-branch-theme:active {
        text-decoration: none;
        outline: none;
    }

    .branch-shell {
        position: relative;
    }

    .branch-header {
        gap: 0.9rem;
>>>>>>> d42f583 (initial commit)
    }

    .branch-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--branch-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .branch-page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .btn-add-branch {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-add-branch:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--branch-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #e7edf4;
        background: #f7fbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .content-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--branch-ink-700);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i { color: var(--branch-brand); }

    /* Table */
    .table-custom { margin-bottom: 0; }

    .table-custom thead th {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover { background: #fbfdff; }

    .branch-name-cell {
        color: var(--branch-ink-900);
        font-weight: 700;
    }

    .branch-date-cell {
        color: var(--branch-ink-500);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.26);
    }

    .status-inactive {
        background: rgba(100, 116, 139, 0.14);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.26);
    }

    .action-btn {
        border-radius: 10px !important;
        padding: 0.32rem 0.5rem;
        border-width: 1px;
        font-size: 0.8rem;
    }

    .btn-outline-info {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }
    .btn-outline-info:hover { background: #0f766e; border-color: #0f766e; color: #fff; }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }
    .btn-outline-warning:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }
    .btn-outline-danger:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

    /* Mobile card view */
    .branch-mobile-cards { display: none; }

    .branch-mobile-card {
        background: #fff;
        border: 1px solid var(--branch-line);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .branch-mobile-card:last-child { margin-bottom: 0; }

    .branch-mobile-card .bmc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.65rem;
    }

    .branch-mobile-card .bmc-meta {
        font-size: 0.8rem;
        color: var(--branch-ink-500);
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .branch-mobile-card .bmc-actions {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.8rem;
        padding-top: 0.75rem;
        border-top: 1px solid #edf3f8;
    }

    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-state i { font-size: 2.5rem; color: #8aa0b6; display: block; margin-bottom: 0.65rem; }

    .btn-create-first {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }
    .btn-create-first:hover { color: #fff; }

    @media (max-width: 767.98px) {
        .branch-header-row { flex-direction: column; align-items: stretch !important; }
        .btn-add-branch { width: 100%; justify-content: center; }
        .table-desktop { display: none; }
        .branch-mobile-cards { display: block; }
    }
</style>
@endpush

@section('content')
<<<<<<< HEAD
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3 branch-header-row">
    <div>
        <span class="branch-kicker"><i class="bi bi-diagram-3"></i>{{ __('branch::branch.title') }}</span>
        <h1 class="branch-page-title fw-bold">{{ __('branch::branch.title') }}</h1>
        <p class="text-muted mb-0">{{ __('branch::branch.subtitle') }}</p>
=======
<div class="branch-shell">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap branch-header">
        <div>
            <span class="branch-kicker"><i class="bi bi-diagram-3"></i>{{ __('branch::branch.title') }}</span>
            <h1 class="branch-title fw-bold">{{ __('branch::branch.title') }}</h1>
            <p class="text-muted mb-0">{{ __('branch::branch.subtitle') }}</p>
        </div>
        <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-branch-theme">
            <i class="bi bi-plus-circle me-1"></i>{{ __('branch::branch.add_new') }}
        </a>
>>>>>>> d42f583 (initial commit)
    </div>
    <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-add-branch">
        <i class="bi bi-plus-circle"></i>{{ __('branch::branch.add_new') }}
    </a>
</div>

{{-- Filter Card --}}
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title"><i class="bi bi-funnel"></i>{{ __('branch::branch.shop_filter') }}</h5>
    </div>
<<<<<<< HEAD
    <div class="p-3 p-md-4">
        <form action="{{ route('branch.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-sm-8 col-md-9">
                <select id="shop_id" name="shop_id" class="form-select">
                    <option value="">{{ __('branch::branch.all_shops') }}</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ (string) $selectedShopId === (string) $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary fw-semibold flex-fill">
                    <i class="bi bi-search me-1 d-none d-sm-inline"></i>{{ __('app.apply_filters') }}
                </button>
                @if($selectedShopId)
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary" title="{{ __('app.back_to_list') }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
=======

    <div class="branch-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('branch::branch.name') }}</th>
                        <th>{{ __('branch::branch.shop') }}</th>
                        <th>{{ __('branch::branch.location') }}</th>
                        <th>{{ __('branch::branch.status') }}</th>
                        <th>{{ __('app.created_at') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td><span class="branch-name">{{ $branch->name }}</span></td>
                            <td>{{ $branch->shop?->name ?? '-' }}</td>
                            <td>{{ $branch->location ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $branch->is_active ? __('branch::branch.active') : __('branch::branch.inactive') }}
                                </span>
                            </td>
                            <td>{{ $branch->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('branch.show', $branch->id) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('branch.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('branch::branch.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>
                                    <div class="fw-semibold mb-1">{{ __('branch::branch.no_branches') }}</div>
                                    <div class="mb-3">{{ __('branch::branch.no_branches_sub') }}</div>
                                    <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-branch-theme">{{ __('branch::branch.add_new') }}</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $branches->links() }}</div>
>>>>>>> d42f583 (initial commit)
    </div>
</div>

{{-- Desktop Table --}}
<div class="content-card table-desktop">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>{{ __('branch::branch.name') }}</th>
                    <th>{{ __('branch::branch.shop') }}</th>
                    <th>{{ __('branch::branch.location') }}</th>
                    <th>{{ __('branch::branch.phone') }}</th>
                    <th>{{ __('branch::branch.status') }}</th>
                    <th>{{ __('app.created_at') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                    <tr>
                        <td><strong class="branch-name-cell">{{ $branch->name }}</strong></td>
                        <td class="text-muted">{{ $branch->shop?->name ?? '-' }}</td>
                        <td class="text-muted">{{ $branch->location ?: '-' }}</td>
                        <td class="text-muted">{{ $branch->phone ?: '-' }}</td>
                        <td>
                            @if($branch->is_active)
                                <span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i>{{ __('branch::branch.active') }}</span>
                            @else
                                <span class="status-badge status-inactive"><i class="bi bi-dash-circle"></i>{{ __('branch::branch.inactive') }}</span>
                            @endif
                        </td>
                        <td class="branch-date-cell">{{ $branch->created_at?->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('branch.show', $branch->id) }}" class="btn btn-outline-info action-btn" title="{{ __('app.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-outline-warning action-btn" title="{{ __('app.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('branch.destroy', $branch->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('branch::branch.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger action-btn" title="{{ __('app.delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-diagram-3"></i>
                                <strong class="d-block mb-1">{{ __('branch::branch.no_branches') }}</strong>
                                <p class="text-muted mb-3 small">{{ __('branch::branch.no_branches_sub') }}</p>
                                <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-create-first">
                                    <i class="bi bi-plus-circle"></i>{{ __('branch::branch.add_new') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($branches->hasPages())
        <div class="p-3 border-top">{{ $branches->links() }}</div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="branch-mobile-cards">
    @forelse($branches as $branch)
        <div class="branch-mobile-card">
            <div class="bmc-header">
                <div>
                    <strong class="branch-name-cell d-block">{{ $branch->name }}</strong>
                    <span class="text-muted small">{{ $branch->shop?->name ?? '-' }}</span>
                </div>
                @if($branch->is_active)
                    <span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i>{{ __('branch::branch.active') }}</span>
                @else
                    <span class="status-badge status-inactive"><i class="bi bi-dash-circle"></i>{{ __('branch::branch.inactive') }}</span>
                @endif
            </div>
            @if($branch->location)
                <div class="bmc-meta"><i class="bi bi-geo-alt"></i>{{ $branch->location }}</div>
            @endif
            @if($branch->phone)
                <div class="bmc-meta"><i class="bi bi-telephone"></i>{{ $branch->phone }}</div>
            @endif
            <div class="bmc-meta"><i class="bi bi-calendar3"></i>{{ $branch->created_at?->format('M d, Y') }}</div>
            <div class="bmc-actions">
                <a href="{{ route('branch.show', $branch->id) }}" class="btn btn-sm btn-outline-info flex-fill text-center">
                    <i class="bi bi-eye me-1"></i>{{ __('app.view') }}
                </a>
                <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-sm btn-outline-warning flex-fill text-center">
                    <i class="bi bi-pencil me-1"></i>{{ __('app.edit') }}
                </a>
                <form action="{{ route('branch.destroy', $branch->id) }}" method="POST"
                    onsubmit="return confirm('{{ __('branch::branch.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="content-card p-4">
            <div class="empty-state">
                <i class="bi bi-diagram-3"></i>
                <strong class="d-block mb-1">{{ __('branch::branch.no_branches') }}</strong>
                <p class="text-muted mb-3 small">{{ __('branch::branch.no_branches_sub') }}</p>
                <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn-create-first">
                    <i class="bi bi-plus-circle"></i>{{ __('branch::branch.add_new') }}
                </a>
            </div>
        </div>
    @endforelse
    @if($branches->hasPages())
        <div class="mt-3">{{ $branches->links() }}</div>
    @endif
</div>
@endsection
