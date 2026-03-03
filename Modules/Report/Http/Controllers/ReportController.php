<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Services\ReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        // Get filter parameters
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ];

        // Get report data
        $salesSummary = $this->reportService->getSalesSummary($filters);
        $salesByShop = $this->reportService->getSalesByShop($filters);
        $topProducts = $this->reportService->getTopSellingProducts($filters, 10);
        $stockSummary = $this->reportService->getStockSummary($filters);
        $dailySales = $this->reportService->getDailySales($filters);
        $shops = $this->reportService->getShops();

        return view('report::index', compact(
            'salesSummary',
            'salesByShop',
            'topProducts',
            'stockSummary',
            'dailySales',
            'shops',
            'filters'
        ));
    }

    public function sales(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ];

        $sales = $this->reportService->getPaginatedSales($filters);
        $salesSummary = $this->reportService->getSalesSummary($filters);
        $shops = $this->reportService->getShops();

        return view('report::sales', compact('sales', 'salesSummary', 'shops', 'filters'));
    }

    public function products(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
        ];

        $products = $this->reportService->getProductReport($filters);
        $stockSummary = $this->reportService->getStockSummary($filters);
        $shops = $this->reportService->getShops();

        return view('report::products', compact('products', 'stockSummary', 'shops', 'filters'));
    }

    public function shops(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ];

        $shopData = $this->reportService->getShopComparison($filters);
        $shops = $this->reportService->getShops();

        return view('report::shops', compact('shopData', 'shops', 'filters'));
    }

    public function daily(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'month' => $request->input('month', now()->format('Y-m')),
        ];

        $dailyData = $this->reportService->getDailyProfitLoss($filters);
        $shops = $this->reportService->getShops();

        return view('report::daily', compact('dailyData', 'shops', 'filters'));
    }

    public function monthly(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'year' => $request->input('year', now()->format('Y')),
        ];

        $monthlyData = $this->reportService->getMonthlyProfitLoss($filters);
        $shops = $this->reportService->getShops();

        return view('report::monthly', compact('monthlyData', 'shops', 'filters'));
    }

    public function exportDailyPdf(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'month' => $request->input('month', now()->format('Y-m')),
        ];

        $dailyData = $this->reportService->getDailyProfitLoss($filters);
        $shops = $this->reportService->getShops();
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.daily-pdf', compact('dailyData', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'daily-report-' . $filters['month'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportMonthlyPdf(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'year' => $request->input('year', now()->format('Y')),
        ];

        $monthlyData = $this->reportService->getMonthlyProfitLoss($filters);
        $shops = $this->reportService->getShops();
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.monthly-pdf', compact('monthlyData', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'monthly-report-' . $filters['year'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportSalesPdf(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ];

        $sales = $this->reportService->getPaginatedSales($filters, 1000);
        $salesSummary = $this->reportService->getSalesSummary($filters);
        $shops = $this->reportService->getShops();
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.sales-pdf', compact('sales', 'salesSummary', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'sales-report-' . $filters['start_date'] . '-to-' . $filters['end_date'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportProductsPdf(Request $request)
    {
        $filters = [
            'shop_id' => $request->input('shop_id'),
        ];

        $products = $this->reportService->getProductReport($filters);
        $stockSummary = $this->reportService->getStockSummary($filters);
        $shops = $this->reportService->getShops();
        $shopName = $filters['shop_id']
            ? ($shops->firstWhere('id', $filters['shop_id'])->name ?? __('report.all_shops'))
            : __('report.all_shops');

        $pdf = Pdf::loadView('report::pdf.products-pdf', compact('products', 'stockSummary', 'shops', 'filters', 'shopName'))
            ->setPaper('a4', 'landscape');

        $filename = 'products-report-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportShopsPdf(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
        ];

        $shopData = $this->reportService->getShopComparison($filters);
        $shops = $this->reportService->getShops();

        $pdf = Pdf::loadView('report::pdf.shops-pdf', compact('shopData', 'shops', 'filters'))
            ->setPaper('a4', 'landscape');

        $filename = 'shops-report-' . $filters['start_date'] . '-to-' . $filters['end_date'] . '.pdf';

        return $pdf->download($filename);
    }
}
