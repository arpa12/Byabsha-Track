@extends('layouts.app')

@section('title', __('sale.warranty_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">{{ __('sale.warranty_title') }}</h2>
        <p class="text-muted mb-0">{{ __('sale.warranty_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.warranties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('sale.warranty_create_btn') }}
    </a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="shop_id" class="form-select">
            <option value="">{{ __('sale.all_shops') }}</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" {{ (string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">{{ __('sale.all_statuses') }}</option>
            @foreach(['active', 'claimed', 'expired', 'void'] as $status)
                <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ __('sale.status_' . $status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary" type="submit">{{ __('sale.apply_filters') }}</button>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th>{{ __('sale.warranty_code') }}</th>
                    <th>{{ __('sale.shop') }}</th>
                    <th>{{ __('sale.product') }}</th>
                    <th>{{ __('sale.customer_name') }}</th>
                    <th>{{ __('sale.warranty_period') }}</th>
                    <th>{{ __('sale.warranty_status') }}</th>
                    <th class="text-end">{{ __('sale.col_actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($warranties as $warranty)
                    @php
                        $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
                    @endphp
                    <tr>
                        <td><strong>{{ $warranty->warranty_code }}</strong></td>
                        <td>{{ $warranty->shop?->name ?? '-' }}</td>
                        <td>{{ $warranty->sale?->product?->name ?? '-' }}</td>
                        <td>{{ $warranty->sale?->customer_name ?? '-' }}</td>
                        <td>{{ $warranty->start_date->format('d M Y') }} - {{ $warranty->end_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $effectiveStatus === 'active' ? 'success' : ($effectiveStatus === 'claimed' ? 'info' : ($effectiveStatus === 'expired' ? 'warning text-dark' : 'secondary')) }}">
                                {{ __('sale.status_' . $effectiveStatus) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($warranty->status === 'active')
                                <form method="POST" action="{{ route('sale.warranties.claim', $warranty->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">{{ __('sale.warranty_mark_claimed') }}</button>
                                </form>
                                <form method="POST" action="{{ route('sale.warranties.void', $warranty->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('sale.warranty_void') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('sale.no_records') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $warranties->links() }}
</div>
@endsection
