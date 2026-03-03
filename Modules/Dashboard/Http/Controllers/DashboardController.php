<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Dashboard\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with statistics for each shop.
     */
    public function index()
    {
        $shopMetrics = $this->dashboardService->getShopMetrics();
        $overallMetrics = $this->dashboardService->getOverallMetrics();

        return view('dashboard::index', compact('shopMetrics', 'overallMetrics'));
    }

    /**
     * Return shop details for modal AJAX request.
     */
    public function shopDetails($shopId)
    {
        $shop = \Modules\Shop\Models\Shop::with(['products', 'sales'])->findOrFail($shopId);
        $capital = \Modules\Capital\Models\Capital::where('shop_id', $shopId)->first();
        $todaySales = \Modules\Sale\Models\Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
        $todayProfit = \Modules\Sale\Models\Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('profit');
        $monthlyProfit = \Modules\Sale\Models\Sale::where('shop_id', $shopId)
            ->whereYear('sale_date', now()->year)
            ->whereMonth('sale_date', now()->month)
            ->sum('profit');
        return view('dashboard::partials.shop-details', [
            'shop' => $shop,
            'capital' => $capital,
            'todaySales' => $todaySales,
            'todayProfit' => $todayProfit,
            'monthlyProfit' => $monthlyProfit,
        ]);
    }
}
