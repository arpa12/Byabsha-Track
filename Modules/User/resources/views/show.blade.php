@extends('layouts.app')

@section('title', __('user.show_title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ __('user.show_title') }}</h1>
        <p class="page-subtitle">{{ __('user.show_subtitle') }}</p>
    </div>
    <div>
        @if(!$user->trashed())
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> {{ __('app.edit') }}
            </a>
        @endif
        <a href="{{ route('user.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>
</div>

<div class="content-card">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <th style="width: 200px;">{{ __('user.name') }}</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>{{ __('user.email') }}</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>{{ __('user.role') }}</th>
                <td>
                    <span class="badge {{ $user->role === 'superadmin' ? 'bg-warning' : 'bg-info' }}">
                        {{ __('user.role_' . $user->role) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>{{ __('user.col_status') }}</th>
                <td>
                    @if($user->trashed())
                        <span class="badge bg-danger">{{ __('user.deleted') }}</span>
                        <span class="text-muted ms-2">{{ __('user.deleted_at') }}: {{ $user->deleted_at->format('M d, Y H:i') }}</span>
                    @else
                        <span class="badge bg-success">{{ __('user.active') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{ __('user.created_at') }}</th>
                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @if($user->updated_at != $user->created_at)
            <tr>
                <th>{{ __('user.updated_at') }}</th>
                <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@if($user->trashed())
    <div class="content-card mt-3 bg-danger-subtle">
        <h5 class="text-danger mb-3">{{ __('user.deleted_warning_title') }}</h5>
        <p class="mb-3">{{ __('user.deleted_warning_text') }}</p>
        <div class="d-flex gap-2">
            <form action="{{ route('user.restore', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-arrow-counterclockwise"></i> {{ __('user.restore') }}
                </button>
            </form>
            <form action="{{ route('user.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('user.confirm_permanent_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash-fill"></i> {{ __('user.permanent_delete') }}
                </button>
            </form>
        </div>
    </div>
@endif
@endsection
