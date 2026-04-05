@extends('layouts.app')

@section('title', __('user.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --user-ink-900: #0f172a;
        --user-ink-700: #334155;
        --user-ink-500: #64748b;
        --user-brand: #0f766e;
        --user-brand-deep: #155e75;
        --user-line: #d8e4ee;
    }

    .user-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--user-ink-900);
    }

    .user-shell::before {
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

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .user-header {
        gap: 0.9rem;
    }

    .user-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--user-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--user-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--user-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-user {
        background: linear-gradient(140deg, var(--user-brand), var(--user-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
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

    .btn-add-user:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--user-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .alert-success {
        border: 1px solid #b9ebd6;
        background: #f1fff8;
        color: #065f46;
        border-radius: 14px;
    }

    .alert-danger {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #991b1b;
        border-radius: 14px;
    }

    .table.table-custom {
        margin-bottom: 0;
    }

    .table.table-custom thead th {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .table.table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table.table-custom tbody tr:hover {
        background: #fbfdff;
    }

    .user-deleted {
        background: #fff6f6;
    }

    .user-name {
        color: #1e293b;
        font-weight: 700;
    }

    .you-chip {
        background: rgba(59, 130, 246, 0.14);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #1d4ed8;
        border-radius: 999px;
        padding: 0.2rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.36rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .badge-superadmin {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.26);
    }

    .badge-user {
        background: rgba(59, 130, 246, 0.12);
        color: #1d4ed8;
        border-color: rgba(59, 130, 246, 0.28);
    }

    .badge-owner {
        background: rgba(15, 118, 110, 0.12);
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.24);
    }

    .badge-manager {
        background: rgba(217, 119, 6, 0.12);
        color: #d97706;
        border-color: rgba(217, 119, 6, 0.26);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.36rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.14);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.3);
    }

    .status-deactive {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.28);
    }

    .actions-cell {
        white-space: nowrap;
    }

    .btn-row-action {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .btn-row-view {
        color: #0369a1;
        border-color: rgba(14, 116, 144, 0.28);
    }

    .btn-row-view:hover {
        color: #fff;
        background: #0284c7;
        border-color: #0284c7;
    }

    .btn-row-edit {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.42);
    }

    .btn-row-edit:hover {
        color: #fff;
        background: #f59e0b;
        border-color: #f59e0b;
    }

    .btn-row-delete {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.34);
    }

    .btn-row-delete:hover {
        color: #fff;
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-row-restore {
        color: #047857;
        border-color: rgba(16, 185, 129, 0.35);
    }

    .btn-row-restore:hover {
        color: #fff;
        background: #10b981;
        border-color: #10b981;
    }

    .btn-row-force-delete {
        color: #991b1b;
        border-color: rgba(153, 27, 27, 0.36);
    }

    .btn-row-force-delete:hover {
        color: #fff;
        background: #991b1b;
        border-color: #991b1b;
    }

    .empty-state {
        padding: 2.5rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.2rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .btn-create-first {
        background: linear-gradient(140deg, var(--user-brand), var(--user-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 0.88rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-create-first:hover {
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .user-header {
            align-items: stretch !important;
        }

        .btn-add-user {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="user-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap user-header">
    <div>
        <span class="user-kicker"><i class="bi bi-people"></i>{{ __('user.title') }}</span>
        <h1 class="page-title display-font">{{ __('user.title') }}</h1>
        <p class="page-subtitle">{{ __('user.subtitle') }}</p>
    </div>
    <a href="{{ route('user.create') }}" class="btn-add-user">
        <i class="bi bi-plus-circle"></i> {{ __('user.add_new') }}
    </a>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>{{ __('user.col_name') }}</th>
                    <th>{{ __('user.col_email') }}</th>
                    <th>{{ __('user.col_role') }}</th>
                    <th>{{ __('user.col_created') }}</th>
                    <th>{{ __('user.col_status') }}</th>
                    <th class="text-end">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="{{ $user->trashed() ? 'user-deleted' : '' }}">
                        <td>
                            <strong class="user-name">{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="you-chip ms-1">{{ __('user.you') }}</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge {{ $user->role === 'superadmin' ? 'badge-superadmin' : ($user->role === 'manager' ? 'badge-manager' : 'badge-owner') }}">
                                {{ __('user.role_' . $user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->trashed())
                                <span class="status-badge status-deactive">{{ __('user.deactive') }}</span>
                            @elseif($user->isPendingApproval())
                                <span class="status-badge status-pending"><i class="bi bi-clock-history me-1"></i>{{ __('user.pending_approval') }}</span>
                            @else
                                <span class="status-badge status-active">{{ __('user.active') }}</span>
                            @endif
                        </td>
                        <td class="text-end actions-cell">
                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-row-action btn-row-view" title="{{ __('app.view') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($user->trashed())
                                <form action="{{ route('user.activate', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('user.confirm_activate') }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-row-action btn-row-restore" title="{{ __('user.activate') }}">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            @elseif($user->isPendingApproval())
                                <a href="{{ route('user.approve.form', $user->id) }}" class="btn btn-sm btn-row-action" style="color:#d97706;border-color:rgba(217,119,6,.38);background:#fffbf5;" title="{{ __('user.approve_title') }}">
                                    <i class="bi bi-person-check"></i>
                                </a>
                            @else
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-row-action btn-row-edit" title="{{ __('app.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('user.deactivate', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('user.confirm_deactivate') }}')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-row-action btn-row-delete" title="{{ __('user.deactivate') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center empty-state">
                            <i class="bi bi-people"></i>
                            <div class="mb-2 text-muted">{{ __('user.no_users') }}</div>
                            <a href="{{ route('user.create') }}" class="btn-create-first">
                                <i class="bi bi-plus-circle"></i> {{ __('user.add_new') }}
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
</div>
@endsection
