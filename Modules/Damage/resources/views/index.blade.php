@extends('layouts.app')

@section('title', __('damage.title'))

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('damage.title') }}</h1>
            <p class="text-muted mb-0">{{ __('damage.subtitle') }}</p>
        </div>
        <a href="{{ route('damage.create') }}" class="btn btn-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ __('damage.record_damage') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>{{ __('damage.filters') }}</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('damage.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">{{ __('damage.shop') }}</label>
                    <select name="shop_id" class="form-select">
                        <option value="">{{ __('damage.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ (string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('damage.date_from') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('damage.date_to') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit">{{ __('damage.apply_filters') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>{{ __('damage.reference_no') }}</th>
                    <th>{{ __('damage.damage_date') }}</th>
                    <th>{{ __('damage.shop') }}</th>
                    <th class="text-end">{{ __('damage.total_quantity') }}</th>
                    <th class="text-end">{{ __('damage.total_loss') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($damages as $damage)
                    <tr>
                        <td>{{ $damage->reference_no }}</td>
                        <td>{{ optional($damage->damage_date)->format('d M Y') }}</td>
                        <td>{{ $damage->shop?->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format((int)$damage->total_quantity) }}</td>
                        <td class="text-end">{{ number_format((float)$damage->total_loss, 2) }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('damage.show', $damage->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('damage.destroy', $damage->id) }}" method="POST" onsubmit="return confirm('{{ __('damage.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">{{ __('damage.no_records') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($damages->hasPages())
            <div class="card-footer bg-white">
                {{ $damages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
