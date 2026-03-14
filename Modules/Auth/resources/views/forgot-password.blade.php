<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.forgot_password_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(30, 41, 59, .9);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 16px;
            padding: 2rem;
            color: #e2e8f0;
        }
        .form-control {
            background: rgba(15, 23, 42, .6);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #e2e8f0;
        }
        .form-control:focus {
            background: rgba(15, 23, 42, .8);
            color: #e2e8f0;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .2);
        }
        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }
        .text-muted-custom {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <h1 class="h4 mb-2">{{ __('auth.forgot_password_heading') }}</h1>
        <p class="text-muted-custom mb-4">{{ __('auth.forgot_password_subtitle') }}</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('auth.email_address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-envelope"></i> {{ __('auth.send_reset_link') }}
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">{{ __('auth.back_to_login') }}</a>
        </div>
    </div>
</body>
</html>
