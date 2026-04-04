@extends('layouts.app')

@section('title', __('branch::branch.edit_title'))

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">{{ __('branch::branch.edit_title') }}</h1>
            <p class="text-muted mb-0">{{ __('branch::branch.subtitle') }}</p>
        </div>

        <form action="{{ route('branch.update', $branch->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">{{ __('branch::branch.shop') }}</label>
                <select name="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
                    <option value="">{{ __('branch::branch.shop_placeholder') }}</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id', $branch->shop_id) == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
                @error('shop_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('branch::branch.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('branch::branch.name_placeholder') }}" required>
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('branch::branch.location') }}</label>
                <input type="text" name="location" value="{{ old('location', $branch->location) }}" class="form-control" placeholder="{{ __('branch::branch.location_placeholder') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('branch::branch.phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" class="form-control" placeholder="{{ __('branch::branch.phone_placeholder') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('branch::branch.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $branch->email) }}" class="form-control" placeholder="{{ __('branch::branch.email_placeholder') }}">
            </div>
            <div class="col-12">
                <label class="form-label">{{ __('branch::branch.address') }}</label>
                <textarea name="address" rows="4" class="form-control" placeholder="{{ __('branch::branch.address_placeholder') }}">{{ old('address', $branch->address) }}</textarea>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">{{ __('branch::branch.active') }}</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary">{{ __('branch::branch.update_btn') }}</button>
                <a href="{{ route('branch.show', $branch->id) }}" class="btn btn-secondary">{{ __('branch::branch.back') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
