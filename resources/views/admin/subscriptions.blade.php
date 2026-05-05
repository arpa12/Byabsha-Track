@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-stars me-2"></i>Subscription Plans</h4>
    </div>

    <div class="row g-4">
        @foreach($plans as $plan)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $plan['name'] }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $plan['price'] }}</h6>
                        <p class="card-text">{{ $plan['description'] }}</p>

                        <ul class="list-unstyled mt-3 mb-4">
                            @foreach($plan['features'] as $label => $included)
                                <li>
                                    @if($included)
                                        <span class="text-success">✓</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                    <span class="ms-2">{{ $label }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto">
                            @if($plan['key'] === 'premium')
                                <a href="#" class="btn btn-primary w-100">Choose Premium</a>
                            @elseif($plan['key'] === 'standard')
                                <a href="#" class="btn btn-outline-primary w-100">Choose Standard</a>
                            @else
                                <a href="#" class="btn btn-outline-secondary w-100">Choose Basic</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
