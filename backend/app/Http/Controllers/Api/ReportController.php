<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get daily profit report (optimized with ReportService)
     */
    public function dailyProfit(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        $data = $this->reportService->dailyGrossProfit($date, $branchId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get monthly profit report (optimized with ReportService)
     */
    public function monthlyProfit(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);
        $branchId = $request->input('branch_id');

        // Get monthly sales report which includes profit data
        $data = $this->reportService->monthlySalesReport($year, $month, $branchId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get sales summary (optimized)
     */
    public function salesSummary(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        $query = Sale::whereBetween('sale_date', [$startDate, $endDate]);
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $summary = $query->selectRaw('
            COUNT(*) as sales_count,
            SUM(total) as total_sales,
            SUM(paid_amount) as total_paid,
            SUM(due_amount) as total_due
        ')->first();

        $paymentStatus = $query->selectRaw('
            payment_status,
            COUNT(*) as count
        ')->groupBy('payment_status')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_sales' => (float) $summary->total_sales,
                'total_paid' => (float) $summary->total_paid,
                'total_due' => (float) $summary->total_due,
                'sales_count' => (int) $summary->sales_count,
                'by_payment_status' => $paymentStatus->mapWithKeys(fn($item) => [
                    $item->payment_status => (int) $item->count
                ]),
            ],
        ]);
    }

    /**
     * Get purchase summary (optimized)
     */
    public function purchaseSummary(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        // Use the dailyTotalPurchaseCost from ReportService for consistency
        $data = $this->reportService->dailyTotalPurchaseCost($startDate, $branchId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get top selling products (optimized)
     */
    public function topSellingProducts(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');
        $limit = (int) $request->input('limit', 10);

        $query = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('sales.branch_id', $branchId))
            ->selectRaw('
                products.id,
                products.name,
                products.sku,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_sales,
                SUM(sale_items.profit) as total_profit
            ')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'products' => $query->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'total_quantity' => (float) $item->total_quantity,
                    'total_sales' => (float) $item->total_sales,
                    'total_profit' => (float) $item->total_profit,
                ]),
            ],
        ]);
    }

    /**
     * Get dashboard statistics (optimized with ReportService)
     */
    public function dashboard(Request $request): JsonResponse
    {
        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $branchId = $request->input('branch_id');
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // Use ReportService for daily metrics
        $dailyProfit = $this->reportService->dailyGrossProfit($date, $branchId);
        $dailySales = $this->reportService->dailyTotalSales($date, $branchId);

        // This month's sales (optimized query)
        $monthSalesQuery = Sale::whereMonth('sale_date', $thisMonth)
            ->whereYear('sale_date', $thisYear);
        if ($branchId) {
            $monthSalesQuery->where('branch_id', $branchId);
        }
        $monthSales = $monthSalesQuery->sum('total');

        // Total products
        $totalProducts = DB::table('products')->where('is_active', true)->count();

        // Low stock products (optimized)
        $lowStockQuery = DB::table('branch_stocks')
            ->join('products', 'branch_stocks.product_id', '=', 'products.id')
            ->whereRaw('branch_stocks.quantity <= products.minimum_stock');
        if ($branchId) {
            $lowStockQuery->where('branch_stocks.branch_id', $branchId);
        }
        $lowStockCount = $lowStockQuery->count();

        // Pending dues (optimized)
        $purchaseDuesQuery = Purchase::where('payment_status', '!=', 'paid');
        if ($branchId) {
            $purchaseDuesQuery->where('branch_id', $branchId);
        }
        $purchaseDues = $purchaseDuesQuery->sum('due_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'today_sales' => $dailySales['summary']['total_amount'],
                'month_sales' => (float) $monthSales,
                'today_profit' => $dailyProfit['summary']['total_gross_profit'],
                'total_products' => (int) $totalProducts,
                'low_stock_count' => (int) $lowStockCount,
                'purchase_dues' => (float) $purchaseDues,
                'today_sales_count' => $dailySales['summary']['total_sales_count'],
                'today_expenses' => $dailyProfit['summary']['total_expenses'],
                'today_net_profit' => $dailyProfit['summary']['net_profit'],
            ],
        ]);
    }

    /**
     * Get daily total sales (new endpoint using ReportService)
     */
    public function dailySales(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data = $this->reportService->dailyTotalSales(
            $request->input('date'),
            $request->input('branch_id')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get daily total purchase cost (new endpoint using ReportService)
     */
    public function dailyPurchases(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data = $this->reportService->dailyTotalPurchaseCost(
            $request->input('date'),
            $request->input('branch_id')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get monthly sales report (new endpoint using ReportService)
     */
    public function monthlySales(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data = $this->reportService->monthlySalesReport(
            (int) $request->input('year'),
            (int) $request->input('month'),
            $request->input('branch_id')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get monthly profit summary per branch (new endpoint using ReportService)
     */
    public function monthlyProfitPerBranch(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $data = $this->reportService->monthlyProfitSummaryPerBranch(
            (int) $request->input('year'),
            (int) $request->input('month')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get year-over-year comparison (new endpoint using ReportService)
     */
    public function yearOverYearComparison(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2001|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $data = $this->reportService->yearOverYearComparison(
            (int) $request->input('year'),
            (int) $request->input('month'),
            $request->input('branch_id')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
