<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('report.exchanges_report') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #0f766e; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #0f766e; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        
        .summary { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e2e8f0; }
        .summary-item { display: table-cell; width: 25%; text-align: center; padding: 10px 6px; border-right: 1px solid #e2e8f0; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
        
        .color-replacement { color: #0f766e; }
        .color-return { color: #d97706; }
        .color-positive { color: #16a34a; }
        .color-negative { color: #dc2626; }
        .color-neutral { color: #475569; }
        
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10px; }
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
        <div class="date-range">{{ __('report.exchanges_report') }} &mdash; {{ $filters['start_date'] }} to {{ $filters['end_date'] }}</div>
        <div class="generated">{{ __('report.generated_at') }}: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">{{ __('report.exchanges_count') }}</div>
            <div class="summary-value">{{ $exchangeSummary->total ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.replacements_count') }}</div>
            <div class="summary-value color-replacement">{{ $exchangeSummary->replacements ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.returns_count') }}</div>
            <div class="summary-value color-return">{{ $exchangeSummary->returns ?? 0 }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">{{ __('report.cost_difference') }}</div>
            <div class="summary-value
                @if($exchangeSummary->cost_difference > 0) color-positive
                @elseif($exchangeSummary->cost_difference < 0) color-negative
                @else color-neutral
                @endif
                ">
                @php
                    $totalDiff = (float)($exchangeSummary->cost_difference ?? 0);
                    $sign = $totalDiff > 0 ? '+' : '';
                @endphp
                {{ $sign }}{{ currency_symbol() }}{{ number_format($totalDiff, 2) }}
            </div>
        </div>
    </div>

    @if($exchanges->count() > 0)
    <table class="report-table">
        <thead>
            <tr>
                <th>{{ __('report.sale_reference') }}</th>
                <th>{{ __('report.shop') }}</th>
                <th>{{ __('report.product_name') }}</th>
                <th>{{ __('report.original_batch') }}</th>
                <th>{{ __('report.replacement_batch') }}</th>
                <th class="text-center">{{ __('sale.quantity') }}</th>
                <th>{{ __('sale.exchange_date') }}</th>
                <th>{{ __('sale.exchange_type') }}</th>
                <th class="text-end">{{ __('sale.exchange_cost_difference') }}</th>
                <th>{{ __('sale.reason') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exchanges as $exchange)
            @php
                $diffVal = (float)$exchange->cost_difference;
                $diffSign = $diffVal > 0 ? '+' : '';
            @endphp
            <tr>
                <td><strong>#{{ $exchange->sale_id }}</strong></td>
                <td>{{ $exchange->shop->name }}</td>
                <td>
                    {{ $exchange->originalBatch->product->name ?? ($exchange->sale->product->name ?? '-') }}
                    @if($exchange->originalBatch && $exchange->originalBatch->attribute_summary !== '-')
                        <br><span style="font-size: 8px; color: #64748b;">{{ $exchange->originalBatch->attribute_summary }}</span>
                    @endif
                </td>
                <td><code>{{ $exchange->originalBatch->batch_code ?? '-' }}</code></td>
                <td>
                    @if($exchange->exchange_type === 'replacement' && $exchange->replacementBatch)
                        <code>{{ $exchange->replacementBatch->batch_code }}</code>
                        @if($exchange->replacementBatch->product && $exchange->replacementBatch->product->id !== ($exchange->originalBatch->product->id ?? null))
                            <br><span style="font-size: 8px; color: #64748b;">({{ $exchange->replacementBatch->product->name }})</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td class="text-center" style="font-weight: bold;">{{ $exchange->quantity }}</td>
                <td>{{ $exchange->exchange_date->format('M d, Y') }}</td>
                <td class="@if($exchange->exchange_type === 'replacement') color-replacement @else color-return @endif" style="font-weight: bold;">
                    {{ __('sale.exchange_type_' . $exchange->exchange_type) }}
                </td>
                <td class="text-end
                    @if($diffVal > 0) color-positive
                    @elseif($diffVal < 0) color-negative
                    @else color-neutral
                    @endif
                    " style="font-weight: bold;">
                    {{ $diffSign }}{{ currency_symbol() }}{{ number_format($diffVal, 2) }}
                </td>
                <td>{{ ucfirst(str_replace('_', ' ', $exchange->reason)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center; color:#64748b; padding:30px 0;">{{ __('report.no_exchanges_found') }}</p>
    @endif

    <div class="footer">
        Byabsha Track &copy; {{ date('Y') }} &mdash; {{ __('report.pdf_footer') }}
    </div>
</body>
</html>
