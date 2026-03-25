@extends('layouts.app')

@section('title', __('settings.title'))

@push('styles')
<style>
    .settings-shell {
        position: relative;
        color: var(--ink-900);
    }

    .settings-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.19), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .settings-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .settings-title {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }

    .settings-subtitle {
        color: #334155;
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .settings-card {
        background: #ffffff;
        border: 1px solid #d8e4ee;
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .settings-tabs {
        border-bottom: 1px solid #dce8f3;
        background: #f7fbff;
        padding: 0.7rem 0.9rem 0;
        gap: 0.25rem;
    }

    .settings-tabs .nav-link {
        border: 1px solid transparent;
        border-radius: 12px 12px 0 0;
        color: #4f647a;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.62rem 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
    }

    .settings-tabs .nav-link:hover {
        color: #0f172a;
        background: #edf5fc;
    }

    .settings-tabs .nav-link.active {
        color: #0f766e;
        border-color: #d3e1ee #d3e1ee #ffffff;
        background: #ffffff;
    }

    .settings-tab-content {
        padding: 1.35rem;
    }

    .settings-section-title {
        margin-bottom: 1rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1f3348;
    }

    .settings-section-meta {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 1rem;
    }

    .settings-field {
        padding: 0.92rem;
        border: 1px solid #e4edf6;
        border-radius: 14px;
        background: #fcfeff;
        margin-bottom: 0.85rem;
    }

    .settings-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .settings-input,
    .settings-select,
    .settings-textarea {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: #0f172a;
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .settings-input:focus,
    .settings-select:focus,
    .settings-textarea:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .settings-help {
        display: block;
        color: #64748b;
        margin-top: 0.45rem;
        font-size: 0.8rem;
    }

    .settings-footer {
        padding: 1rem 1.35rem 1.2rem;
        border-top: 1px solid #e1eaf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.7rem;
    }

    .btn-clear-cache {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.8);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
    }

    .btn-clear-cache:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .btn-save-settings {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.62rem 1.18rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
    }

    .btn-save-settings:hover {
        color: #fff;
    }

    @media (max-width: 768px) {
        .settings-tab-content {
            padding: 1rem;
        }

        .settings-footer {
            flex-direction: column;
            align-items: stretch;
            padding: 0.9rem 1rem 1rem;
        }

        .btn-clear-cache,
        .btn-save-settings {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-shell">
<div class="mb-4">
    <span class="settings-kicker"><i class="bi bi-sliders"></i>{{ __('settings.title') }}</span>
    <h1 class="settings-title">{{ __('settings.title') }}</h1>
    <p class="settings-subtitle">{{ __('settings.subtitle') }}</p>
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

<div class="settings-card">
    <form action="{{ route('settings.update', ['group' => $activeGroup]) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $sectionLabel = $activeGroup === 'system' ? __('settings.system_settings') : __('settings.general_settings');
            $sectionIcon = $activeGroup === 'system' ? 'bi-cpu' : 'bi-sliders';
            $displayGroups = $activeGroup === 'system' ? ['system'] : ['general', 'business'];
        @endphp

        <div class="settings-tab-content">
            <h5 class="settings-section-title"><i class="bi {{ $sectionIcon }} me-1"></i>{{ $sectionLabel }}</h5>
            <p class="settings-section-meta">{{ __('settings.subtitle') }}</p>

            @php $hasAnySetting = false; @endphp
            @foreach($displayGroups as $group)
                @php $groupSettings = $settings->get($group, collect()); @endphp
                @if($groupSettings->count() > 0)
                    @php $hasAnySetting = true; @endphp
                    @if($activeGroup === 'general' && $group === 'business')
                        <h6 class="settings-section-title mt-4"><i class="bi bi-briefcase me-1"></i>{{ __('settings.business_settings') }}</h6>
                    @endif

                    @foreach($groupSettings as $setting)
                        <div class="settings-field">
                            <label for="{{ $setting->key }}" class="form-label settings-label">
                                {{ __('settings.' . $setting->key) }}
                            </label>

                            @if($setting->type === 'boolean')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>{{ __('app.no') }}</option>
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>{{ __('app.yes') }}</option>
                                </select>
                            @elseif($setting->key === 'default_language')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="bn" {{ $setting->value === 'bn' ? 'selected' : '' }}>বাংলা</option>
                                </select>
                            @elseif($setting->key === 'app_timezone')
                                <select class="form-select settings-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                    <option value="Asia/Dhaka" {{ $setting->value === 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka</option>
                                    <option value="Asia/Kolkata" {{ $setting->value === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                    <option value="UTC" {{ $setting->value === 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            @elseif($setting->key === 'business_address')
                                <textarea class="form-control settings-textarea"
                                          id="{{ $setting->key }}"
                                          name="settings[{{ $setting->key }}]"
                                          rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @else
                                <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                       class="form-control settings-input"
                                       id="{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ old('settings.' . $setting->key, $setting->value) }}">
                            @endif

                            @if(__('settings.' . $setting->key . '_help') !== 'settings.' . $setting->key . '_help')
                                <small class="settings-help">{{ __('settings.' . $setting->key . '_help') }}</small>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endforeach

            @if(!$hasAnySetting)
                <div class="settings-field mb-0">
                    <span class="settings-help mb-0">{{ __('settings.subtitle') }}</span>
                </div>
            @endif
        </div>

        <div class="settings-footer">
            <a href="{{ route('settings.clear-cache', ['group' => $activeGroup]) }}" class="btn btn-clear-cache">
                <i class="bi bi-arrow-clockwise"></i> {{ __('settings.clear_cache') }}
            </a>
            <button type="submit" class="btn btn-save-settings">
                <i class="bi bi-check-circle"></i> {{ __('app.save') }}
            </button>
        </div>
    </form>
</div>
</div>
@endsection
