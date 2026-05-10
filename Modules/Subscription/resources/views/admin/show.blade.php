@extends('layouts.app')
@section('title', __('subscription::subscription.review_payment_title') . ' #' . $paymentRequest->id)
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> {{ __('subscription::subscription.back_to_list') }}</a>
    <h4 class="fw-bold mb-0">{{ __('subscription::subscription.review_payment_title') }} #{{ $paymentRequest->id }}</h4>
  </div>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  <div class="row g-4">
    <div class="col-md-7">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0">{{ __('subscription::subscription.payment_details') }}</h6></div>
        <div class="card-body"><table class="table table-sm table-borderless">
          <tr><th class="text-muted fw-normal w-40">{{ __('subscription::subscription.table_user') }}</th><td>{{ $paymentRequest->user->name }} <span class="text-muted small">({{ $paymentRequest->user->email }})</span></td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.shop') }}</th><td>{{ $paymentRequest->shop?->name ?? '—' }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.branch') }}</th><td>{{ $paymentRequest->branch?->name ?? '—' }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.table_plan') }}</th><td>{{ $paymentRequest->plan->name }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.table_amount') }}</th><td class="fw-bold text-success">{{ number_format($paymentRequest->amount) }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.table_duration') }}</th><td>{{ $paymentRequest->duration_months }} {{ __('subscription::subscription.months') }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.sender_bkash') }}</th><td><code>{{ $paymentRequest->sender_bkash_number }}</code></td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.txn_id') }}</th><td><code>{{ $paymentRequest->transaction_id }}</code></td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.submitted') }}</th><td>{{ $paymentRequest->created_at->format('d M Y, H:i') }}</td></tr>
          <tr><th class="text-muted fw-normal">{{ __('subscription::subscription.status') }}</th><td>@if($paymentRequest->status==='approved')<span class="badge bg-success">{{ __('subscription::subscription.approved') }}</span>@elseif($paymentRequest->status==='rejected')<span class="badge bg-danger">{{ __('subscription::subscription.rejected') }}</span>@else<span class="badge bg-warning text-dark">{{ __('subscription::subscription.pending') }}</span>@endif</td></tr>
          @if($paymentRequest->admin_note)<tr><th class="text-muted fw-normal">{{ __('subscription::subscription.note') }}</th><td>{{ $paymentRequest->admin_note }}</td></tr>@endif
        </table></div>
      </div>
      @if($paymentRequest->receipt_image)
      <div class="card border-0 shadow-sm mt-4"><div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0">{{ __('subscription::subscription.receipt') }}</h6></div>
        <div class="card-body text-center"><img src="{{ Storage::url($paymentRequest->receipt_image) }}" class="img-fluid rounded" style="max-height:400px" alt="{{ __('subscription::subscription.receipt') }}"></div>
      </div>
      @endif
    </div>
    <div class="col-md-5">
      @if($paymentRequest->status === 'pending')
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0 text-success"><i class="bi bi-check-circle me-2"></i>{{ __('subscription::subscription.approve_section') }}</h6></div>
        <div class="card-body">
          <p class="small text-muted mb-3">{{ __('subscription::subscription.activates', ['plan' => $paymentRequest->plan->name, 'duration' => $paymentRequest->duration_months, 'user' => $paymentRequest->user->name]) }}</p>
          <form action="{{ route('admin.subscriptions.approve', $paymentRequest) }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label small">{{ __('subscription::subscription.note_optional') }}</label>
            <textarea name="admin_note" class="form-control form-control-sm" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success w-100" onclick="return confirm('{{ __('subscription::subscription.approve_confirm') }}')"><i class="bi bi-check-lg me-1"></i>{{ __('subscription::subscription.approve_button') }}</button>
          </form>
        </div></div>
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0 text-danger"><i class="bi bi-x-circle me-2"></i>{{ __('subscription::subscription.reject_section') }}</h6></div>
        <div class="card-body">
          <form action="{{ route('admin.subscriptions.reject', $paymentRequest) }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label small">{{ __('subscription::subscription.reason_label') }} <span class="text-danger">*</span></label>
            <textarea name="admin_note" class="form-control form-control-sm" rows="2" required></textarea></div>
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('{{ __('subscription::subscription.reject_confirm') }}')"><i class="bi bi-x-lg me-1"></i>{{ __('subscription::subscription.reject_button') }}</button>
          </form>
        </div></div>
      @else
      <div class="alert alert-secondary"><i class="bi bi-info-circle me-2"></i>{{ __('subscription::subscription.request_status_msg', ['status' => $paymentRequest->status]) }}@if($paymentRequest->reviewer) {{ __('subscription::subscription.reviewed_by', ['reviewer' => $paymentRequest->reviewer->name]) }}@endif.</div>
      @endif
    </div>
  </div>
</div>
@endsection
