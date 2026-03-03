@extends('layouts.app')

@section('title', __('shop.edit_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('shop.edit_title') }}</h1>
    <p class="page-subtitle">{{ __('shop.edit_subtitle') }}</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-shop"></i>
                    {{ __('shop.shop_info') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('shop.update', $shop->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            {{ __('shop.name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $shop->name) }}"
                               placeholder="{{ __('shop.name_placeholder') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('shop.back_to_list') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('shop.update_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
