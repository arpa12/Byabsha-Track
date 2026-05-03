@extends('layouts.app')

@section('title', __('sale.warranty_create_btn'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">{{ __('sale.warranty_create_btn') }}</h2>
        <p class="text-muted mb-0">{{ __('sale.warranty_create_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.warranties.index') }}" class="btn btn-outline-secondary">{{ __('sale.back_to_list') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('sale.warranties.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">{{ __('sale.shop') }}</label>
                <select name="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
                    <option value="">{{ __('sale.select_shop') }}</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>
                @error('shop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-8">
                <label class="form-label">{{ __('sale.sale_reference') }}</label>
                <select name="sale_id" class="form-select @error('sale_id') is-invalid @enderror" required>
                    <option value="">{{ __('sale.select_sale') }}</option>
                    @foreach($sales as $sale)
                        <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                            #{{ $sale->id }} | {{ $sale->shop?->name }} | {{ $sale->product?->name }} | {{ $sale->sale_date?->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
                @error('sale_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.warranty_start') }}</label>
                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', now()->toDateString()) }}" required>
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.warranty_end') }}</label>
                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('sale.warranty_terms') }}</label>
                <input type="text" name="terms" class="form-control @error('terms') is-invalid @enderror" value="{{ old('terms') }}" placeholder="{{ __('sale.warranty_terms_placeholder') }}">
                @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('sale.warranty_create_btn') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
