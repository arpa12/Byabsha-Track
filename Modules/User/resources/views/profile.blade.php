@extends('layouts.app')

@section('title', __('user.profile_title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('user.profile_title') }}</h1>
    <p class="page-subtitle">{{ __('user.profile_subtitle') }}</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('app.validation_error') }}</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="content-card">
    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">{{ __('user.name') }} <span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">{{ __('user.email') }} <span class="text-danger">*</span></label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3">{{ __('user.change_password_title') }}</h5>
        <p class="text-muted mb-3">{{ __('user.change_password_subtitle') }}</p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="current_password" class="form-label">{{ __('user.current_password') }}</label>
                <input
                    type="password"
                    class="form-control @error('current_password') is-invalid @enderror"
                    id="current_password"
                    name="current_password"
                >
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="new_password" class="form-label">{{ __('user.new_password') }}</label>
                <input
                    type="password"
                    class="form-control @error('new_password') is-invalid @enderror"
                    id="new_password"
                    name="new_password"
                >
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="new_password_confirmation" class="form-label">{{ __('user.new_password_confirmation') }}</label>
                <input
                    type="password"
                    class="form-control"
                    id="new_password_confirmation"
                    name="new_password_confirmation"
                >
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>{{ __('user.save_profile') }}
            </button>
        </div>
    </form>
</div>
@endsection
