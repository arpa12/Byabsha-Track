@extends('layouts.app')

@section('title', __('settings.title'))

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ __('settings.title') }}</h1>
    <p class="page-subtitle">{{ __('settings.subtitle') }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('app.success') }}!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('app.error') }}!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="content-card">
    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->get('tab', 'general') === 'general' ? 'active' : '' }}" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                <i class="bi bi-sliders"></i> {{ __('settings.general') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->get('tab') === 'business' ? 'active' : '' }}" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab">
                <i class="bi bi-briefcase"></i> {{ __('settings.business') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->get('tab') === 'system' ? 'active' : '' }}" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                <i class="bi bi-cpu"></i> {{ __('settings.system') }}
            </button>
        </li>
    </ul>

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="tab-content p-4" id="settingsTabsContent">
            {{-- General Settings --}}
            <div class="tab-pane fade {{ request()->get('tab', 'general') === 'general' ? 'show active' : '' }}" id="general" role="tabpanel">
                <h5 class="mb-3">{{ __('settings.general_settings') }}</h5>

                @foreach($settings->get('general', []) as $setting)
                    <div class="mb-3">
                        <label for="{{ $setting->key }}" class="form-label">
                            {{ __('settings.' . $setting->key) }}
                        </label>

                        @if($setting->type === 'boolean')
                            <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>{{ __('app.no') }}</option>
                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>{{ __('app.yes') }}</option>
                            </select>
                        @elseif($setting->key === 'default_language')
                            <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>English</option>
                                <option value="bn" {{ $setting->value === 'bn' ? 'selected' : '' }}>বাংলা</option>
                            </select>
                        @elseif($setting->key === 'app_timezone')
                            <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                <option value="Asia/Dhaka" {{ $setting->value === 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka</option>
                                <option value="Asia/Kolkata" {{ $setting->value === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                <option value="UTC" {{ $setting->value === 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        @else
                            <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                   class="form-control"
                                   id="{{ $setting->key }}"
                                   name="settings[{{ $setting->key }}]"
                                   value="{{ old('settings.' . $setting->key, $setting->value) }}">
                        @endif

                        @if(__('settings.' . $setting->key . '_help') !== 'settings.' . $setting->key . '_help')
                            <small class="text-muted">{{ __('settings.' . $setting->key . '_help') }}</small>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Business Settings --}}
            <div class="tab-pane fade {{ request()->get('tab') === 'business' ? 'show active' : '' }}" id="business" role="tabpanel">
                <h5 class="mb-3">{{ __('settings.business_settings') }}</h5>

                @foreach($settings->get('business', []) as $setting)
                    <div class="mb-3">
                        <label for="{{ $setting->key }}" class="form-label">
                            {{ __('settings.' . $setting->key) }}
                        </label>

                        @if($setting->key === 'business_address')
                            <textarea class="form-control"
                                      id="{{ $setting->key }}"
                                      name="settings[{{ $setting->key }}]"
                                      rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                        @else
                            <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                   class="form-control"
                                   id="{{ $setting->key }}"
                                   name="settings[{{ $setting->key }}]"
                                   value="{{ old('settings.' . $setting->key, $setting->value) }}">
                        @endif

                        @if(__('settings.' . $setting->key . '_help') !== 'settings.' . $setting->key . '_help')
                            <small class="text-muted">{{ __('settings.' . $setting->key . '_help') }}</small>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- System Settings --}}
            <div class="tab-pane fade {{ request()->get('tab') === 'system' ? 'show active' : '' }}" id="system" role="tabpanel">
                <h5 class="mb-3">{{ __('settings.system_settings') }}</h5>

                @foreach($settings->get('system', []) as $setting)
                    <div class="mb-3">
                        <label for="{{ $setting->key }}" class="form-label">
                            {{ __('settings.' . $setting->key) }}
                        </label>

                        @if($setting->type === 'boolean')
                            <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>{{ __('app.no') }}</option>
                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>{{ __('app.yes') }}</option>
                            </select>
                        @else
                            <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                   class="form-control"
                                   id="{{ $setting->key }}"
                                   name="settings[{{ $setting->key }}]"
                                   value="{{ old('settings.' . $setting->key, $setting->value) }}">
                        @endif

                        @if(__('settings.' . $setting->key . '_help') !== 'settings.' . $setting->key . '_help')
                            <small class="text-muted">{{ __('settings.' . $setting->key . '_help') }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <a href="{{ route('settings.clear-cache') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise"></i> {{ __('settings.clear_cache') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> {{ __('app.save') }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Activate tab based on URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (tabParam) {
            const tabButton = document.getElementById(tabParam + '-tab');
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
        }
    });
</script>
@endpush
@endsection
