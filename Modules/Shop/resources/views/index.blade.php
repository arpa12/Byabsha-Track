@extends('layouts.app')

@section('title', __('shop.title'))

@push('styles')
<style>
    .actions-cell {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ __('shop.title') }}</h1>
        <p class="page-subtitle">{{ __('shop.subtitle') }}</p>
    </div>
    <a href="{{ route('shop.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('shop.add_new') }}
    </a>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>{{ __('shop.col_name') }}</th>
                    <th>{{ __('shop.col_products') }}</th>
                    <th>{{ __('shop.col_sales') }}</th>
                    <th>{{ __('shop.col_created') }}</th>
                    <th>{{ __('shop.col_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $shop)
                <tr>
                    <td>
                        <strong style="color: #1e293b;">{{ $shop->name }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $shop->products_count }} {{ __('shop.products_badge') }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $shop->sales_count }} {{ __('shop.sales_badge') }}</span>
                    </td>
                    <td style="color: #64748b;">
                        {{ $shop->created_at->format('M d, Y') }}
                    </td>
                    <td class="actions-cell">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('shop.show', $shop->id) }}"
                               class="btn btn-outline-info"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('shop.edit', $shop->id) }}"
                               class="btn btn-outline-warning"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('shop.destroy', $shop->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __("shop.confirm_delete") }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-outline-danger"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-shop"></i>
                            <p class="mb-2">{{ __('shop.no_shops') }}</p>
                            <a href="{{ route('shop.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle"></i> {{ __('shop.create_first') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
