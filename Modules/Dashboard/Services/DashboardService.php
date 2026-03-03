<?php

namespace Modules\Dashboard\Services;

use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;
use Modules\Capital\Models\Capital;
use Modules\Capital\Services\CapitalService;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }
    public function getShopMetrics()
    {
        $shops = Shop::all();
        $metrics = [];

        foreach ($shops as $shop) {
            $metrics[] = [
                'shop' => $shop,
                'today_sales' => $this->getTodaySales($shop->id),
                'today_profit' => $this->getTodayProfit($shop->id),
                'monthly_profit' => $this->getMonthlyProfit($shop->id),
                'total_capital' => $this->getTotalCapital($shop->id),
            ];
        }

        return $metrics;
    }

    public function getTodaySales($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
    }

    public function getTodayProfit($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('profit');
    }

    public function getMonthlyProfit($shopId)
    {
        return Sale::where('shop_id', $shopId)
            ->whereYear('sale_date', now()->year)
            ->whereMonth('sale_date', now()->month)
            ->sum('profit');
    }

    public function getTotalCapital($shopId)
    {
        // Check if capital record exists
        $capital = Capital::where('shop_id', $shopId)->first();

        // If not exists, calculate and create it
        if (!$capital) {
            $this->capitalService->updateShopCapital($shopId);
            $capital = Capital::where('shop_id', $shopId)->first();
        }

        return $capital ? $capital->total_capital : 0;
    }

    public function getOverallMetrics()
    {
        return [
            'total_shops' => Shop::count(),
            'total_products' => Product::count(),
            'total_sales_today' => Sale::whereDate('sale_date', today())->count(),
            'total_revenue_today' => Sale::whereDate('sale_date', today())->sum('total_amount'),
            'total_profit_today' => Sale::whereDate('sale_date', today())->sum('profit'),
            'low_stock_count' => Product::where('stock_quantity', '<=', 5)->count(),
        ];
    }
}
