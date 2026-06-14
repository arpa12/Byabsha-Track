<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.warranties_report') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #0f766e; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #0f766e; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        
        .summary { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e2e8f0; }
        .summary-item { display: table-cell; width: 20%; text-align: center; padding: 10px 6px; border-right: 1px solid #e2e8f0; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
        
        .color-active { color: #16a34a; }
        .color-expired { color: #d97706; }
        .color-claimed { color: #0284c7; }
        .color-void { color: #dc2626; }
        
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10.5px; }
        table.report-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name">{{ $shopName }}</div>
        <div class="date-range">{{ __('report.warranties_report') }} &mdash; {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</div>
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">{{ __('report.total_warranties') }}</div>
            <div class="summary-value">{{ $warrantySummary->total ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('sale.status_active') }}</div>
            <div class="summary-value color-active">{{ $warrantySummary->active ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('sale.status_expired') }}</div>
            <div class="summary-value color-expired">{{ $warrantySummary->expired ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('sale.status_claimed') }}</div>
            <div class="summary-value color-claimed">{{ $warrantySummary->claimed ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('sale.status_void') }}</div>
            <div class="summary-value color-void">{{ $warrantySummary->voided ?? 0 }}</div>
        </div>
    </div>

    @if($warranties->count() > 0)
    <table class="report-table">
        <thead>
            <tr>
                <th>{{ __('report.warranty_code') }}</th>
                <th>{{ __('report.shop') }}</th>
                <th>{{ __('report.product_name') }}</th>
                <th>{{ __('report.customer_name') }}</th>
                <th>{{ __('report.customer_phone') }}</th>
                <th>{{ __('report.warranty_period') }}</th>
                <th>{{ __('sale.warranty_status') }}</th>
                <th>{{ __('report.terms_notes') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warranties as $warranty)
            @php
                $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
            @endphp
            <tr>
                <td><strong>{{ $warranty->warranty_code }}</strong></td>
                <td>{{ $warranty->shop->name }}</td>
                <td>{{ $warranty->sale->product->name ?? '-' }}</td>
                <td>{{ $warranty->sale->customer_name ?: '-' }}</td>
                <td>{{ $warranty->sale->customer_phone ?: '-' }}</td>
                <td>{{ $warranty->start_date->format('M d, Y') }} - {{ $warranty->end_date->format('M d, Y') }}</td>
                <td class="
                    @if($effectiveStatus === 'active') color-active
                    @elseif($effectiveStatus === 'expired') color-expired
                    @elseif($effectiveStatus === 'claimed') color-claimed
                    @else color-void
                    @endif
                    " style="font-weight: bold;">
                    {{ __('sale.status_' . $effectiveStatus) }}
                </td>
                <td>{{ $warranty->terms ?: ($warranty->claim_note ?: '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_warranties_found') }}</p>
    @endif

    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>
