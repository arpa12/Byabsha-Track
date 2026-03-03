@extends('layouts.app')

@section('title', __('product.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('product.title') }}</h1>
        <p class="page-subtitle">{{ __('product.subtitle') }}</p>
    </div>
    <a href="{{ route('product.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('product.add_new') }}
    </a>
</div>

<div class="content-card">
    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('product.col_name') }}</th>
                        <th>{{ __('product.col_shop') }}</th>
                        <th>{{ __('product.col_category') }}</th>
                        <th>{{ __('product.col_brand') }}</th>
                        <th class="text-end">{{ __('product.col_purchase_price') }}</th>
                        <th class="text-end">{{ __('product.col_sale_price') }}</th>
                        <th class="text-center">{{ __('product.col_stock') }}</th>
                        <th class="text-center">{{ __('product.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $product->shop->name }}</span>
                        </td>
                        <td>{{ $product->category ?? '-' }}</td>
                        <td>{{ $product->brand ?? '-' }}</td>
                        <td class="text-end">৳{{ number_format($product->purchase_price, 2) }}</td>
                        <td class="text-end">৳{{ number_format($product->sale_price, 2) }}</td>
                        <td class="text-center">
                            @if($product->stock_quantity <= 5)
                                <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                            @elseif($product->stock_quantity <= 20)
                                <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('product.show', $product->id) }}"
                                   class="btn btn-outline-primary"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('product.edit', $product->id) }}"
                                   class="btn btn-outline-warning"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('product.destroy', $product->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('{{ __("product.confirm_delete") }}')">
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
            </table>
        </div>

        <div class="p-3">
            {{ $products->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h3>{{ __('product.no_products') }}</h3>
            <p>{{ __('product.no_products_sub') }}</p>
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> {{ __('product.add_product_btn') }}
            </a>
        </div>
    @endif
</div>
@endsection
