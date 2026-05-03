@extends('layouts.app')

@section('title', __('damage.show_title'))

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('damage.show_title') }}</h1>
            <p class="text-muted mb-0">{{ __('damage.show_subtitle') }}</p>
        </div>
        <a href="{{ route('damage.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>{{ __('damage.back_to_list') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body row g-3">
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.reference_no') }}</div>
                <div class="fw-semibold">{{ $damage->reference_no }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.damage_date') }}</div>
                <div class="fw-semibold">{{ optional($damage->damage_date)->format('d M Y') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.shop') }}</div>
                <div class="fw-semibold">{{ $damage->shop?->name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.items_count') }}</div>
                <div class="fw-semibold">{{ $damage->items->count() }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">{{ __('damage.note') }}</div>
                <div class="fw-semibold">{{ $damage->note ?: '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.total_quantity') }}</div>
                <div class="fw-semibold">{{ number_format((int)$damage->total_quantity) }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">{{ __('damage.total_loss') }}</div>
                <div class="fw-semibold text-danger">{{ number_format((float)$damage->total_loss, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>{{ __('damage.product') }}</th>
                    <th>{{ __('damage.batch') }}</th>
                    <th>{{ __('damage.batch_attributes') }}</th>
                    <th class="text-end">{{ __('damage.quantity') }}</th>
                    <th class="text-end">{{ __('damage.purchase_price') }}</th>
                    <th class="text-end">{{ __('damage.line_loss') }}</th>
                    <th>{{ __('damage.reason') }}</th>
                    <th>{{ __('damage.reason_note') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($damage->items as $item)
                    <tr>
                        <td>{{ $item->product?->name ?? '-' }}</td>
                        <td>{{ $item->productBatch?->batch_code ?? '-' }}</td>
                        <td>{{ $item->productBatch?->attribute_summary ?? '-' }}</td>
                        <td class="text-end">{{ number_format((int)$item->quantity) }}</td>
                        <td class="text-end">{{ number_format((float)$item->purchase_price_per_unit, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format((float)$item->total_loss, 2) }}</td>
                        <td>{{ ucfirst((string)$item->reason) }}</td>
                        <td>{{ $item->reason_note ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">{{ __('damage.no_records') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
