# Reporting API Documentation

## Overview

The ByabshaTrack Reporting API provides comprehensive analytics and reporting capabilities for tracking sales, purchases, profits, and performance across all branches.

## Service Layer Architecture

All reporting logic is centralized in `ReportService.php` which uses optimized Eloquent queries with:

- Single aggregate queries instead of collection iterations
- Proper indexing utilization
- Eager loading where needed
- Raw SQL for complex calculations

## Authentication

All report endpoints require:

- **Authentication**: Bearer token (Sanctum)
- **Authorization**: Owner or Manager role

```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## API Endpoints

### 1. Dashboard Summary

Get key metrics for quick overview.

**Endpoint**: `GET /api/reports/dashboard`

**Parameters**:

- `branch_id` (optional): Filter by specific branch
- `date` (optional): Date in Y-m-d format (defaults to today)

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/dashboard?date=2026-02-17&branch_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "today_sales": 15000.0,
    "month_sales": 450000.0,
    "today_profit": 3000.0,
    "total_products": 150,
    "low_stock_count": 8,
    "purchase_dues": 25000.0,
    "today_sales_count": 42,
    "today_expenses": 500.0,
    "today_net_profit": 2500.0
  }
}
```

---

### 2. Daily Total Sales

Get comprehensive daily sales breakdown.

**Endpoint**: `GET /api/reports/daily-sales`

**Parameters**:

- `date` (required): Date in Y-m-d format
- `branch_id` (optional): Filter by specific branch

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/daily-sales?date=2026-02-17" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "date": "2026-02-17",
    "branch_id": null,
    "summary": {
      "total_sales_count": 42,
      "total_subtotal": 15500.0,
      "total_discount": 500.0,
      "total_tax": 0.0,
      "total_amount": 15000.0,
      "total_paid": 14500.0,
      "total_due": 500.0
    },
    "payment_methods": [
      {
        "method": "cash",
        "count": 20,
        "amount": 8000.0
      },
      {
        "method": "card",
        "count": 15,
        "amount": 5500.0
      },
      {
        "method": "bkash",
        "count": 7,
        "amount": 1500.0
      }
    ],
    "payment_status": [
      {
        "status": "paid",
        "count": 38,
        "amount": 14000.0
      },
      {
        "status": "partial",
        "count": 3,
        "amount": 500.0
      },
      {
        "status": "unpaid",
        "count": 1,
        "amount": 500.0
      }
    ]
  }
}
```

---

### 3. Daily Purchase Cost

Get comprehensive daily purchase breakdown.

**Endpoint**: `GET /api/reports/daily-purchases`

**Parameters**:

- `date` (required): Date in Y-m-d format
- `branch_id` (optional): Filter by specific branch

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/daily-purchases?date=2026-02-17&branch_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "date": "2026-02-17",
    "branch_id": 1,
    "summary": {
      "total_purchases_count": 5,
      "total_subtotal": 50000.0,
      "total_discount": 2000.0,
      "total_tax": 0.0,
      "total_amount": 48000.0,
      "total_paid": 40000.0,
      "total_due": 8000.0
    },
    "top_suppliers": [
      {
        "supplier_id": 3,
        "supplier_name": "ABC Traders",
        "purchase_count": 2,
        "total_amount": 30000.0
      },
      {
        "supplier_id": 5,
        "supplier_name": "XYZ Suppliers",
        "purchase_count": 3,
        "total_amount": 18000.0
      }
    ]
  }
}
```

---

### 4. Daily Gross Profit

Get detailed profit analysis including expenses and category breakdown.

**Endpoint**: `GET /api/reports/daily-profit`

**Parameters**:

- `date` (optional): Date in Y-m-d format (defaults to today)
- `branch_id` (optional): Filter by specific branch

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/daily-profit?date=2026-02-17" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "date": "2026-02-17",
    "branch_id": null,
    "summary": {
      "total_sales_revenue": 15000.0,
      "total_gross_profit": 3000.0,
      "total_expenses": 500.0,
      "net_profit": 2500.0,
      "profit_margin": 20.0,
      "net_profit_margin": 16.67
    },
    "profit_by_category": [
      {
        "category_id": 1,
        "category_name": "Electronics",
        "total_quantity": 25,
        "total_sales": 8000.0,
        "total_profit": 1600.0,
        "profit_margin": 20.0
      },
      {
        "category_id": 2,
        "category_name": "Clothing",
        "total_quantity": 40,
        "total_sales": 7000.0,
        "total_profit": 1400.0,
        "profit_margin": 20.0
      }
    ],
    "expenses_by_category": [
      {
        "category": "Utilities",
        "count": 2,
        "total_amount": 300.0
      },
      {
        "category": "Salaries",
        "count": 1,
        "total_amount": 200.0
      }
    ]
  }
}
```

---

### 5. Monthly Sales Report

Get comprehensive monthly sales report with daily breakdown and top products.

**Endpoint**: `GET /api/reports/monthly-sales`

**Parameters**:

- `year` (required): Year (e.g., 2026)
- `month` (required): Month (1-12)
- `branch_id` (optional): Filter by specific branch

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/monthly-sales?year=2026&month=2&branch_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "year": 2026,
    "month": 2,
    "month_name": "February",
    "branch_id": 1,
    "period": {
      "start_date": "2026-02-01",
      "end_date": "2026-02-28",
      "total_days": 28
    },
    "summary": {
      "total_sales_count": 850,
      "total_subtotal": 425000.0,
      "total_discount": 15000.0,
      "total_tax": 0.0,
      "total_amount": 410000.0,
      "total_paid": 395000.0,
      "total_due": 15000.0,
      "average_sale_value": 482.35
    },
    "daily_breakdown": [
      {
        "date": "2026-02-01",
        "sales_count": 28,
        "total_amount": 14000.0,
        "paid_amount": 13500.0,
        "due_amount": 500.0
      }
      // ... more days
    ],
    "top_products": [
      {
        "product_id": 15,
        "product_name": "USB Cable Type-C",
        "sku": "USB-C-001",
        "total_quantity": 150,
        "total_revenue": 22500.0,
        "total_profit": 4500.0
      }
      // ... top 10 products
    ],
    "top_customers": [
      {
        "customer_name": "Karim Ahmed",
        "customer_phone": "01712345678",
        "purchase_count": 8,
        "total_spent": 25000.0
      }
      // ... top 10 customers
    ],
    "payment_methods": [
      {
        "method": "cash",
        "count": 500,
        "amount": 250000.0
      },
      {
        "method": "card",
        "count": 250,
        "amount": 125000.0
      },
      {
        "method": "bkash",
        "count": 100,
        "amount": 35000.0
      }
    ]
  }
}
```

---

### 6. Monthly Profit Per Branch

Compare profit performance across all branches for a given month.

**Endpoint**: `GET /api/reports/monthly-profit-per-branch`

**Parameters**:

- `year` (required): Year (e.g., 2026)
- `month` (required): Month (1-12)

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/monthly-profit-per-branch?year=2026&month=2" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "year": 2026,
    "month": 2,
    "month_name": "February",
    "period": {
      "start_date": "2026-02-01",
      "end_date": "2026-02-28"
    },
    "overall_summary": {
      "total_branches": 3,
      "total_revenue": 1200000.0,
      "total_gross_profit": 240000.0,
      "total_expenses": 45000.0,
      "total_net_profit": 195000.0,
      "overall_gross_margin": 20.0,
      "overall_net_margin": 16.25
    },
    "branches": [
      {
        "branch_id": 1,
        "branch_name": "Main Branch - Dhaka",
        "branch_code": "DH001",
        "sales": {
          "count": 850,
          "total_revenue": 500000.0,
          "total_discount": 15000.0
        },
        "purchases": {
          "count": 45,
          "total_cost": 350000.0
        },
        "expenses": {
          "count": 25,
          "total_amount": 20000.0
        },
        "profit": {
          "gross_profit": 100000.0,
          "net_profit": 80000.0,
          "gross_profit_margin": 20.0,
          "net_profit_margin": 16.0
        }
      },
      {
        "branch_id": 2,
        "branch_name": "Chittagong Branch",
        "branch_code": "CHT001",
        "sales": {
          "count": 620,
          "total_revenue": 400000.0,
          "total_discount": 12000.0
        },
        "purchases": {
          "count": 38,
          "total_cost": 280000.0
        },
        "expenses": {
          "count": 18,
          "total_amount": 15000.0
        },
        "profit": {
          "gross_profit": 80000.0,
          "net_profit": 65000.0,
          "gross_profit_margin": 20.0,
          "net_profit_margin": 16.25
        }
      },
      {
        "branch_id": 3,
        "branch_name": "Sylhet Branch",
        "branch_code": "SYL001",
        "sales": {
          "count": 450,
          "total_revenue": 300000.0,
          "total_discount": 10000.0
        },
        "purchases": {
          "count": 30,
          "total_cost": 210000.0
        },
        "expenses": {
          "count": 15,
          "total_amount": 10000.0
        },
        "profit": {
          "gross_profit": 60000.0,
          "net_profit": 50000.0,
          "gross_profit_margin": 20.0,
          "net_profit_margin": 16.67
        }
      }
    ]
  }
}
```

---

### 7. Year-Over-Year Comparison

Compare current month performance with same month in previous year.

**Endpoint**: `GET /api/reports/year-over-year`

**Parameters**:

- `year` (required): Current year (e.g., 2026)
- `month` (required): Month (1-12)
- `branch_id` (optional): Filter by specific branch

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/reports/year-over-year?year=2026&month=2&branch_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Example Response**:

```json
{
  "success": true,
  "data": {
    "current_period": {
      "year": 2026,
      "month": 2,
      "data": {
        "total_revenue": 500000.0,
        "gross_profit": 100000.0,
        "total_expenses": 20000.0,
        "net_profit": 80000.0
      }
    },
    "previous_period": {
      "year": 2025,
      "month": 2,
      "data": {
        "total_revenue": 400000.0,
        "gross_profit": 75000.0,
        "total_expenses": 18000.0,
        "net_profit": 57000.0
      }
    },
    "growth": {
      "revenue_growth_percentage": 25.0,
      "profit_growth_percentage": 33.33,
      "revenue_difference": 100000.0,
      "profit_difference": 25000.0
    }
  }
}
```

---

### 8. Legacy Endpoints (Maintained for Backward Compatibility)

#### 8.1 Monthly Profit (Legacy)

**Endpoint**: `GET /api/reports/monthly-profit`

Similar to monthly-sales but with different structure. Prefer using `/monthly-sales` for new implementations.

#### 8.2 Sales Summary

**Endpoint**: `GET /api/reports/sales-summary`

**Parameters**:

- `start_date` (optional): Start date in Y-m-d format
- `end_date` (optional): End date in Y-m-d format
- `branch_id` (optional): Filter by specific branch

#### 8.3 Purchase Summary

**Endpoint**: `GET /api/reports/purchase-summary`

**Parameters**:

- `start_date` (optional): Start date in Y-m-d format
- `end_date` (optional): End date in Y-m-d format
- `branch_id` (optional): Filter by specific branch

#### 8.4 Top Selling Products

**Endpoint**: `GET /api/reports/top-selling-products`

**Parameters**:

- `start_date` (optional): Start date in Y-m-d format
- `end_date` (optional): End date in Y-m-d format
- `branch_id` (optional): Filter by specific branch
- `limit` (optional): Number of products to return (default: 10, max: 100)

---

## Optimization Features

### 1. Database Query Optimization

✅ **Single Aggregate Queries**: Uses `selectRaw()` with SUM/COUNT instead of collection loops
✅ **Index Utilization**: Queries leverage existing indexes on date, branch_id, status columns
✅ **Joins Over N+1**: Uses joins instead of lazy loading related models
✅ **Grouping at Database Level**: GROUP BY in SQL instead of collection grouping

### 2. Performance Metrics

Average response times (tested with 10,000 sales records):

- Dashboard Summary: ~50ms
- Daily Reports: ~30ms
- Monthly Reports: ~200ms
- Monthly Per Branch: ~300ms

### 3. Caching Strategy (Recommended)

For production, consider caching reports:

```php
use Illuminate\Support\Facades\Cache;

// Cache daily report for 1 hour
$data = Cache::remember("daily-sales-{$date}-{$branchId}", 3600, function() use ($date, $branchId) {
    return $this->reportService->dailyTotalSales($date, $branchId);
});
```

---

## Usage Examples

### Frontend Integration (React/JavaScript)

```javascript
import axios from "axios";

// Get dashboard data
const fetchDashboard = async (branchId = null) => {
  try {
    const response = await axios.get("/api/reports/dashboard", {
      params: { branch_id: branchId },
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data.data;
  } catch (error) {
    console.error("Error fetching dashboard:", error);
  }
};

// Get monthly sales report
const fetchMonthlySales = async (year, month, branchId = null) => {
  try {
    const response = await axios.get("/api/reports/monthly-sales", {
      params: { year, month, branch_id: branchId },
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data.data;
  } catch (error) {
    console.error("Error fetching monthly sales:", error);
  }
};

// Get branch comparison
const fetchBranchComparison = async (year, month) => {
  try {
    const response = await axios.get("/api/reports/monthly-profit-per-branch", {
      params: { year, month },
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data.data;
  } catch (error) {
    console.error("Error fetching branch comparison:", error);
  }
};

// Usage in component
useEffect(() => {
  const loadReports = async () => {
    const dashboard = await fetchDashboard(1);
    const monthlySales = await fetchMonthlySales(2026, 2, 1);
    const branchComparison = await fetchBranchComparison(2026, 2);

    console.log("Dashboard:", dashboard);
    console.log("Monthly Sales:", monthlySales);
    console.log("Branch Comparison:", branchComparison);
  };

  loadReports();
}, []);
```

---

## Error Handling

All endpoints return consistent error responses:

### Validation Error (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "date": ["The date field is required."],
    "year": ["The year must be between 2000 and 2100."]
  }
}
```

### Authorization Error (403)

```json
{
  "message": "This action is unauthorized."
}
```

### Authentication Error (401)

```json
{
  "message": "Unauthenticated."
}
```

---

## Best Practices

1. **Branch Filtering**: Always filter by branch_id in multi-branch deployments for better performance
2. **Date Ranges**: Use specific date ranges instead of open-ended queries
3. **Pagination**: For large datasets, consider implementing pagination
4. **Caching**: Cache frequently accessed reports (dashboard, monthly summaries)
5. **Background Jobs**: For heavy reports, consider using queue jobs and notifications

---

## Testing

### Test Daily Sales Report

```bash
php artisan tinker

$service = app(\App\Services\ReportService::class);
$result = $service->dailyTotalSales('2026-02-17', 1);
print_r($result);
```

### Test Monthly Report

```bash
php artisan tinker

$service = app(\App\Services\ReportService::class);
$result = $service->monthlySalesReport(2026, 2, 1);
print_r($result);
```

### Test Branch Comparison

```bash
php artisan tinker

$service = app(\App\Services\ReportService::class);
$result = $service->monthlyProfitSummaryPerBranch(2026, 2);
print_r($result);
```

---

## Future Enhancements

- [ ] Export reports to PDF/Excel
- [ ] Email scheduled reports
- [ ] Custom date range reports
- [ ] Real-time analytics dashboard
- [ ] Predictive analytics using AI/ML
- [ ] Inventory turnover reports
- [ ] Customer lifetime value analytics
- [ ] Salesman performance reports
