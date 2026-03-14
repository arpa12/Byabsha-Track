@extends('layouts.app')

@section('title', __('user.title'))

@push('styles')
<style>
    .actions-cell {
        white-space: nowrap;
    }
    .badge-superadmin {
        background-color: #f59e0b;
    }
    .badge-manager {
        background-color: #3b82f6;
    }
    .badge-owner {
        background-color: #10b981;
    }
    .user-deleted {
        opacity: 0.6;
        background-color: #fee;
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ __('user.title') }}</h1>
        <p class="page-subtitle">{{ __('user.subtitle') }}</p>
    </div>
    <a href="{{ route('user.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('user.add_new') }}
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-info ms-1">{{ __('user.you') }}</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'superadmin' ? 'badge-superadmin' : ($user->role === 'owner' ? 'badge-owner' : 'badge-manager') }}">
                                {{ __('user.role_' . $user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->trashed())
                                <span class="badge bg-danger">{{ __('user.deleted') }}</span>
                            @else
                                <span class="badge bg-success">{{ __('user.active') }}</span>
                            @endif
                        </td>
                        <td class="text-end actions-cell">
                            @if($user->trashed())
                                <form action="{{ route('user.restore', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="{{ __('user.restore') }}">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                <form action="{{ route('user.force-delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('user.confirm_permanent_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('user.permanent_delete') }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-info" title="{{ __('app.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning" title="{{ __('app.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('user.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('app.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('user.no_users') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
