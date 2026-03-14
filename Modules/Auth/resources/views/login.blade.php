<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.page_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; background: #0f172a; min-height: 100vh; display: flex; flex-direction: column; }
        .login-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg,#0f172a 0%,#1e293b 60%,#1e3a5f 100%); position: relative; overflow: hidden; }
        .login-wrapper::before { content: ''; position: absolute; width: 500px; height: 500px; background: radial-gradient(circle, rgba(37,99,235,.12) 0%, transparent 70%); top: -150px; right: -150px; border-radius: 50%; pointer-events: none; }
        .login-card { width: 100%; max-width: 440px; background: rgba(30,41,59,.85); border: 1px solid rgba(255,255,255,.08); border-radius: 20px; padding: 2.5rem; backdrop-filter: blur(20px); box-shadow: 0 25px 60px rgba(0,0,0,.4); position: relative; z-index: 1; }
        .brand-logo { display: flex; align-items: center; justify-content: center; gap: .6rem; text-decoration: none; }
        .logo-icon { width: 44px; height: 44px; background: linear-gradient(135deg,#2563eb,#7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; }
        .form-control-custom { background: rgba(15,23,42,.6); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; color: #e2e8f0; padding: .75rem 1rem .75rem 2.75rem; font-size: .95rem; width: 100%; transition: all .2s ease; }
        .form-control-custom::placeholder { color: #475569; }
        .form-control-custom:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); color: #e2e8f0; }
        .form-control-custom.is-invalid { border-color: #ef4444; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #475569; font-size: 1rem; z-index: 5; }
        .toggle-btn { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #475569; cursor: pointer; background: none; border: none; padding: 0; font-size: 1rem; z-index: 5; }
        .btn-login { width: 100%; padding: .85rem; background: linear-gradient(135deg,#2563eb,#7c3aed); border: none; border-radius: 10px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem; transition: all .3s ease; box-shadow: 0 4px 15px rgba(37,99,235,.3); }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(37,99,235,.45); }
        .alert-custom { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); border-radius: 10px; padding: .75rem 1rem; color: #fca5a5; font-size: .875rem; display: flex; align-items: center; gap: .5rem; margin-bottom: 1.25rem; }
        .field-error { color: #f87171; font-size: .8rem; margin-top: .35rem; display: flex; align-items: center; gap: .3rem; }
        .divider { text-align: center; color: #334155; font-size: .8rem; margin: 1.5rem 0; position: relative; }
        .divider::before { content: ''; position: absolute; left: 0; top: 50%; width: 100%; height: 1px; background: rgba(255,255,255,.07); }
        .divider span { background: rgba(30,41,59,.85); padding: 0 .75rem; position: relative; }
        .back-link { display: flex; align-items: center; justify-content: center; gap: .4rem; color: #64748b; font-size: .875rem; text-decoration: none; transition: color .2s; }
        .back-link:hover { color: #94a3b8; }
        .btn-demo { width: 100%; padding: .75rem; background: transparent; border: 1px dashed rgba(96,165,250,.45); border-radius: 10px; color: #60a5fa; font-size: .875rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem; transition: all .25s ease; margin-bottom: 1rem; }
        .btn-demo:hover { background: rgba(96,165,250,.08); border-color: #60a5fa; }
        .demo-badge { display: inline-flex; align-items: center; gap: .35rem; background: rgba(96,165,250,.1); border: 1px solid rgba(96,165,250,.2); border-radius: 50px; padding: .18rem .65rem; font-size: .72rem; color: #93c5fd; font-weight: 600; margin-bottom: 1.5rem; }
        .lang-sw { display:inline-flex; align-items:center; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12); border-radius:20px; padding:3px; gap:2px; }
        .lang-sw a { display:inline-flex; align-items:center; padding:4px 11px; border-radius:14px; font-size:.75rem; font-weight:600; text-decoration:none; color:#94a3b8; transition:background .2s,color .2s; }
        .lang-sw a:hover { color:#fff; }
        .lang-sw a.on { background:#2563eb; color:#fff; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div style="position:absolute;top:1rem;right:1.5rem;z-index:10">
            <div class="lang-sw">
                <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'on' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'bn') }}" class="{{ app()->getLocale() === 'bn' ? 'on' : '' }}">বাংলা</a>
            </div>
        </div>
        <div class="login-card">
            <a href="{{ route('landing.index') }}" class="brand-logo mb-3">
                <div class="logo-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <span style="font-size:1.4rem;font-weight:700;color:#fff">Byabsha Track</span>
            </a>
            <h1 style="font-size:1.5rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.4rem">{{ __('auth.welcome_back') }}</h1>
            <p style="font-size:.9rem;color:#64748b;text-align:center;margin-bottom:1rem">{{ __('auth.sign_in_sub') }}</p>
            @if($errors->any())
                <div class="alert-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('status'))
                <div class="alert alert-success mb-3" style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.35);color:#86efac;">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="email">{{ __('auth.email_address') }}</label>
                    <div style="position:relative">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" id="email" name="email"
                            class="form-control-custom @error('email') is-invalid @enderror"
                            placeholder="{{ __('auth.enter_email') }}"
                            value="{{ old('email') }}"
                            required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label style="color:#94a3b8;font-size:.875rem;font-weight:500;display:block;margin-bottom:.5rem" for="password">{{ __('auth.password') }}</label>
                    <div style="position:relative">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" id="password" name="password"
                            class="form-control-custom @error('password') is-invalid @enderror"
                            placeholder="{{ __('auth.enter_password') }}"
                            required autocomplete="current-password">
                        <button type="button" class="toggle-btn" onclick="togglePwd()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex align-items-center mb-4">
                    <input type="checkbox" id="remember" name="remember" style="width:16px;height:16px;accent-color:#2563eb;cursor:pointer">
                    <label for="remember" style="font-size:.875rem;color:#64748b;cursor:pointer;margin-left:.4rem">{{ __('auth.remember_me') }}</label>
                    <a href="{{ route('password.request') }}" style="margin-left:auto;font-size:.82rem;color:#93c5fd;text-decoration:none;">{{ __('auth.forgot_password_link') }}</a>
                </div>
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.sign_in') }}
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="back-link" style="justify-content:center;color:#93c5fd;">
                    <i class="bi bi-person-plus"></i> {{ __('auth.create_owner_account') }}
                </a>
            </div>
            <div class="divider"><span>{{ __('auth.or') }}</span></div>
            <a href="{{ route('landing.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> {{ __('auth.back_to_home') }}
            </a>
        </div>
    </div>
    <div class="footer-note">&copy; {{ date('Y') }} Byabsha Track &bull; {{ __('auth.copyright') }}</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePwd() {
            var i = document.getElementById('password');
            var ic = document.getElementById('eyeIcon');
            i.type = i.type === 'password' ? 'text' : 'password';
            ic.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        }
    </script>
</body>
</html>
