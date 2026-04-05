@extends('layouts.app')

@section('title', __('branch::branch.create_title'))

@push('styles')
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
    }

    .btn-branch-theme {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }

    .content-card {
        background: #fff;
        border: 1px solid var(--branch-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #e7edf4;
        background: #f7fbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .content-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #334155;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i { color: var(--branch-brand); }

    .tip-item {
        display: flex;
        gap: 0.65rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #edf3f8;
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.5;
    }

    .tip-item:last-child { border-bottom: none; padding-bottom: 0; }

    .tip-item i {
        color: var(--branch-brand);
        font-size: 1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    .form-label { color: #334155; }

    @media (max-width: 991.98px) {
        .tips-col { margin-top: 0; }
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ __('branch::branch.create_title') }}</h1>
        <p class="page-subtitle mb-0">{{ __('branch::branch.subtitle') }}</p>
    </div>
    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i>{{ __('branch::branch.back') }}
    </a>
</div>

<div class="row g-4">
    {{-- Main Form --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-diagram-3"></i>{{ __('branch::branch.create_title') }}
                </h5>
            </div>
            <div class="p-4">
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">
                                {{ __('branch::branch.shop') }} <span class="text-danger">*</span>
                            </label>
                            <select name="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
                                <option value="">{{ __('branch::branch.shop_placeholder') }}</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ old('shop_id', $selectedShopId) == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">
                                {{ __('branch::branch.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="{{ __('branch::branch.name_placeholder') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">{{ __('branch::branch.location') }}</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                class="form-control @error('location') is-invalid @enderror"
                                placeholder="{{ __('branch::branch.location_placeholder') }}">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">{{ __('branch::branch.phone') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control"
                                placeholder="{{ __('branch::branch.phone_placeholder') }}">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">{{ __('branch::branch.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="{{ __('branch::branch.email_placeholder') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ __('branch::branch.address') }}</label>
                            <textarea name="address" rows="3" class="form-control"
                                placeholder="{{ __('branch::branch.address_placeholder') }}">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    {{ __('branch::branch.active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                        <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i>{{ __('branch::branch.back') }}
                        </a>
                        <button type="submit" class="btn-branch-theme">
                            <i class="bi bi-check-circle"></i>{{ __('branch::branch.save_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tips Card --}}
    <div class="col-lg-4 tips-col">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-lightbulb"></i>Tips
                </h5>
            </div>
            <div class="p-4">
                <div class="tip-item">
                    <i class="bi bi-diagram-3"></i>
                    <span>Each branch belongs to one shop and can have its own location, phone, and email.</span>
                </div>
                <div class="tip-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Branch names must be unique within each shop.</span>
                </div>
                <div class="tip-item">
                    <i class="bi bi-telephone"></i>
                    <span>Phone and email help customers reach a specific branch directly.</span>
                </div>
                <div class="tip-item">
                    <i class="bi bi-toggle-on"></i>
                    <span>Inactive branches are hidden from sales while preserving historical data.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
