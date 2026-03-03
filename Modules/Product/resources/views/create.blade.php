@extends('layouts.app')

@section('title', __('product.create_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('product.create_title') }}</h1>
    <p class="page-subtitle">{{ __('product.create_subtitle') }}</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('product.product_info_card') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('product.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="shop_id" class="form-label fw-semibold">
                            {{ __('product.shop') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('shop_id') is-invalid @enderror" 
                                id="shop_id" 
                                name="shop_id" 
                                required>
                            <option value="">{{ __('product.select_shop') }}</option>
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
                        <label for="name" class="form-label fw-semibold">
                            {{ __('product.name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="{{ __('product.enter_product_name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label fw-semibold">{{ __('product.category') }}</label>
                            <input type="text" 
                                   class="form-control @error('category') is-invalid @enderror" 
                                   id="category" 
                                   name="category" 
                                   value="{{ old('category') }}"
                                   placeholder="{{ __('product.category_placeholder') }}">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label fw-semibold">{{ __('product.brand') }}</label>
                            <input type="text" 
                                   class="form-control @error('brand') is-invalid @enderror" 
                                   id="brand" 
                                   name="brand" 
                                   value="{{ old('brand') }}"
                                   placeholder="{{ __('product.brand_placeholder') }}">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label fw-semibold">
                                {{ __('product.purchase_price') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" 
                                       class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" 
                                       name="purchase_price" 
                                       value="{{ old('purchase_price') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label fw-semibold">
                                {{ __('product.sale_price') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" 
                                       class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" 
                                       name="sale_price" 
                                       value="{{ old('sale_price') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label fw-semibold">
                            {{ __('product.initial_stock') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('stock_quantity') is-invalid @enderror" 
                               id="stock_quantity" 
                               name="stock_quantity" 
                               value="{{ old('stock_quantity', 0) }}" 
                               min="0"
                               placeholder="0"
                               required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('product.stock_hint') }}</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('product.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('product.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('product.create_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
