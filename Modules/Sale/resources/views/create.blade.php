@extends('layouts.app')

@section('title', __('sale.create_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('sale.create_title') }}</h1>
    <p class="page-subtitle">{{ __('sale.create_subtitle') }}</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-receipt"></i>
                    {{ __('sale.sale_information') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('sale.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="shop_id" class="form-label fw-semibold">
                            {{ __('sale.shop') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('shop_id') is-invalid @enderror"
                                id="shop_id"
                                name="shop_id"
                                required>
                            <option value="">{{ __('sale.select_shop') }}</option>
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
                            {{ __('sale.product') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('product_id') is-invalid @enderror"
                                id="product_id"
                                name="product_id"
                                data-selected-product-id="{{ old('product_id', '') }}"
                                required>
                            <option value="">{{ __('sale.select_product') }}</option>
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="stock-info"></div>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">
                            {{ __('sale.quantity') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control @error('quantity') is-invalid @enderror"
                               id="quantity"
                               name="quantity"
                               value="{{ old('quantity', 1) }}"
                               min="1"
                               placeholder="{{ __('sale.enter_quantity') }}"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sale_date" class="form-label fw-semibold">
                            {{ __('sale.sale_date_label') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('sale_date') is-invalid @enderror"
                               id="sale_date"
                               name="sale_date"
                               value="{{ old('sale_date', date('Y-m-d')) }}"
                               required>
                        @error('sale_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('sale.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('sale.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('sale.create_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Calculation Preview Panel --}}
    <div class="col-md-4 mt-3 mt-md-0">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    {{ __('sale.calc_preview') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ __('sale.calc_hint') }}
                </div>

                <div id="calc-details" style="display:none;">
                    <div class="mb-3 p-3 rounded" style="background:#f0f9ff; border-left:3px solid #0ea5e9;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('sale.total_amount_label') }}</div>
                        <div class="text-primary small" id="formula-total"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-total">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#fff7ed; border-left:3px solid #f97316;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('sale.purchase_cost_label') }}</div>
                        <div class="text-warning small" id="formula-cost"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-cost">—</div>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #22c55e;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('sale.profit_label') }}</div>
                        <div class="text-success small" id="formula-profit"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-profit">—</div>
                    </div>
                    <div class="p-3 rounded" style="background:#faf5ff; border-left:3px solid #a855f7;">
                        <div class="text-muted small fw-semibold mb-1">{{ __('sale.margin_label') }}</div>
                        <div class="small" id="formula-margin" style="color:#7c3aed;"></div>
                        <div class="fs-5 fw-bold mt-1" id="value-margin">—</div>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded" style="background:#f8fafc; border:1px dashed #cbd5e1; font-size:0.8rem;">
                    <div class="fw-semibold text-muted mb-2"><i class="bi bi-lightbulb me-1"></i>{{ __('sale.formulas_used') }}</div>
                    <div class="text-muted">{{ __('sale.formula_total') }}</div>
                    <div class="text-muted">{{ __('sale.formula_cost') }}</div>
                    <div class="text-muted">{{ __('sale.formula_profit') }}</div>
                    <div class="text-muted">{{ __('sale.formula_margin') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const productsByShopEndpoint = "{{ route('sale.products-by-shop') }}";

async function loadProductsByShop(selectedShopId) {
    const productSelect = document.getElementById('product_id');
    const selectedProductId = productSelect.dataset.selectedProductId || '';

    productSelect.innerHTML = `<option value="">{{ __('sale.select_product') }}</option>`;

    if (!selectedShopId) {
        updateCalcPreview();
        return;
    }

    try {
        const response = await fetch(`${productsByShopEndpoint}?shop_id=${selectedShopId}`);
        const products = await response.json();

        products.forEach((product) => {
            const option = document.createElement('option');
            option.value = product.id;
            option.setAttribute('data-stock', product.stock_quantity);
            option.setAttribute('data-price', product.sale_price);
            option.setAttribute('data-purchase-price', product.purchase_price);
            option.textContent = `${product.name} - Stock: ${product.stock_quantity} - Price: ${Number(product.sale_price).toFixed(2)}`;

            if (String(selectedProductId) === String(product.id)) {
                option.selected = true;
            }

            productSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Failed to load products by shop:', error);
    }

    updateCalcPreview();
}

function filterProductsByShop() {
    const shopSelect = document.getElementById('shop_id');
    const selectedShopId = shopSelect.value;

    loadProductsByShop(selectedShopId);
}

function updateCalcPreview() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const stockInfo = document.getElementById('stock-info');

    const salePrice     = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const purchasePrice = parseFloat(selectedOption.getAttribute('data-purchase-price')) || 0;
    const stock         = selectedOption.getAttribute('data-stock');
    const qty           = parseInt(quantityInput.value) || 0;

    if (stock) {
        stockInfo.innerHTML = `Available Stock: <strong>${stock}</strong> units | Sale Price: <strong>${salePrice.toFixed(2)}</strong>`;
        quantityInput.max = stock;
    } else {
        stockInfo.innerHTML = '';
    }

    const calcDetails = document.getElementById('calc-details');
    if (salePrice <= 0 || qty <= 0) { calcDetails.style.display = 'none'; return; }
    calcDetails.style.display = 'block';

    const totalAmount = qty * salePrice;
    const totalCost   = qty * purchasePrice;
    const profit      = (salePrice - purchasePrice) * qty;
    const margin      = totalAmount > 0 ? (profit / totalAmount) * 100 : 0;

    document.getElementById('formula-total').textContent  = `${qty} × ${salePrice.toFixed(2)}`;
    document.getElementById('value-total').textContent    = totalAmount.toFixed(2);

    document.getElementById('formula-cost').textContent   = `${qty} × ${purchasePrice.toFixed(2)}`;
    document.getElementById('value-cost').textContent     = totalCost.toFixed(2);

    document.getElementById('formula-profit').textContent = `(${salePrice.toFixed(2)} − ${purchasePrice.toFixed(2)}) × ${qty}`;
    const profitEl = document.getElementById('value-profit');
    profitEl.textContent = profit.toFixed(2);
    profitEl.className   = 'fs-5 fw-bold mt-1 ' + (profit > 0 ? 'text-success' : (profit < 0 ? 'text-danger' : 'text-secondary'));

    document.getElementById('formula-margin').textContent = `(${profit.toFixed(2)} ÷ ${totalAmount.toFixed(2)}) × 100`;
    document.getElementById('value-margin').textContent   = margin.toFixed(2) + '%';
}

document.getElementById('shop_id').addEventListener('change', filterProductsByShop);
document.getElementById('product_id').addEventListener('change', updateCalcPreview);
document.getElementById('quantity').addEventListener('input', updateCalcPreview);
document.addEventListener('DOMContentLoaded', filterProductsByShop);
</script>
@endsection
