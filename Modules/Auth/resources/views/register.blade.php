<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.register_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; background: #0f172a; min-height: 100vh; display: flex; flex-direction: column; }
        .register-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg,#0f172a 0%,#1e293b 60%,#1e3a5f 100%); }
        .register-card { width: 100%; max-width: 460px; background: rgba(30,41,59,.88); border: 1px solid rgba(255,255,255,.08); border-radius: 20px; padding: 2.25rem; backdrop-filter: blur(18px); box-shadow: 0 24px 60px rgba(0,0,0,.35); }
        .form-control-custom { background: rgba(15,23,42,.6); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; color: #e2e8f0; padding: .75rem 1rem .75rem 2.75rem; width: 100%; }
        .form-control-custom::placeholder { color: #475569; }
        .form-control-custom:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #475569; }
        .btn-register { width: 100%; padding: .85rem; background: linear-gradient(135deg,#2563eb,#0ea5e9); border: none; border-radius: 10px; color: #fff; font-weight: 600; }
        .btn-register:hover { filter: brightness(1.05); }
        .alert-custom { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); border-radius: 10px; padding: .75rem 1rem; color: #fca5a5; font-size: .875rem; margin-bottom: 1rem; }
        .field-error { color: #f87171; font-size: .8rem; margin-top: .35rem; }
        .brand-logo { display: flex; align-items: center; justify-content: center; gap: .6rem; text-decoration: none; margin-bottom: .8rem; }
        .logo-icon { width: 42px; height: 42px; background: linear-gradient(135deg,#2563eb,#0ea5e9); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; }
        .sub-link { color: #93c5fd; text-decoration: none; font-size: .9rem; }
        .sub-link:hover { text-decoration: underline; }
        .lang-sw { display:inline-flex; align-items:center; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12); border-radius:20px; padding:3px; gap:2px; }
        .lang-sw a { display:inline-flex; align-items:center; padding:4px 11px; border-radius:14px; font-size:.75rem; font-weight:600; text-decoration:none; color:#94a3b8; }
        .lang-sw a.on { background:#2563eb; color:#fff; }
    </style>
</head>
<body>
<div class="register-wrapper">
    <div style="position:absolute;top:1rem;right:1.5rem;z-index:10">
        <div class="lang-sw">
            <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'on' : '' }}">EN</a>
            <a href="{{ route('language.switch', 'bn') }}" class="{{ app()->getLocale() === 'bn' ? 'on' : '' }}">বাংলা</a>
        </div>
    </div>
    <div class="register-card">
        <a href="{{ route('landing.index') }}" class="brand-logo">
            <div class="logo-icon"><i class="bi bi-shop-window"></i></div>
            <span style="font-size:1.35rem;font-weight:700;color:#fff">Byabsha Track</span>
        </a>

        <h1 style="font-size:1.45rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.4rem">{{ __('auth.register_heading') }}</h1>
        <p style="font-size:.9rem;color:#64748b;text-align:center;margin-bottom:1rem">{{ __('auth.register_subtitle') }}</p>

        @if($errors->any())
            <div class="alert-custom">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="name">{{ __('auth.full_name') }}</label>
                <div style="position:relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" id="name" name="name" class="form-control-custom @error('name') is-invalid @enderror" placeholder="{{ __('auth.enter_name') }}" value="{{ old('name') }}" required>
                </div>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="email">{{ __('auth.email_address') }}</label>
                <div style="position:relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" placeholder="{{ __('auth.enter_email') }}" value="{{ old('email') }}" required>
                </div>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="password">{{ __('auth.password') }}</label>
                <div style="position:relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" placeholder="{{ __('auth.enter_password') }}" required>
                </div>
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="password_confirmation">{{ __('auth.confirm_new_password') }}</label>
                <div style="position:relative">
                    <i class="bi bi-shield-lock input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" placeholder="{{ __('auth.confirm_new_password') }}" required>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus"></i> {{ __('auth.register_as_owner') }}
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="sub-link">{{ __('auth.already_have_account') }}</a>
        </div>
    </div>
</div>
</body>
</html>
