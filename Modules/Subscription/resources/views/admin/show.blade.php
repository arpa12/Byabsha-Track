@extends('layouts.app')
@section('title', 'Review Payment')
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Review Payment #{{ $paymentRequest->id }}</h4>
  </div>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  <div class="row g-4">
    <div class="col-md-7">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0">Payment Details</h6></div>
        <div class="card-body"><table class="table table-sm table-borderless">
          <tr><th class="text-muted fw-normal w-40">User</th><td>{{ $paymentRequest->user->name }} <span class="text-muted small">({{ $paymentRequest->user->email }})</span></td></tr>
          <tr><th class="text-muted fw-normal">Shop</th><td>{{ $paymentRequest->shop?->name ?? '—' }}</td></tr>
          <tr><th class="text-muted fw-normal">Branch</th><td>{{ $paymentRequest->branch?->name ?? '—' }}</td></tr>
          <tr><th class="text-muted fw-normal">Plan</th><td>{{ $paymentRequest->plan->name }}</td></tr>
          <tr><th class="text-muted fw-normal">Amount</th><td class="fw-bold text-success">{{ number_format($paymentRequest->amount) }}</td></tr>
          <tr><th class="text-muted fw-normal">Duration</th><td>{{ $paymentRequest->duration_months }} month(s)</td></tr>
          <tr><th class="text-muted fw-normal">Sender bKash</th><td><code>{{ $paymentRequest->sender_bkash_number }}</code></td></tr>
          <tr><th class="text-muted fw-normal">Txn ID</th><td><code>{{ $paymentRequest->transaction_id }}</code></td></tr>
          <tr><th class="text-muted fw-normal">Submitted</th><td>{{ $paymentRequest->created_at->format('d M Y, H:i') }}</td></tr>
          <tr><th class="text-muted fw-normal">Status</th><td>@if($paymentRequest->status==='approved')<span class="badge bg-success">Approved</span>@elseif($paymentRequest->status==='rejected')<span class="badge bg-danger">Rejected</span>@else<span class="badge bg-warning text-dark">Pending</span>@endif</td></tr>
          @if($paymentRequest->admin_note)<tr><th class="text-muted fw-normal">Note</th><td>{{ $paymentRequest->admin_note }}</td></tr>@endif
        </table></div>
      </div>
      @if($paymentRequest->receipt_image)
      <div class="card border-0 shadow-sm mt-4"><div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0">Receipt</h6></div>
        <div class="card-body text-center"><img src="{{ Storage::url($paymentRequest->receipt_image) }}" class="img-fluid rounded" style="max-height:400px" alt="Receipt"></div>
      </div>
      @endif
    </div>
    <div class="col-md-5">
      @if($paymentRequest->status === 'pending')
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0 text-success"><i class="bi bi-check-circle me-2"></i>Approve</h6></div>
        <div class="card-body">
          <p class="small text-muted mb-3">Activates <strong>{{ $paymentRequest->plan->name }}</strong> for <strong>{{ $paymentRequest->duration_months }}</strong> month(s) for {{ $paymentRequest->user->name }}.</p>
          <form action="{{ route('admin.subscriptions.approve', $paymentRequest) }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label small">Note (optional)</label>
            <textarea name="admin_note" class="form-control form-control-sm" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this payment?')"><i class="bi bi-check-lg me-1"></i>Approve & Activate</button>
          </form>
        </div></div>
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0 text-danger"><i class="bi bi-x-circle me-2"></i>Reject</h6></div>
        <div class="card-body">
          <form action="{{ route('admin.subscriptions.reject', $paymentRequest) }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label small">Reason <span class="text-danger">*</span></label>
            <textarea name="admin_note" class="form-control form-control-sm" rows="2" required></textarea></div>
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this payment?')"><i class="bi bi-x-lg me-1"></i>Reject</button>
          </form>
        </div></div>
      @else
      <div class="alert alert-secondary"><i class="bi bi-info-circle me-2"></i>This request was <strong>{{ $paymentRequest->status }}</strong>@if($paymentRequest->reviewer) by {{ $paymentRequest->reviewer->name }}@endif.</div>
      @endif
    </div>
  </div>
</div>
@endsection
