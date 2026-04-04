@extends('layouts.app')

@section('title', __('branch::branch.show_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 fw-bold mb-1">{{ $branch->name }}</h1>
        <p class="text-muted mb-0">{{ $branch->shop?->name }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> {{ __('app.edit') }}</a>
        <a href="{{ route('branch.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> {{ __('branch::branch.back') }}</a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.shop') }}</div>
                    <div class="fw-semibold">{{ $branch->shop?->name ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.location') }}</div>
                    <div>{{ $branch->location ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.phone') }}</div>
                    <div>{{ $branch->phone ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.email') }}</div>
                    <div>{{ $branch->email ?: '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.address') }}</div>
                    <div>{{ $branch->address ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('branch::branch.status') }}</div>
                    <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $branch->is_active ? __('branch::branch.active') : __('branch::branch.inactive') }}
                    </span>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('app.created_at') }}</div>
                    <div>{{ $branch->created_at?->format('F d, Y') }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">{{ __('app.updated_at') }}</div>
                    <div>{{ $branch->updated_at?->format('F d, Y') }}</div>
                </div>
            </div>
        </div>

        <form action="{{ route('branch.destroy', $branch->id) }}" method="POST" class="mt-4" onsubmit="return confirm('{{ __('branch::branch.confirm_delete') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">{{ __('app.delete') }}</button>
        </form>
    </div>
</div>
@endsection
