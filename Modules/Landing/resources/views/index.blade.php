<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.page_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; }
        .hero { background: linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#1e40af 100%); min-height: 90vh; display: flex; align-items: center; padding: 5rem 0; position: relative; overflow: hidden; }
        .hero-title { font-size: clamp(2.2rem,5vw,3.8rem); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 1.5rem; }
        .hero-title span { background: linear-gradient(135deg,#60a5fa,#a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .btn-hero-primary { background: linear-gradient(135deg,#2563eb,#7c3aed); color: #fff; padding: .85rem 2.5rem; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .3s ease; }
        .btn-hero-primary:hover { transform: translateY(-2px); color: #fff; }
        .btn-hero-outline { background: transparent; color: #cbd5e1; padding: .85rem 2.5rem; border-radius: 50px; font-weight: 600; border: 1px solid rgba(203,213,225,.3); text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .3s ease; }
        .btn-hero-outline:hover { color: #fff; border-color: rgba(203,213,225,.6); }
        .preview-card { background: rgba(30,41,59,.9); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; padding: 1.5rem; box-shadow: 0 25px 60px rgba(0,0,0,.5); }
        .preview-metric { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08); border-radius: 10px; padding: 1rem; text-align: center; }
        .feature-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; height: 100%; transition: all .3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
        .module-pill { display: inline-flex; align-items: center; gap: .6rem; background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; padding: .6rem 1.25rem; border-radius: 50px; font-size: .9rem; font-weight: 500; }
        .module-pill:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
        .cta-section { background: linear-gradient(135deg,#1e293b 0%,#1e40af 100%); padding: 6rem 0; text-align: center; }
        .footer { background: #0f172a; padding: 2rem 0; text-align: center; }
        /* Navbar */
        .site-navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; transition: all .3s ease; }
        .site-navbar .navbar-brand { font-size: 1.35rem; font-weight: 800; background: linear-gradient(135deg,#2563eb,#7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -.02em; }
        .site-navbar .nav-link { color: #475569 !important; font-size: .9rem; font-weight: 500; padding: .5rem 1rem !important; border-radius: 8px; transition: all .25s; }
        .site-navbar .nav-link:hover { color: #1e293b !important; background: rgba(15,23,42,.05); }
        .site-navbar .nav-badge { display: inline-flex; align-items: center; gap: .4rem; color: #6366f1; font-size: .78rem; font-weight: 600; background: rgba(99,102,241,.08); border: 1px solid rgba(99,102,241,.2); padding: .25rem .75rem; border-radius: 50px; }
        .site-navbar .btn-nav-cta { background: linear-gradient(135deg,#2563eb,#7c3aed); color: #fff !important; padding: .45rem 1.4rem; border-radius: 50px; font-size: .875rem; font-weight: 600; transition: all .3s; border: none; }
        .site-navbar .btn-nav-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.35); color: #fff !important; }
        .site-navbar .navbar-toggler { border-color: #e2e8f0; }
        .site-navbar .sign-in-link { color: #475569 !important; }
        /* Hero feature cards */
        .hero-feat-wrap { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1); border-radius: 24px; padding: 2.2rem 2rem; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .hero-feat-card { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 16px; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; transition: transform .35s ease, background .35s ease, border-color .35s ease; opacity: 0; animation: cardFadeIn .6s ease forwards; }
        .hero-feat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.25); }
        .hero-feat-emoji { font-size: 2rem; line-height: 1; flex-shrink: 0; filter: drop-shadow(0 2px 6px rgba(0,0,0,.35)); }
        .hero-feat-label { font-size: 1rem; font-weight: 700; color: #e2e8f0; line-height: 1.35; }
        .hero-feat-card:nth-child(1) { animation-delay: .15s; }
        .hero-feat-card:nth-child(2) { animation-delay: .3s; }
        .hero-feat-card:nth-child(3) { animation-delay: .45s; }
        @keyframes cardFadeIn { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        /* Lang switcher */
        .lang-switcher { display:inline-flex; align-items:center; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:20px; padding:3px; gap:2px; }
        .lang-btn { display:inline-flex; align-items:center; justify-content:center; padding:4px 12px; border-radius:16px; font-size:.78rem; font-weight:600; text-decoration:none; color:#64748b; transition:background .2s,color .2s,box-shadow .2s; white-space:nowrap; }
        .lang-btn:hover:not(.lang-active) { background:#e2e8f0; color:#1e293b; }
        .lang-btn.lang-active { background:#2563eb; color:#fff; box-shadow:0 1px 4px rgba(37,99,235,.3); }
        /* Login modal */
        .modal-login .modal-content { background:rgba(15,23,42,.97); border:1px solid rgba(255,255,255,.08); border-radius:20px; backdrop-filter:blur(20px); }
        .form-ctrl-dark { background:rgba(15,23,42,.6); border:1px solid rgba(255,255,255,.1); border-radius:10px; color:#e2e8f0; padding:.7rem 1rem .7rem 2.6rem; font-size:.9rem; width:100%; transition:all .2s; }
        .form-ctrl-dark::placeholder { color:#475569; }
        .form-ctrl-dark:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); color:#e2e8f0; background:rgba(15,23,42,.6); }
        .form-ctrl-dark.is-invalid { border-color:#ef4444; }
        .btn-login-modal { width:100%; padding:.8rem; background:linear-gradient(135deg,#2563eb,#7c3aed); border:none; border-radius:10px; color:#fff; font-size:.95rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.5rem; transition:all .3s; box-shadow:0 4px 15px rgba(37,99,235,.3); }
        .btn-login-modal:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(37,99,235,.45); }
        .btn-demo-modal { width:100%; padding:.7rem; background:transparent; border:1px dashed rgba(96,165,250,.45); border-radius:10px; color:#60a5fa; font-size:.82rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.5rem; transition:all .25s; }
        .btn-demo-modal:hover { background:rgba(96,165,250,.08); border-color:#60a5fa; }
        .input-icon-modal { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:#475569; font-size:.95rem; z-index:5; }
        .toggle-btn-modal { position:absolute; right:.9rem; top:50%; transform:translateY(-50%); color:#475569; cursor:pointer; background:none; border:none; padding:0; font-size:.95rem; z-index:5; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top site-navbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing.index') }}">
      <div style="width:34px;height:34px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:9px;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-graph-up-arrow" style="color:#fff;font-size:.95rem"></i>
      </div>
      Byabsha Track
    </a>
    <button class="navbar-toggler border-0 ms-auto me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="#features"><i class="bi bi-grid-3x3-gap me-1"></i>{{ __('landing.nav_features') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="#cta"><i class="bi bi-rocket me-1"></i>{{ __('landing.nav_get_started') }}</a></li>
        <li class="nav-item d-flex align-items-center ms-2">
          <span class="nav-badge"><i class="bi bi-lightning-fill"></i> {{ __('landing.v1_live') }}</span>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-2 mt-lg-0">
        <div class="lang-switcher">
          <a href="{{ route('language.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}">EN</a>
          <a href="{{ route('language.switch', 'bn') }}" class="lang-btn {{ app()->getLocale() === 'bn' ? 'lang-active' : '' }}">বাংলা</a>
        </div>
        @auth
          <a href="{{ route('dashboard.index') }}" class="btn-nav-cta btn">
            <i class="bi bi-speedometer2 me-1"></i>{{ __('landing.go_to_dashboard') }}
          </a>
        @else
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="nav-link" style="white-space:nowrap">{{ __('landing.sign_in') }}</a>
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-nav-cta btn">
            <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('landing.get_started') }}
          </a>
        @endauth
      </div>
    </div>
  </div>
</nav>
<section class="hero">
  <div class="container position-relative" style="z-index:1">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-4"
          style="background:rgba(99,102,241,.2);border:1px solid rgba(99,102,241,.4);color:#a5b4fc;font-size:.85rem">
          <i class="bi bi-lightning-fill"></i> {{ __('landing.smart_business') }}
        </div>
        <h1 class="hero-title">
          {{ __('landing.hero_title_1') }} <span>{{ __('landing.hero_title_hl') }}</span> {{ __('landing.hero_title_2') }}
        </h1>
        <p style="font-size:1.1rem;color:#94a3b8;line-height:1.7;margin-bottom:2rem;max-width:520px">
          {{ __('landing.hero_desc') }}
        </p>
        <div class="d-flex flex-wrap gap-3 mb-4">
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-hero-primary">
            <i class="bi bi-box-arrow-in-right"></i> {{ __('landing.start_tracking') }}
          </a>
          <a href="#features" class="btn-hero-outline">
            <i class="bi bi-play-circle"></i> {{ __('landing.see_features') }}
          </a>
        </div>
        <div class="d-flex gap-4 pt-3" style="border-top:1px solid rgba(255,255,255,.1)">
          <div>
            <div style="font-size:2rem;font-weight:800;color:#fff">6+</div>
            <div style="font-size:.85rem;color:#64748b">{{ __('landing.modules') }}</div>
          </div>
          <div>
            <div style="font-size:2rem;font-weight:800;color:#fff">{{ __('landing.real_time') }}</div>
            <div style="font-size:.85rem;color:#64748b">{{ __('landing.real_time_label') }}</div>
          </div>
          <div>
            <div style="font-size:2rem;font-weight:800;color:#fff">{{ __('landing.auto') }}</div>
            <div style="font-size:.85rem;color:#64748b">{{ __('landing.capital_sync') }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="hero-feat-wrap w-100" style="max-width:560px">
          <div class="d-flex flex-column gap-3">
            <div class="row g-3">
              <div class="col-6">
                <div class="hero-feat-card h-100">
                  <span class="hero-feat-emoji">📊</span>
                  <span class="hero-feat-label">{{ __('landing.feat_analytics') }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="hero-feat-card h-100">
                  <span class="hero-feat-emoji">💰</span>
                  <span class="hero-feat-label">{{ __('landing.feat_profit') }}</span>
                </div>
              </div>
            </div>
            <div class="row g-3 justify-content-center">
              <div class="col-6">
                <div class="hero-feat-card">
                  <span class="hero-feat-emoji">🔒</span>
                  <span class="hero-feat-label">{{ __('landing.feat_secure') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="features" style="padding:6rem 0;background:#f8fafc">
  <div class="container">
    <div class="text-center mb-5">
      <div class="text-primary fw-semibold text-uppercase small mb-2" style="letter-spacing:.1em">{{ __('landing.features_badge') }}</div>
      <h2 style="font-size:2.4rem;font-weight:800;color:#0f172a">{{ __('landing.features_title') }}</h2>
      <p class="text-secondary mx-auto mt-2" style="max-width:540px">{{ __('landing.features_sub') }}</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#eff6ff;color:#2563eb;font-size:1.5rem">
            <i class="bi bi-speedometer2"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat1_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat1_desc') }}</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#f0fdf4;color:#16a34a;font-size:1.5rem">
            <i class="bi bi-cart-check-fill"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat2_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat2_desc') }}</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#fef3c7;color:#d97706;font-size:1.5rem">
            <i class="bi bi-box-seam-fill"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat3_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat3_desc') }}</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#f5f3ff;color:#7c3aed;font-size:1.5rem">
            <i class="bi bi-cash-coin"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat4_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat4_desc') }}</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#fff1f2;color:#e11d48;font-size:1.5rem">
            <i class="bi bi-bar-chart-line-fill"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat5_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat5_desc') }}</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-card">
          <div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width:56px;height:56px;background:#ecfeff;color:#0891b2;font-size:1.5rem">
            <i class="bi bi-shop-window"></i>
          </div>
          <div class="fw-bold mb-2">{{ __('landing.feat6_title') }}</div>
          <p class="text-secondary small mb-0">{{ __('landing.feat6_desc') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section" id="cta">
  <div class="container">
    <h2 style="font-size:2.5rem;font-weight:800;color:#fff;margin-bottom:1rem">{{ __('landing.cta_title') }}</h2>
    <p style="font-size:1.1rem;color:#94a3b8;margin-bottom:2.5rem">{{ __('landing.cta_sub') }}</p>
    <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-hero-primary" style="display:inline-flex">
      <i class="bi bi-box-arrow-in-right"></i> {{ __('landing.cta_btn') }}
    </a>
  </div>
</section>
<footer class="footer">
  <div class="container">
    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
      <i class="bi bi-graph-up-arrow" style="color:#2563eb;font-size:1.2rem"></i>
      <span style="color:#94a3b8;font-weight:600">Byabsha Track</span>
    </div>
    <p style="color:#475569;font-size:.875rem">{{ __('landing.footer_built') }}</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@guest
<!-- Login Modal -->
<div class="modal fade modal-login" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('landing.index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
          <div style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-graph-up-arrow" style="color:#fff;font-size:1rem"></i>
          </div>
          <span style="font-size:1.2rem;font-weight:700;color:#fff">Byabsha Track</span>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <h2 style="font-size:1.35rem;font-weight:700;color:#fff;text-align:center;margin-bottom:.3rem">{{ __('auth.welcome_back') }}</h2>
      <p style="font-size:.85rem;color:#64748b;text-align:center;margin-bottom:1rem">{{ __('auth.sign_in_sub') }}</p>
      <div class="text-center mb-3">
        <span style="display:inline-flex;align-items:center;gap:.35rem;background:rgba(96,165,250,.1);border:1px solid rgba(96,165,250,.2);border-radius:50px;padding:.18rem .65rem;font-size:.72rem;color:#93c5fd;font-weight:600">
          <i class="bi bi-info-circle-fill"></i> {{ __('auth.demo_available') }}
        </span>
      </div>

      @if($errors->any())
        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:.75rem 1rem;color:#fca5a5;font-size:.875rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem">
          <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem">{{ __('auth.email_address') }}</label>
          <div style="position:relative">
            <i class="bi bi-envelope input-icon-modal"></i>
            <input type="email" name="email"
              class="form-ctrl-dark {{ $errors->has('email') ? 'is-invalid' : '' }}"
              placeholder="{{ __('auth.enter_email') }}"
              value="{{ old('email') }}"
              required autocomplete="email" autofocus>
          </div>
        </div>
        <div class="mb-3">
          <label style="color:#94a3b8;font-size:.82rem;font-weight:500;display:block;margin-bottom:.4rem">{{ __('auth.password') }}</label>
          <div style="position:relative">
            <i class="bi bi-lock input-icon-modal"></i>
            <input type="password" id="mPwd" name="password"
              class="form-ctrl-dark"
              placeholder="{{ __('auth.enter_password') }}"
              required autocomplete="current-password">
            <button type="button" class="toggle-btn-modal" onclick="toggleMPwd()">
              <i class="bi bi-eye" id="mEye"></i>
            </button>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <input type="checkbox" id="mRemember" name="remember" style="width:15px;height:15px;accent-color:#2563eb;cursor:pointer">
          <label for="mRemember" style="font-size:.82rem;color:#64748b;cursor:pointer;margin-left:.4rem">{{ __('auth.remember_me') }}</label>
        </div>
        <button type="submit" class="btn-login-modal">
          <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.sign_in') }}
        </button>
        <button type="button" class="btn-demo-modal mt-2" onclick="fillMDemo()">
          <i class="bi bi-lightning-charge-fill"></i> {{ __('auth.use_demo') }}
          <span style="font-size:.72rem;opacity:.7;font-weight:400">{{ __('auth.demo_label') }}</span>
        </button>
      </form>
    </div>
  </div>
</div>
@endguest

<script>
  function toggleMPwd() {
    var i = document.getElementById('mPwd');
    var ic = document.getElementById('mEye');
    i.type = i.type === 'password' ? 'text' : 'password';
    ic.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }
  function fillMDemo() {
    document.getElementById('mPwd').closest('form').querySelector('[name="email"]').value = 'admin@byabsha.com';
    document.getElementById('mPwd').value = 'password';
  }
  @if($errors->any())
  document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('loginModal')).show();
  });
  @endif
</script>
</body>
</html>
