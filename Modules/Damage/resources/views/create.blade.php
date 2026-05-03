@extends('layouts.app')

@section('title', __('damage.create_title'))

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('damage.create_title') }}</h1>
            <p class="text-muted mb-0">{{ __('damage.create_subtitle') }}</p>
        </div>
        <a href="{{ route('damage.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>{{ __('damage.back_to_list') }}
        </a>
    </div>

    <form method="POST" action="{{ route('damage.store') }}" id="damageForm">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><strong>{{ __('damage.summary') }}</strong></div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('damage.shop') }} *</label>
                    <select class="form-select @error('shop_id') is-invalid @enderror" id="shop_id" name="shop_id" required>
                        <option value="">{{ __('damage.select_shop') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                        @endforeach
                    </select>
                    @error('shop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('damage.damage_date') }} *</label>
                    <input type="date" class="form-control @error('damage_date') is-invalid @enderror" name="damage_date" value="{{ old('damage_date', now()->toDateString()) }}" required>
                    @error('damage_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('damage.note') }}</label>
                    <input type="text" class="form-control @error('note') is-invalid @enderror" name="note" value="{{ old('note') }}">
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>{{ __('damage.line_items') }}</strong>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addLineBtn">{{ __('damage.add_line') }}</button>
            </div>
            <div class="card-body">
                <div id="lineItems"></div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-danger">{{ __('damage.record_damage') }}</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const batchesByShopUrl = @json(route('damage.batches-by-shop'));
    const lineItemsWrap = document.getElementById('lineItems');
    const addLineBtn = document.getElementById('addLineBtn');
    const shopSelect = document.getElementById('shop_id');

    let lineIndex = 0;
    let availableBatches = [];

    function reasonOptions(selected = '') {
        const options = [
            { value: 'damaged', label: @json(__('damage.reason_damaged')) },
            { value: 'expired', label: @json(__('damage.reason_expired')) },
            { value: 'spoiled', label: @json(__('damage.reason_spoiled')) },
            { value: 'missing', label: @json(__('damage.reason_missing')) },
            { value: 'adjustment', label: @json(__('damage.reason_adjustment')) },
        ];

        return options.map((opt) => {
            const isSelected = String(opt.value) === String(selected) ? 'selected' : '';
            return `<option value="${opt.value}" ${isSelected}>${opt.label}</option>`;
        }).join('');
    }

    function batchOptions(selectedBatchId = '') {
        const first = `<option value="">${@json(__('damage.select_batch'))}</option>`;

        const rows = availableBatches.map((batch) => {
            const label = `${batch.product_name} | ${batch.batch_code} | ${batch.attribute_summary || '-'} | ${@json(__('damage.available_stock'))}: ${batch.stock_quantity}`;
            const selected = String(batch.id) === String(selectedBatchId) ? 'selected' : '';

            return `<option value="${batch.id}" data-product-id="${batch.product_id}" data-stock="${batch.stock_quantity}" data-purchase-price="${batch.purchase_price}" ${selected}>${label}</option>`;
        }).join('');

        return first + rows;
    }

    function addLine(defaults = {}) {
        const idx = lineIndex++;
        const row = document.createElement('div');
        row.className = 'row g-3 align-items-end border rounded p-3 mb-3 line-item';
        row.innerHTML = `
            <div class="col-lg-4">
                <label class="form-label">${@json(__('damage.batch'))} *</label>
                <select class="form-select batch-select" name="items[${idx}][product_batch_id]" required>
                    ${batchOptions(defaults.product_batch_id || '')}
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${@json(__('damage.quantity'))} *</label>
                <input type="number" min="1" class="form-control quantity-input" name="items[${idx}][quantity]" value="${defaults.quantity || 1}" required>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${@json(__('damage.purchase_price'))}</label>
                <input type="text" class="form-control purchase-price-input" readonly>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${@json(__('damage.reason'))}</label>
                <select class="form-select" name="items[${idx}][reason]">
                    ${reasonOptions(defaults.reason || 'damaged')}
                </select>
            </div>
            <div class="col-lg-2 text-end">
                <button type="button" class="btn btn-outline-danger remove-line-btn">${@json(__('damage.remove_line'))}</button>
            </div>
            <div class="col-12">
                <label class="form-label">${@json(__('damage.reason_note'))}</label>
                <input type="text" class="form-control" name="items[${idx}][reason_note]" value="${defaults.reason_note || ''}">
            </div>
            <input type="hidden" class="product-id-input" name="items[${idx}][product_id]" value="${defaults.product_id || ''}">
        `;

        const batchSelect = row.querySelector('.batch-select');
        const qtyInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.purchase-price-input');
        const productIdInput = row.querySelector('.product-id-input');

        function syncBatchMeta() {
            const selected = batchSelect.options[batchSelect.selectedIndex];
            const productId = selected ? selected.getAttribute('data-product-id') : '';
            const stock = selected ? parseInt(selected.getAttribute('data-stock') || '0', 10) : 0;
            const purchasePrice = selected ? parseFloat(selected.getAttribute('data-purchase-price') || '0') : 0;

            productIdInput.value = productId || '';
            qtyInput.max = stock > 0 ? String(stock) : '';
            priceInput.value = purchasePrice ? Number(purchasePrice).toFixed(2) : '';
        }

        batchSelect.addEventListener('change', syncBatchMeta);
        row.querySelector('.remove-line-btn').addEventListener('click', () => row.remove());

        lineItemsWrap.appendChild(row);
        syncBatchMeta();
    }

    async function loadBatches(shopId) {
        availableBatches = [];

        if (!shopId) {
            lineItemsWrap.innerHTML = '';
            return;
        }

        const response = await fetch(`${batchesByShopUrl}?shop_id=${encodeURIComponent(shopId)}`);
        availableBatches = await response.json();

        lineItemsWrap.querySelectorAll('.line-item').forEach((line) => line.remove());
        addLine();
    }

    shopSelect.addEventListener('change', (event) => {
        loadBatches(event.target.value).catch(() => {
            availableBatches = [];
            lineItemsWrap.innerHTML = '';
        });
    });

    addLineBtn.addEventListener('click', () => addLine());

    document.addEventListener('DOMContentLoaded', () => {
        const oldItems = @json(old('items', []));
        const selectedShop = shopSelect.value;

        if (!selectedShop) {
            addLine();
            return;
        }

        loadBatches(selectedShop)
            .then(() => {
                lineItemsWrap.innerHTML = '';

                if (Array.isArray(oldItems) && oldItems.length > 0) {
                    oldItems.forEach((item) => addLine(item));
                } else {
                    addLine();
                }
            })
            .catch(() => {
                addLine();
            });
    });
</script>
@endpush
