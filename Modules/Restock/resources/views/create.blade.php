@extends('layouts.app')

@section('title', __('restock.create_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('restock.create_title') }}</h1>
    <p class="page-subtitle">{{ __('restock.create_subtitle') }}</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('restock.restock_info') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('restock.store') }}" method="POST" id="restockForm">
                    @csrf

                    <div class="mb-3">
                        <label for="shop_id" class="form-label fw-semibold">
                            {{ __('restock.shop') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('shop_id') is-invalid @enderror"
                                id="shop_id"
                                name="shop_id"
                                required>
                            <option value="">{{ __('restock.select_shop') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shop_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold">
                            {{ __('restock.product') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('product_id') is-invalid @enderror"
                                id="product_id"
                                name="product_id"
                                required>
                            <option value="">{{ __('restock.select_shop_first') }}</option>
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="product-info"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label fw-semibold">
                                {{ __('restock.quantity') }} <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   id="quantity"
                                   name="quantity"
                                   value="{{ old('quantity', 1) }}"
                                   min="1"
                                   placeholder="{{ __('restock.enter_quantity') }}"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="purchase_price_per_unit" class="form-label fw-semibold">
                                {{ __('restock.purchase_price') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number"
                                       class="form-control @error('purchase_price_per_unit') is-invalid @enderror"
                                       id="purchase_price_per_unit"
                                       name="purchase_price_per_unit"
                                       value="{{ old('purchase_price_per_unit') }}"
                                       step="0.01"
                                       min="0.01"
                                       placeholder="{{ __('restock.enter_price') }}"
                                       required>
                                @error('purchase_price_per_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="restock_date" class="form-label fw-semibold">
                            {{ __('restock.restock_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('restock_date') is-invalid @enderror"
                               id="restock_date"
                               name="restock_date"
                               value="{{ old('restock_date', date('Y-m-d')) }}"
                               required>
                        @error('restock_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label fw-semibold">{{ __('restock.note') }}</label>
                        <textarea class="form-control @error('note') is-invalid @enderror"
                                  id="note"
                                  name="note"
                                  rows="2"
                                  placeholder="{{ __('restock.note_placeholder') }}">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('restock.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('restock.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('restock.record_restock') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cost Preview Panel --}}
    <div class="col-md-4 mt-3 mt-md-0">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    {{ __('restock.cost_preview') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ __('restock.cost_hint') }}
                </div>

                <div id="preview-details" style="display:none;">
                    <div class="mb-3 p-3 rounded" style="background:#f0f9ff; border-left:3px solid #0ea5e9;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('restock.product_purchase_price') }}</div>
                        <div class="fs-5 fw-bold" id="preview-product-price">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#fff7ed; border-left:3px solid #f97316;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('restock.total_cost_label') }}</div>
                        <div class="text-warning small" id="preview-formula"></div>
                        <div class="fs-5 fw-bold mt-1" id="preview-total-cost">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #22c55e;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('restock.current_stock_label') }}</div>
                        <div class="fs-5 fw-bold" id="preview-current-stock">—</div>
                    </div>
                    <div class="p-3 rounded" style="background:#faf5ff; border-left:3px solid #a855f7;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('restock.stock_after_label') }}</div>
                        <div class="fs-5 fw-bold text-success" id="preview-stock-after">—</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shopSelect = document.getElementById('shop_id');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('purchase_price_per_unit');
    const productInfo = document.getElementById('product-info');
    const productsUrl = "{{ route('restock.products-by-shop') }}";

    let productsData = [];

    shopSelect.addEventListener('change', function() {
        const shopId = this.value;
        productSelect.innerHTML = '';

        if (!shopId) {
            productSelect.innerHTML = '<option value="">{{ __("restock.select_shop_first") }}</option>';
            productsData = [];
            productInfo.innerHTML = '';
            updatePreview();
            return;
        }

        productSelect.innerHTML = '<option value="">{{ __("app.loading") }}</option>';

        fetch(productsUrl + '?shop_id=' + shopId)
            .then(response => response.json())
            .then(products => {
                productsData = products;
                productSelect.innerHTML = '<option value="">{{ __("restock.select_product") }}</option>';
                products.forEach(function(product) {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name + ' (Stock: ' + product.stock_quantity + ')';
                    option.dataset.stock = product.stock_quantity;
                    option.dataset.purchasePrice = product.purchase_price;
                    productSelect.appendChild(option);
                });

                const oldProductId = "{{ old('product_id', '') }}";
                if (oldProductId) {
                    productSelect.value = oldProductId;
                    productSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(() => {
                productSelect.innerHTML = '<option value="">{{ __("restock.select_product") }}</option>';
            });
    });

    productSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected && selected.value) {
            const stock = selected.dataset.stock;
            const purchasePrice = parseFloat(selected.dataset.purchasePrice) || 0;
            productInfo.innerHTML = '{{ __("restock.current_stock_label") }}: <strong>' + stock + '</strong> | {{ __("restock.product_purchase_price") }}: <strong>৳' + purchasePrice.toFixed(2) + '</strong>';

            if (!priceInput.value) {
                priceInput.value = purchasePrice.toFixed(2);
            }
        } else {
            productInfo.innerHTML = '';
        }
        updatePreview();
    });

    quantityInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);

    function updatePreview() {
        const previewDetails = document.getElementById('preview-details');
        const selected = productSelect.options[productSelect.selectedIndex];
        const qty = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        if (!selected || !selected.value || qty <= 0) {
            previewDetails.style.display = 'none';
            return;
        }

        previewDetails.style.display = 'block';

        const currentStock = parseInt(selected.dataset.stock) || 0;
        const productPrice = parseFloat(selected.dataset.purchasePrice) || 0;
        const totalCost = qty * price;
        const stockAfter = currentStock + qty;

        document.getElementById('preview-product-price').textContent = '৳' + productPrice.toFixed(2);
        document.getElementById('preview-formula').textContent = qty + ' × ৳' + price.toFixed(2);
        document.getElementById('preview-total-cost').textContent = '৳' + totalCost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-current-stock').textContent = currentStock + ' {{ __("app.units") }}';
        document.getElementById('preview-stock-after').textContent = stockAfter + ' {{ __("app.units") }}';
    }

    if (shopSelect.value) {
        shopSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
