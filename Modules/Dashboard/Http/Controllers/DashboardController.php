<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Dashboard\Services\DashboardService;
use Modules\Capital\Models\Capital;
use Modules\Sale\Models\Sale;

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
        $user = auth()->user();
        $shopMetrics = $this->dashboardService->getShopMetrics($user);
        $overallMetrics = $this->dashboardService->getOverallMetrics($user);

        return view('dashboard::index', compact('shopMetrics', 'overallMetrics'));
    }

    /**
     * Return shop details for modal AJAX request.
     */
    public function shopDetails($shopId)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $shopId), 403, 'You do not have access to this shop.');

        $shop = \Modules\Shop\Models\Shop::with(['products', 'sales'])->findOrFail($shopId);
        $capital = Capital::where('shop_id', $shopId)->first();
        $todaySales = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
        $todayProfit = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('profit');
        $monthlyProfit = Sale::where('shop_id', $shopId)
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
