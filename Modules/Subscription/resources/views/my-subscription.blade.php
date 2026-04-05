@extends('layouts.app')
@section('title', __( 'subscription::subscription.my_subscription' ))
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>{{ __( 'subscription::subscription.my_subscription' ) }}</h4>
    <a href="{{ route('subscription.plans') }}" class="btn btn-primary btn-sm"><i class="bi bi-grid-3x3-gap me-1"></i>{{ __('subscription::subscription.view_plans') }}</a>
  </div>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  <div class="row g-4 mb-4">
    <div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
      <div class="d-flex align-items-center gap-3 mb-3"><div class="bg-primary bg-opacity-10 rounded-circle p-3"><i class="bi bi-star-fill text-primary fs-4"></i></div>
      <div><div class="text-muted small">{{ __('subscription::subscription.current_plan') }}</div><div class="fw-bold fs-5">{{ $currentPlan->name }}</div></div></div>
      @if($subscription && $subscription->ends_at)
        <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-calendar-check text-success"></i><span class="small">{{ __('subscription::subscription.active_until') }} <strong>{{ $subscription->ends_at->format('d M Y') }}</strong></span></div>
        <div class="text-muted small">{{ $subscription->ends_at->diffForHumans() }}</div>
      @else
        <div class="text-muted small"><i class="bi bi-infinity me-1"></i>{{ __('subscription::subscription.free_plan_no_expiry') }}</div>
      @endif
    </div></div></div>
    <div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
      <h6 class="fw-semibold mb-3">{{ __('subscription::subscription.plan_features') }}</h6><ul class="list-unstyled small mb-0">
      <li class="mb-1"><i class="bi bi-shop me-2 text-primary"></i>{{ __('subscription::subscription.up_to') }} {{ $currentPlan->max_shops ?? '' }} {{ __('subscription::subscription.shops') }}</li>
      <li class="mb-1"><i class="bi bi-building me-2 text-primary"></i>{{ __('subscription::subscription.up_to') }} {{ $currentPlan->max_branches ?? '' }} {{ __('subscription::subscription.branches') }}</li>
      <li class="mb-1">@if($currentPlan->has_capital)<i class="bi bi-check-circle-fill text-success me-2"></i>{{ __('subscription::subscription.capital') }}@else<i class="bi bi-x-circle-fill text-danger me-2"></i><span class="text-muted">{{ __('subscription::subscription.capital') }}</span>@endif</li>
      <li class="mb-1">@if($currentPlan->has_reports)<i class="bi bi-check-circle-fill text-success me-2"></i>{{ __('subscription::subscription.reports') }}@else<i class="bi bi-x-circle-fill text-danger me-2"></i><span class="text-muted">{{ __('subscription::subscription.reports') }}</span>@endif</li>
      </ul></div></div></div>
  </div>
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="bi bi-clock-history me-2"></i>{{ __('subscription::subscription.payment_history') }}</h6></div>
    <div class="card-body p-0">
      @if($history->isEmpty())
        <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>{{ __('subscription::subscription.no_payment_history') }}</div>
      @else
      <div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>{{ __('subscription::subscription.plan') }}</th><th>{{ __('subscription::subscription.amount') }}</th><th>{{ __('subscription::subscription.duration') }}</th><th>{{ __('subscription::subscription.txn_id') }}</th><th>{{ __('subscription::subscription.date') }}</th><th>{{ __('subscription::subscription.status') }}</th></tr></thead>
        <tbody>
          @foreach($history as $req)
          <tr>
            <td>{{ $req->plan->name }}</td>
            <td>{{ number_format($req->amount) }}</td>
            <td>{{ $req->duration_months }}mo</td>
            <td><code>{{ $req->transaction_id }}</code></td>
            <td>{{ $req->created_at->format('d M Y') }}</td>
            <td>@if($req->status==='approved')<span class="badge bg-success">{{ __('subscription::subscription.approved') }}</span>@elseif($req->status==='rejected')<span class="badge bg-danger" title="{{ $req->admin_note }}">{{ __('subscription::subscription.rejected') }}</span>@else<span class="badge bg-warning text-dark">{{ __('subscription::subscription.pending') }}</span>@endif</td>
          </tr>
          @endforeach
        </tbody></table></div>
        <div class="p-3">{{ $history->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
