@extends('layouts.app')

@section('title', __('branch::branch.title'))

@push('styles')
<style>
    .branch-shell {
        position: relative;
    }

    .branch-header {
        gap: 0.9rem;
    }

    .branch-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
    }

    .branch-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        margin-bottom: 0.45rem;
    }

    .branch-card {
        background: #fff;
        border: 1px solid #d8e4ee;
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .branch-count {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .branch-name {
        color: #1e293b;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="branch-shell">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap branch-header">
        <div>
            <span class="branch-kicker"><i class="bi bi-diagram-3"></i>{{ __('branch::branch.title') }}</span>
            <h1 class="branch-title fw-bold">{{ __('branch::branch.title') }}</h1>
            <p class="text-muted mb-0">{{ __('branch::branch.subtitle') }}</p>
        </div>
        <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
            <i class="bi bi-plus-circle me-1"></i>{{ __('branch::branch.add_new') }}
        </a>
    </div>

    <div class="branch-card mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('branch.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('branch::branch.shop_filter') }}</label>
                    <select id="shop_id" name="shop_id" class="form-select">
                        <option value="">{{ __('branch::branch.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ (string) $selectedShopId === (string) $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary w-100">{{ __('app.apply_filters') }}</button>
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary w-100">{{ __('app.back_to_list') }}</a>
                </div>
            </form>
        </div>
    </div>

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
                                    <a href="{{ route('branch.create', ['shop_id' => $selectedShopId]) }}" class="btn btn-sm btn-primary rounded-pill">{{ __('branch::branch.add_new') }}</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $branches->links() }}</div>
    </div>
</div>
@endsection
