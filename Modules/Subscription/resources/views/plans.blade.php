@extends('layouts.app')
@section('title', __( 'subscription::subscription.plans' ))
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-2"></i>{{ __( 'subscription::subscription.plans' ) }}</h4>
    <a href="{{ route('subscription.my') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-receipt me-1"></i>{{ __('subscription::subscription.my_subscription') }}</a>
  </div>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @if($pendingRequest)
  <div class="alert alert-info d-flex gap-2 mb-4"><i class="bi bi-hourglass-split fs-5"></i><div><strong>{{ __('subscription::subscription.payment_under_review') }}</strong>  {{ __('subscription::subscription.payment_pending_approval', ['plan' => $pendingRequest->plan->name, 'amount' => number_format($pendingRequest->amount)]) }}</div></div>
  @endif
  <div class="row g-4 justify-content-center">
  @foreach($plans as $plan)
  @php $isCurrent = $currentPlan && $currentPlan->id === $plan->id; @endphp
  <div class="col-md-6 col-lg-3"><div class="card h-100 shadow-sm border-0 {{ $isCurrent ? 'border border-2 border-secondary' : '' }}">
    <div class="card-body d-flex flex-column p-4">
      <h5 class="fw-bold mb-1">{{ $plan->name }}</h5>
      <div class="mb-3">@if($plan->isFree())<span class="fs-3 fw-bold text-success">{{ __('subscription::subscription.free') }}</span>@else<span class="fs-3 fw-bold">{{ number_format($plan->price) }}</span><span class="text-muted small">{{ __('subscription::subscription.per_month') }}</span>@endif</div>
      <ul class="list-unstyled small mb-4 flex-grow-1">
        <li class="mb-1"><i class="bi bi-shop me-2 text-primary"></i>{{ $plan->max_shops ?? __('subscription::subscription.unlimited') }} {{ __('subscription::subscription.shops') }}</li>
        <li class="mb-1"><i class="bi bi-building me-2 text-primary"></i>{{ $plan->max_branches ?? __('subscription::subscription.unlimited') }} {{ __('subscription::subscription.branches') }}</li>
        <li class="mb-1"><i class="bi bi-tag me-2 text-primary"></i>{{ $plan->max_brands ?? __('subscription::subscription.unlimited') }} {{ __('subscription::subscription.brands') }}</li>
        <li class="mb-1"><i class="bi bi-grid me-2 text-primary"></i>{{ $plan->max_categories ?? __('subscription::subscription.unlimited') }} {{ __('subscription::subscription.categories') }}</li>
        <li class="mb-1"><i class="bi bi-cart me-2 text-primary"></i>{{ $plan->max_sales ? number_format($plan->max_sales) : __('subscription::subscription.unlimited') }} {{ __('subscription::subscription.sales_per_month') }}</li>
        <li class="mb-1">
          @if($plan->has_capital)<i class="bi bi-check-circle-fill me-2 text-success"></i>{{ __('subscription::subscription.capital') }}
          @else<i class="bi bi-x-circle-fill me-2 text-danger"></i><span class="text-muted">{{ __('subscription::subscription.capital') }}</span>
          @endif
        </li>
        <li class="mb-1">
          @if($plan->has_restock)<i class="bi bi-check-circle-fill me-2 text-success"></i>{{ __('subscription::subscription.restock') }}
          @else<i class="bi bi-x-circle-fill me-2 text-danger"></i><span class="text-muted">{{ __('subscription::subscription.restock') }}</span>
          @endif
        </li>
        <li class="mb-1">
          @if($plan->has_reports)<i class="bi bi-check-circle-fill me-2 text-success"></i>{{ __('subscription::subscription.reports') }}
          @else<i class="bi bi-x-circle-fill me-2 text-danger"></i><span class="text-muted">{{ __('subscription::subscription.reports') }}</span>
          @endif
        </li>
      </ul>
      @if($isCurrent)
        <button class="btn btn-secondary w-100" disabled><i class="bi bi-check2-circle me-1"></i>{{ __('subscription::subscription.current_plan_btn') }}</button>
      @elseif($plan->isFree())
        <button class="btn btn-outline-secondary w-100" disabled>{{ __('subscription::subscription.free_forever') }}</button>
      @elseif($pendingRequest)
        <button class="btn btn-warning w-100" disabled><i class="bi bi-hourglass me-1"></i>{{ __('subscription::subscription.pending_review') }}</button>
      @else
        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#payModal" data-plan-id="{{ $plan->id }}" data-plan-name="{{ $plan->name }}" data-plan-price="{{ $plan->price }}">
          <i class="bi bi-wallet2 me-1"></i>{{ __('subscription::subscription.submit_payment') }}
        </button>
      @endif
    </div></div></div>
  @endforeach
  </div>
</div>

{{-- ======================================================================
     Payment Modal  Step-by-step bKash manual payment flow
     ====================================================================== --}}
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
  <div class="modal-header border-0 pb-0">
    <h5 class="modal-title fw-bold"><i class="bi bi-wallet2 me-2 text-danger"></i>{{ __('subscription::subscription.subscribe_via_bkash') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
  </div>

  <form action="{{ route('subscription.payment.submit') }}" method="POST" enctype="multipart/form-data"
        style="display:flex;flex-direction:column;flex:1 1 auto;min-height:0;overflow:hidden;">@csrf
  <input type="hidden" name="plan_id" id="modal-plan-id">

  <div class="modal-body pt-2" style="overflow-y:auto;">

    {{-- Step 1 --- Choose duration & see amount --}}
    <div class="d-flex gap-2 mb-3">
      <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold" style="width:28px;height:28px;font-size:.85rem;">1</div>
      <div class="flex-grow-1">
        <div class="fw-semibold mb-1">{{ __('subscription::subscription.choose_plan_duration') }}</div>
        <div class="mb-2">
          <span class="text-muted small">{{ __('subscription::subscription.selected_plan') }} </span>
          <span id="modal-plan-name" class="fw-semibold"></span>
        </div>
        <select class="form-select form-select-sm" name="duration_months" id="duration_months" required>
          @for($i=1;$i<=12;$i++)
            <option value="{{ $i }}">{{ $i }} {{ $i>1 ? __('subscription::subscription.months') : __('subscription::subscription.month') }}</option>
          @endfor
        </select>
        <div class="form-text mt-1">{{ __('subscription::subscription.total_payable') }} <strong class="text-success fs-6" id="modal-total"></strong></div>
      </div>
    </div>

    {{-- Shop & Branch selection (owners only) --}}
    @if($shops->isNotEmpty())
    <hr class="my-3">
    <div class="d-flex gap-2 mb-3">
      <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-bold" style="width:28px;height:28px;font-size:.85rem;"><i class="bi bi-shop" style="font-size:.75rem;"></i></div>
      <div class="flex-grow-1">
        <div class="fw-semibold mb-2">{{ __('subscription::subscription.select_shop_branch') }}</div>
        <div class="mb-2">
          <label class="form-label small fw-semibold">{{ __('subscription::subscription.shop') }} <span class="text-danger">*</span></label>
          <select class="form-select form-select-sm" name="shop_id" id="modal-shop-id" required>
            <option value="">{{ __('subscription::subscription.select_shop') }}</option>
            @foreach($shops as $shop)
              <option value="{{ $shop->id }}" data-branches="{{ $shop->branches->map(fn($b) => ['id'=>$b->id,'name'=>$b->name])->toJson() }}">
                {{ $shop->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label small fw-semibold">{{ __('subscription::subscription.branch') }} <span class="text-muted">({{ __('subscription::subscription.optional') }})</span></label>
          <select class="form-select form-select-sm" name="branch_id" id="modal-branch-id">
            <option value="">{{ __('subscription::subscription.all_branches') }}</option>
          </select>
        </div>
      </div>
    </div>
    @endif

    <hr class="my-3">

    {{-- Step 2 --- Send bKash payment --}}
    <div class="d-flex gap-2 mb-3">
      <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-danger text-white fw-bold" style="width:28px;height:28px;font-size:.85rem;">2</div>
      <div class="flex-grow-1">
        <div class="fw-semibold mb-2">{{ __('subscription::subscription.send_bkash_payment') }}</div>
        <div class="rounded-3 p-3 mb-2" style="background:#fde8ea;border:1px solid #f5c2c7;">
          <div class="small fw-semibold text-danger mb-1"><i class="bi bi-phone-fill me-1"></i>{{ __('subscription::subscription.send_money_to_bkash') }}</div>
          <div class="fs-4 fw-bold text-center text-danger letter-spacing-1">{{ config('subscription.bkash_number', '01700000000') }}</div>
          <div class="text-center small text-muted mt-1"><i class="bi bi-arrow-right-circle me-1"></i>{{ __('subscription::subscription.use_send_money') }}</div>
        </div>
        <ol class="small text-muted ps-3 mb-0">
          <li class="mb-1">{{ __('subscription::subscription.bkash_step_1') }}</li>
          <li class="mb-1">{{ __('subscription::subscription.bkash_step_2') }}</li>
          <li class="mb-1">{{ __('subscription::subscription.bkash_step_3') }}</li>
          <li class="mb-1">{{ __('subscription::subscription.bkash_step_4') }}</li>
          <li>{{ __('subscription::subscription.bkash_step_5') }}</li>
        </ol>
      </div>
    </div>

    <hr class="my-3">

    {{-- Step 3 --- Submit transaction details --}}
    <div class="d-flex gap-2">
      <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-success text-white fw-bold" style="width:28px;height:28px;font-size:.85rem;">3</div>
      <div class="flex-grow-1">
        <div class="fw-semibold mb-3">{{ __('subscription::subscription.enter_payment_details') }}</div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">{{ __('subscription::subscription.your_bkash_number') }} <span class="text-danger">*</span></label>
          <input type="text" class="form-control form-control-sm" name="sender_bkash_number"
                 placeholder="01XXXXXXXXX" pattern="01[3-9]\d{8}" maxlength="11" required>
          <div class="form-text">{{ __('subscription::subscription.bkash_number_hint') }}</div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">{{ __('subscription::subscription.transaction_id_label') }} <span class="text-danger">*</span></label>
          <input type="text" class="form-control form-control-sm" name="transaction_id"
                 placeholder="e.g. 8AB3K2PQ91" maxlength="100" required>
          <div class="form-text">{{ __('subscription::subscription.transaction_id_hint') }}</div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">{{ __('subscription::subscription.receipt_screenshot') }} <span class="text-muted">{{ __('subscription::subscription.optional_recommended') }}</span></label>
          <input type="file" class="form-control form-control-sm" name="receipt_image"
                 accept="image/jpeg,image/png,image/webp">
          <div class="form-text">{{ __('subscription::subscription.receipt_hint') }}</div>
        </div>
      </div>
    </div>

    {{-- What happens next --}}
    <div class="alert alert-info d-flex gap-2 mb-0 mt-3 py-2 px-3 small">
      <i class="bi bi-hourglass-split flex-shrink-0 mt-1"></i>
      <span>{{ __('subscription::subscription.manual_verify_note') }}</span>
    </div>

  </div>{{-- /modal-body --}}

  <div class="modal-footer border-0 pt-1">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('subscription::subscription.cancel') }}</button>
    <button type="submit" class="btn btn-danger px-4">
      <i class="bi bi-send me-1"></i>{{ __('subscription::subscription.submit_payment_request') }}
    </button>
  </div>
  </form>
</div></div></div>

@push('scripts')
<script>
document.getElementById('payModal').addEventListener('show.bs.modal', function(e) {
  var btn = e.relatedTarget;
  document.getElementById('modal-plan-id').value = btn.dataset.planId;
  document.getElementById('modal-plan-name').textContent = btn.dataset.planName;
  window._planPrice = parseInt(btn.dataset.planPrice, 10);
  updateTotal();
});
document.getElementById('duration_months').addEventListener('change', updateTotal);
function updateTotal() {
  var months = parseInt(document.getElementById('duration_months').value, 10);
  var total = (window._planPrice || 0) * months;
  document.getElementById('modal-total').textContent = '\u09F3' + total.toLocaleString();
}

// Shop → Branch cascade
var shopSelect   = document.getElementById('modal-shop-id');
var branchSelect = document.getElementById('modal-branch-id');
if (shopSelect) {
  shopSelect.addEventListener('change', function() {
    var selected = shopSelect.options[shopSelect.selectedIndex];
    var branches = selected.dataset.branches ? JSON.parse(selected.dataset.branches) : [];
    branchSelect.innerHTML = '<option value="">-- All Branches --</option>';
    branches.forEach(function(b) {
      var opt = document.createElement('option');
      opt.value = b.id;
      opt.textContent = b.name;
      branchSelect.appendChild(opt);
    });
  });
}
</script>
@endpush
@endsection
