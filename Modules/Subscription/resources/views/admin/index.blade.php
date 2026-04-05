@extends('layouts.app')

@section('title', 'Manage Subscriptions')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-credit-card me-2"></i>Subscription Payments</h4>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-3 mb-4">
    <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-warning">{{ $counts['pending'] }}</div><div class="small text-muted">Pending</div></div></div>
    <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-success">{{ $counts['approved'] }}</div><div class="small text-muted">Approved</div></div></div>
    <div class="col-sm-4"><div class="card border-0 shadow-sm text-center p-3"><div class="fs-2 fw-bold text-danger">{{ $counts['rejected'] }}</div><div class="small text-muted">Rejected</div></div></div>
  </div>

  {{-- Filter Bar --}}
  <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="card border-0 shadow-sm mb-3 p-3">
    <input type="hidden" name="status" value="{{ $status }}">
    <div class="row g-2 align-items-end">
      <div class="col-sm-4">
        <label class="form-label small mb-1">Search User</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email…" value="{{ $search }}">
      </div>
      <div class="col-sm-3">
        <label class="form-label small mb-1">Shop</label>
        <select name="shop_id" id="filter-shop-id" class="form-select form-select-sm">
          <option value="">All Shops</option>
          @foreach($shops as $shop)
            <option value="{{ $shop->id }}"
              data-branches="{{ $shop->branches->map(fn($b) => ['id'=>$b->id,'name'=>$b->name])->toJson() }}"
              {{ $shopId == $shop->id ? 'selected' : '' }}>
              {{ $shop->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3">
        <label class="form-label small mb-1">Branch</label>
        <select name="branch_id" id="filter-branch-id" class="form-select form-select-sm">
          <option value="">All Branches</option>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-2 d-flex gap-1">
        <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-funnel-fill"></i> Filter</button>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </div>
  </form>

  <ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link {{ $status==='pending' ? 'active' : '' }}" href="{{ route('admin.subscriptions.index', array_filter(['status'=>'pending','shop_id'=>$shopId,'branch_id'=>$branchId,'search'=>$search])) }}">Pending @if($counts['pending']>0)<span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>@endif</a></li>
    <li class="nav-item"><a class="nav-link {{ $status==='approved' ? 'active' : '' }}" href="{{ route('admin.subscriptions.index', array_filter(['status'=>'approved','shop_id'=>$shopId,'branch_id'=>$branchId,'search'=>$search])) }}">Approved</a></li>
    <li class="nav-item"><a class="nav-link {{ $status==='rejected' ? 'active' : '' }}" href="{{ route('admin.subscriptions.index', array_filter(['status'=>'rejected','shop_id'=>$shopId,'branch_id'=>$branchId,'search'=>$search])) }}">Rejected</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.subscriptions.active') }}"><i class="bi bi-check2-circle me-1"></i>Active Subscriptions</a></li>
  </ul>

  <div class="card border-0 shadow-sm"><div class="card-body p-0">
    @if($requests->isEmpty())
      <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No {{ $status }} requests.</div>
    @else
    <div class="table-responsive"><table class="table table-hover align-middle mb-0">
      <thead class="table-light"><tr><th>#</th><th>User</th><th>Shop</th><th>Branch</th><th>Plan</th><th>Amount</th><th>Duration</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
        @foreach($requests as $req)
        <tr>
          <td class="text-muted small">{{ $req->id }}</td>
          <td><div class="fw-semibold">{{ $req->user->name }}</div><div class="text-muted small">{{ $req->user->email }}</div></td>
          <td>{{ $req->shop?->name ?? '—' }}</td>
          <td>{{ $req->branch?->name ?? '—' }}</td>
          <td>{{ $req->plan->name }}</td>
          <td>{{ number_format($req->amount) }}</td>
          <td>{{ $req->duration_months }}mo</td>
          <td>{{ $req->created_at->diffForHumans() }}</td>
          <td><a href="{{ route('admin.subscriptions.show', $req) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Review</a></td>
        </tr>
        @endforeach
      </tbody></table></div>
      <div class="p-3">{{ $requests->links() }}</div>
    @endif
  </div></div>
</div>
@push('scripts')
<script>
(function () {
  var shopSel   = document.getElementById('filter-shop-id');
  var branchSel = document.getElementById('filter-branch-id');
  if (!shopSel) return;
  shopSel.addEventListener('change', function () {
    var opt      = shopSel.options[shopSel.selectedIndex];
    var branches = opt.dataset.branches ? JSON.parse(opt.dataset.branches) : [];
    branchSel.innerHTML = '<option value="">All Branches</option>';
    branches.forEach(function (b) {
      var o = document.createElement('option');
      o.value = b.id; o.textContent = b.name;
      branchSel.appendChild(o);
    });
  });
})();
</script>
@endpush
@endsection
