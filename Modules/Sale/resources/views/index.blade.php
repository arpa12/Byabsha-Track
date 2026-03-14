@extends('layouts.app')

@section('title', __('sale.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('sale.title') }}</h1>
        <p class="page-subtitle">{{ __('sale.subtitle') }}</p>
    </div>
    <a href="{{ route('sale.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('sale.new_sale') }}
    </a>
</div>

{{-- Calculation Legend --}}
<div class="alert alert-light border mb-3 py-2 px-3" style="font-size:0.8rem;">
    <span class="fw-semibold text-muted"><i class="bi bi-calculator me-1"></i>{{ __('sale.how_calculated') }} &nbsp;</span>
    <span class="text-muted">
        {{ __('sale.total_formula') }} &nbsp;|&nbsp;
        {{ __('sale.profit_formula') }} &nbsp;|&nbsp;
        {{ __('sale.margin_formula') }}
    </span>
</div>

<div class="content-card">
    @if($sales->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('sale.col_date') }}</th>
                        <th>{{ __('sale.col_shop') }}</th>
                        <th>{{ __('sale.col_product') }}</th>
                        <th class="text-center">{{ __('sale.col_quantity') }}</th>
                        <th class="text-end">{{ __('sale.col_sale_price') }}</th>
                        <th class="text-end">{{ __('sale.col_total') }}</th>
                        <th class="text-end">{{ __('sale.col_profit') }}</th>
                        <th class="text-center">{{ __('sale.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $sale->shop?->name ?? 'Deleted shop' }}</span>
                        </td>
                        <td>{{ $sale->product?->name ?? 'Deleted product' }}</td>
                        <td class="text-center">{{ $sale->quantity }}</td>
                        <td class="text-end">{{ number_format($sale->sale_price, 2) }}</td>
                        <td class="text-end"><strong>{{ number_format($sale->total_amount, 2) }}</strong></td>
                        <td class="text-end">
                            <span class="badge {{ $sale->profit > 0 ? 'bg-success' : ($sale->profit < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                {{ number_format($sale->profit, 2) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('sale.show', $sale->id) }}"
                                   class="btn btn-outline-primary"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sale.edit', $sale->id) }}"
                                   class="btn btn-outline-secondary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('sale.destroy', $sale->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('{{ __("sale.confirm_delete") }}')">
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
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-semibold">
                    @php
                        $pageTotal  = $sales->sum('total_amount');
                        $pageProfit = $sales->sum('profit');
                        $pageQty    = $sales->sum('quantity');
                    @endphp
                    <tr>
                        <td colspan="3" class="text-muted small">{{ __('sale.page_totals') }} ({{ $sales->count() }} {{ __('sale.records') }})</td>
                        <td class="text-center">{{ number_format($pageQty) }}</td>
                        <td></td>
                        <td class="text-end">{{ number_format($pageTotal, 2) }}</td>
                        <td class="text-end">
                            <span class="{{ $pageProfit > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($pageProfit, 2) }}
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-3">
            {{ $sales->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-receipt"></i>
            <h3>{{ __('sale.no_sales') }}</h3>
            <p>{{ __('sale.no_sales_sub') }}</p>
            <a href="{{ route('sale.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> {{ __('sale.new_sale') }}
            </a>
        </div>
    @endif
</div>
@endsection
