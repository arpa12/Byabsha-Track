@extends('layouts.app')

@section('title', __('sale.exchange_create_btn'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">{{ __('sale.exchange_create_btn') }}</h2>
        <p class="text-muted mb-0">{{ __('sale.exchange_create_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.exchanges.index') }}" class="btn btn-outline-secondary">{{ __('sale.back_to_list') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('sale.exchanges.store') }}" class="row g-3">
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
                            #{{ $sale->id }} | {{ $sale->shop?->name }} | {{ $sale->product?->name }} | {{ $sale->sale_date?->format('d M Y') }} | {{ __('sale.quantity') }}: {{ $sale->quantity }}
                        </option>
                    @endforeach
                </select>
                @error('sale_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.exchange_type') }}</label>
                <select name="exchange_type" class="form-select @error('exchange_type') is-invalid @enderror" required>
                    <option value="replacement" {{ old('exchange_type', 'replacement') === 'replacement' ? 'selected' : '' }}>{{ __('sale.exchange_type_replacement') }}</option>
                    <option value="return_only" {{ old('exchange_type') === 'return_only' ? 'selected' : '' }}>{{ __('sale.exchange_type_return_only') }}</option>
                </select>
                @error('exchange_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.quantity') }}</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" class="form-control @error('quantity') is-invalid @enderror" required>
                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.exchange_date') }}</label>
                <input type="date" name="exchange_date" value="{{ old('exchange_date', now()->toDateString()) }}" class="form-control @error('exchange_date') is-invalid @enderror" required>
                @error('exchange_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('sale.reason') }}</label>
                <input type="text" name="reason" value="{{ old('reason', 'defective') }}" class="form-control @error('reason') is-invalid @enderror" required>
                @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('sale.replacement_batch_optional') }}</label>
                <select name="replacement_batch_id" class="form-select @error('replacement_batch_id') is-invalid @enderror">
                    <option value="">{{ __('sale.no_replacement_batch') }}</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ old('replacement_batch_id') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->shop?->name ?? '' }} | {{ $batch->product?->name ?? '-' }} | {{ $batch->batch_code }} | {{ __('sale.available_stock') }}: {{ $batch->remaining_quantity }} | {{ __('sale.col_sale_price') }}: {{ number_format((float)$batch->purchase_price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('replacement_batch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('sale.note') }}</label>
                <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('sale.exchange_create_btn') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
