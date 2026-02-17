<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Branch;
use Carbon\Carbon;

class ReportService
{
    /**
     * Calculate daily total sales for a specific date
     *
     * @param string $date Format: Y-m-d
     * @param int|null $branchId Optional branch filter
     * @return array
     */
    public function dailyTotalSales(string $date, ?int $branchId = null): array
    {
        $query = Sale::whereDate('sale_date', $date);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $result = $query->selectRaw('
            COUNT(*) as total_sales_count,
            SUM(subtotal) as total_subtotal,
            SUM(discount) as total_discount,
            SUM(tax) as total_tax,
            SUM(total) as total_amount,
            SUM(paid_amount) as total_paid,
            SUM(due_amount) as total_due
        ')->first();

        // Get payment method breakdown
        $paymentMethods = Sale::whereDate('sale_date', $date)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('
                payment_method,
                COUNT(*) as count,
                SUM(total) as amount
            ')
            ->groupBy('payment_method')
            ->get();

        // Get sales by status
        $paymentStatus = Sale::whereDate('sale_date', $date)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('
                payment_status,
                COUNT(*) as count,
                SUM(total) as amount
            ')
            ->groupBy('payment_status')
            ->get();

        return [
            'date' => $date,
            'branch_id' => $branchId,
            'summary' => [
                'total_sales_count' => (int) $result->total_sales_count,
                'total_subtotal' => (float) $result->total_subtotal,
                'total_discount' => (float) $result->total_discount,
                'total_tax' => (float) $result->total_tax,
                'total_amount' => (float) $result->total_amount,
                'total_paid' => (float) $result->total_paid,
                'total_due' => (float) $result->total_due,
            ],
            'payment_methods' => $paymentMethods->map(fn($item) => [
                'method' => $item->payment_method,
                'count' => (int) $item->count,
                'amount' => (float) $item->amount,
            ]),
            'payment_status' => $paymentStatus->map(fn($item) => [
                'status' => $item->payment_status,
                'count' => (int) $item->count,
                'amount' => (float) $item->amount,
            ]),
        ];
    }

    /**
     * Calculate daily total purchase cost for a specific date
     *
     * @param string $date Format: Y-m-d
     * @param int|null $branchId Optional branch filter
     * @return array
     */
    public function dailyTotalPurchaseCost(string $date, ?int $branchId = null): array
    {
        $query = Purchase::whereDate('purchase_date', $date);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $result = $query->selectRaw('
            COUNT(*) as total_purchases_count,
            SUM(subtotal) as total_subtotal,
            SUM(discount) as total_discount,
            SUM(tax) as total_tax,
            SUM(total) as total_amount,
            SUM(paid_amount) as total_paid,
            SUM(due_amount) as total_due
        ')->first();

        // Get top suppliers for the day
        $topSuppliers = Purchase::whereDate('purchase_date', $date)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->selectRaw('
                suppliers.id,
                suppliers.name,
                COUNT(purchases.id) as purchase_count,
                SUM(purchases.total) as total_amount
            ')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return [
            'date' => $date,
            'branch_id' => $branchId,
            'summary' => [
                'total_purchases_count' => (int) $result->total_purchases_count,
                'total_subtotal' => (float) $result->total_subtotal,
                'total_discount' => (float) $result->total_discount,
                'total_tax' => (float) $result->total_tax,
                'total_amount' => (float) $result->total_amount,
                'total_paid' => (float) $result->total_paid,
                'total_due' => (float) $result->total_due,
            ],
            'top_suppliers' => $topSuppliers->map(fn($item) => [
                'supplier_id' => $item->id,
                'supplier_name' => $item->name,
                'purchase_count' => (int) $item->purchase_count,
                'total_amount' => (float) $item->total_amount,
            ]),
        ];
    }

    /**
     * Calculate daily gross profit for a specific date
     *
     * @param string $date Format: Y-m-d
     * @param int|null $branchId Optional branch filter
     * @return array
     */
    public function dailyGrossProfit(string $date, ?int $branchId = null): array
    {
        // Get total sales revenue
        $salesQuery = Sale::whereDate('sale_date', $date);
        if ($branchId) {
            $salesQuery->where('branch_id', $branchId);
        }
        $salesRevenue = $salesQuery->sum('total');

        // Get total profit from sale items
        $profitQuery = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereDate('sales.sale_date', $date);
        if ($branchId) {
            $profitQuery->where('sales.branch_id', $branchId);
        }
        $totalProfit = $profitQuery->sum('sale_items.profit');

        // Get total expenses
        $expenseQuery = Expense::whereDate('expense_date', $date);
        if ($branchId) {
            $expenseQuery->where('branch_id', $branchId);
        }
        $totalExpenses = $expenseQuery->sum('amount');

        // Calculate net profit
        $netProfit = $totalProfit - $totalExpenses;

        // Get profit breakdown by product category
        $profitByCategory = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereDate('sales.sale_date', $date)
            ->when($branchId, fn($q) => $q->where('sales.branch_id', $branchId))
            ->selectRaw('
                categories.id as category_id,
                categories.name as category_name,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_sales,
                SUM(sale_items.profit) as total_profit
            ')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_profit')
            ->get();

        // Get expense breakdown by category
        $expensesByCategory = Expense::whereDate('expense_date', $date)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('
                category,
                COUNT(*) as count,
                SUM(amount) as total_amount
            ')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        return [
            'date' => $date,
            'branch_id' => $branchId,
            'summary' => [
                'total_sales_revenue' => (float) $salesRevenue,
                'total_gross_profit' => (float) $totalProfit,
                'total_expenses' => (float) $totalExpenses,
                'net_profit' => (float) $netProfit,
                'profit_margin' => $salesRevenue > 0 ? round(($totalProfit / $salesRevenue) * 100, 2) : 0,
                'net_profit_margin' => $salesRevenue > 0 ? round(($netProfit / $salesRevenue) * 100, 2) : 0,
            ],
            'profit_by_category' => $profitByCategory->map(fn($item) => [
                'category_id' => $item->category_id,
                'category_name' => $item->category_name,
                'total_quantity' => (float) $item->total_quantity,
                'total_sales' => (float) $item->total_sales,
                'total_profit' => (float) $item->total_profit,
                'profit_margin' => $item->total_sales > 0 ? round(($item->total_profit / $item->total_sales) * 100, 2) : 0,
            ]),
            'expenses_by_category' => $expensesByCategory->map(fn($item) => [
                'category' => $item->category ?? 'Uncategorized',
                'count' => (int) $item->count,
                'total_amount' => (float) $item->total_amount,
            ]),
        ];
    }

    /**
     * Generate monthly sales report
     *
     * @param int $year
     * @param int $month
     * @param int|null $branchId Optional branch filter
     * @return array
     */
    public function monthlySalesReport(int $year, int $month, ?int $branchId = null): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Monthly summary
        $summaryQuery = Sale::whereBetween('sale_date', [$startDate, $endDate]);
        if ($branchId) {
            $summaryQuery->where('branch_id', $branchId);
        }

        $summary = $summaryQuery->selectRaw('
            COUNT(*) as total_sales_count,
            SUM(subtotal) as total_subtotal,
            SUM(discount) as total_discount,
            SUM(tax) as total_tax,
            SUM(total) as total_amount,
            SUM(paid_amount) as total_paid,
            SUM(due_amount) as total_due
        ')->first();

        // Daily breakdown
        $dailyBreakdown = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('
                DATE(sale_date) as date,
                COUNT(*) as sales_count,
                SUM(total) as total_amount,
                SUM(paid_amount) as paid_amount,
                SUM(due_amount) as due_amount
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling products
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('sales.branch_id', $branchId))
            ->selectRaw('
                products.id,
                products.name,
                products.sku,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_revenue,
                SUM(sale_items.profit) as total_profit
            ')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Top customers by purchase amount
        $topCustomers = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereNotNull('customer_phone')
            ->selectRaw('
                customer_name,
                customer_phone,
                COUNT(*) as purchase_count,
                SUM(total) as total_spent
            ')
            ->groupBy('customer_name', 'customer_phone')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // Sales by payment method
        $paymentMethods = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->selectRaw('
                payment_method,
                COUNT(*) as count,
                SUM(total) as amount
            ')
            ->groupBy('payment_method')
            ->get();

        return [
            'year' => $year,
            'month' => $month,
            'month_name' => $startDate->format('F'),
            'branch_id' => $branchId,
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'total_days' => $startDate->daysInMonth,
            ],
            'summary' => [
                'total_sales_count' => (int) $summary->total_sales_count,
                'total_subtotal' => (float) $summary->total_subtotal,
                'total_discount' => (float) $summary->total_discount,
                'total_tax' => (float) $summary->total_tax,
                'total_amount' => (float) $summary->total_amount,
                'total_paid' => (float) $summary->total_paid,
                'total_due' => (float) $summary->total_due,
                'average_sale_value' => $summary->total_sales_count > 0
                    ? round($summary->total_amount / $summary->total_sales_count, 2)
                    : 0,
            ],
            'daily_breakdown' => $dailyBreakdown->map(fn($item) => [
                'date' => $item->date,
                'sales_count' => (int) $item->sales_count,
                'total_amount' => (float) $item->total_amount,
                'paid_amount' => (float) $item->paid_amount,
                'due_amount' => (float) $item->due_amount,
            ]),
            'top_products' => $topProducts->map(fn($item) => [
                'product_id' => $item->id,
                'product_name' => $item->name,
                'sku' => $item->sku,
                'total_quantity' => (float) $item->total_quantity,
                'total_revenue' => (float) $item->total_revenue,
                'total_profit' => (float) $item->total_profit,
            ]),
            'top_customers' => $topCustomers->map(fn($item) => [
                'customer_name' => $item->customer_name,
                'customer_phone' => $item->customer_phone,
                'purchase_count' => (int) $item->purchase_count,
                'total_spent' => (float) $item->total_spent,
            ]),
            'payment_methods' => $paymentMethods->map(fn($item) => [
                'method' => $item->payment_method,
                'count' => (int) $item->count,
                'amount' => (float) $item->amount,
            ]),
        ];
    }

    /**
     * Generate monthly profit summary per branch
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function monthlyProfitSummaryPerBranch(int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all branches
        $branches = Branch::where('is_active', true)->get();

        $branchReports = [];
        $totalSalesAllBranches = 0;
        $totalProfitAllBranches = 0;
        $totalExpensesAllBranches = 0;

        foreach ($branches as $branch) {
            // Sales for this branch
            $sales = Sale::where('branch_id', $branch->id)
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->selectRaw('
                    COUNT(*) as sales_count,
                    SUM(total) as total_revenue,
                    SUM(discount) as total_discount
                ')
                ->first();

            // Profit for this branch
            $profit = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.branch_id', $branch->id)
                ->whereBetween('sales.sale_date', [$startDate, $endDate])
                ->sum('sale_items.profit');

            // Expenses for this branch
            $expenses = Expense::where('branch_id', $branch->id)
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->selectRaw('
                    COUNT(*) as expense_count,
                    SUM(amount) as total_expenses
                ')
                ->first();

            // Purchases for this branch
            $purchases = Purchase::where('branch_id', $branch->id)
                ->whereBetween('purchase_date', [$startDate, $endDate])
                ->selectRaw('
                    COUNT(*) as purchase_count,
                    SUM(total) as total_purchase_cost
                ')
                ->first();

            $totalRevenue = (float) $sales->total_revenue;
            $totalProfit = (float) $profit;
            $totalExpense = (float) $expenses->total_expenses;
            $netProfit = $totalProfit - $totalExpense;

            $totalSalesAllBranches += $totalRevenue;
            $totalProfitAllBranches += $totalProfit;
            $totalExpensesAllBranches += $totalExpense;

            $branchReports[] = [
                'branch_id' => $branch->id,
                'branch_name' => $branch->name,
                'branch_code' => $branch->code,
                'sales' => [
                    'count' => (int) $sales->sales_count,
                    'total_revenue' => $totalRevenue,
                    'total_discount' => (float) $sales->total_discount,
                ],
                'purchases' => [
                    'count' => (int) $purchases->purchase_count,
                    'total_cost' => (float) $purchases->total_purchase_cost,
                ],
                'expenses' => [
                    'count' => (int) $expenses->expense_count,
                    'total_amount' => $totalExpense,
                ],
                'profit' => [
                    'gross_profit' => $totalProfit,
                    'net_profit' => $netProfit,
                    'gross_profit_margin' => $totalRevenue > 0
                        ? round(($totalProfit / $totalRevenue) * 100, 2)
                        : 0,
                    'net_profit_margin' => $totalRevenue > 0
                        ? round(($netProfit / $totalRevenue) * 100, 2)
                        : 0,
                ],
            ];
        }

        // Overall company summary
        $netProfitAllBranches = $totalProfitAllBranches - $totalExpensesAllBranches;

        return [
            'year' => $year,
            'month' => $month,
            'month_name' => $startDate->format('F'),
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'overall_summary' => [
                'total_branches' => count($branches),
                'total_revenue' => $totalSalesAllBranches,
                'total_gross_profit' => $totalProfitAllBranches,
                'total_expenses' => $totalExpensesAllBranches,
                'total_net_profit' => $netProfitAllBranches,
                'overall_gross_margin' => $totalSalesAllBranches > 0
                    ? round(($totalProfitAllBranches / $totalSalesAllBranches) * 100, 2)
                    : 0,
                'overall_net_margin' => $totalSalesAllBranches > 0
                    ? round(($netProfitAllBranches / $totalSalesAllBranches) * 100, 2)
                    : 0,
            ],
            'branches' => $branchReports,
        ];
    }

    /**
     * Get year-over-year comparison
     *
     * @param int $year
     * @param int $month
     * @param int|null $branchId
     * @return array
     */
    public function yearOverYearComparison(int $year, int $month, ?int $branchId = null): array
    {
        $currentPeriod = $this->monthlyProfitSummary($year, $month, $branchId);
        $previousPeriod = $this->monthlyProfitSummary($year - 1, $month, $branchId);

        $revenueGrowth = $previousPeriod['total_revenue'] > 0
            ? round((($currentPeriod['total_revenue'] - $previousPeriod['total_revenue']) / $previousPeriod['total_revenue']) * 100, 2)
            : 0;

        $profitGrowth = $previousPeriod['gross_profit'] > 0
            ? round((($currentPeriod['gross_profit'] - $previousPeriod['gross_profit']) / $previousPeriod['gross_profit']) * 100, 2)
            : 0;

        return [
            'current_period' => [
                'year' => $year,
                'month' => $month,
                'data' => $currentPeriod,
            ],
            'previous_period' => [
                'year' => $year - 1,
                'month' => $month,
                'data' => $previousPeriod,
            ],
            'growth' => [
                'revenue_growth_percentage' => $revenueGrowth,
                'profit_growth_percentage' => $profitGrowth,
                'revenue_difference' => $currentPeriod['total_revenue'] - $previousPeriod['total_revenue'],
                'profit_difference' => $currentPeriod['gross_profit'] - $previousPeriod['gross_profit'],
            ],
        ];
    }

    /**
     * Helper method for monthly profit summary
     *
     * @param int $year
     * @param int $month
     * @param int|null $branchId
     * @return array
     */
    private function monthlyProfitSummary(int $year, int $month, ?int $branchId = null): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $salesQuery = Sale::whereBetween('sale_date', [$startDate, $endDate]);
        if ($branchId) {
            $salesQuery->where('branch_id', $branchId);
        }
        $totalRevenue = $salesQuery->sum('total');

        $profitQuery = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate]);
        if ($branchId) {
            $profitQuery->where('sales.branch_id', $branchId);
        }
        $grossProfit = $profitQuery->sum('sale_items.profit');

        $expenseQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        if ($branchId) {
            $expenseQuery->where('branch_id', $branchId);
        }
        $totalExpenses = $expenseQuery->sum('amount');

        return [
            'total_revenue' => (float) $totalRevenue,
            'gross_profit' => (float) $grossProfit,
            'total_expenses' => (float) $totalExpenses,
            'net_profit' => (float) ($grossProfit - $totalExpenses),
        ];
    }
}
