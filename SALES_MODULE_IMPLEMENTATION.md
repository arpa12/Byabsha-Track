# Sales Module - Implementation Guide

## 📦 What Has Been Implemented

### ✅ Backend (Already Exists)

The backend for the Sales module is **fully functional** with the following endpoints:

#### API Endpoints

1. **GET /api/sales** - List all sales with pagination and filters

   ```php
   // SaleController.php
   public function index(Request $request): JsonResponse
   {
       // Supports filters:
       // - branch_id
       // - user_id
       // - start_date, end_date
       // - payment_status (paid, partial, unpaid)
       // - search (invoice, customer name, phone)

       // Returns paginated sales with relationships:
       // - branch
       // - user (salesman)
       // - items.product
   }
   ```

2. **GET /api/sales/{id}** - Get single sale details

   ```php
   public function show(Sale $sale): JsonResponse
   {
       // Returns complete sale info with:
       // - All sale items
       // - Product details
       // - Branch information
       // - Salesman information
   }
   ```

3. **PUT /api/sales/{id}** - Update sale (limited fields)

   ```php
   public function update(Request $request, Sale $sale): JsonResponse
   {
       // Allows updating:
       // - note
       // - payment_status
   }
   ```

4. **DELETE /api/sales/{id}** - Delete sale (owner only)
   ```php
   public function destroy(Sale $sale): JsonResponse
   {
       // Reverses stock deductions
       // Uses database transactions
       // Soft deletes the sale
   }
   ```

### ✅ Frontend Service Layer

**Location**: `frontend/src/services/saleService.js`

```javascript
// Complete sales service with all operations

export const getAllSales = async (params = {}) => { ... };
export const getSaleById = async (id) => { ... };
export const deleteSale = async (id) => { ... };
export const updateSale = async (id, data) => { ... };
export const getSalesStats = async (params = {}) => { ... };
export const exportSalesToCSV = async (params = {}) => { ... };
export const searchSales = async (query, additionalParams = {}) => { ... };
export const getCustomerHistory = async (customerPhone) => { ... };
export const processSaleReturn = async (saleId, returnData) => { ... };
```

### ✅ Frontend Sales Page

**Location**: `frontend/src/pages/Sales.jsx`

The Sales page includes:

1. **Sidebar Navigation** ✅
2. **Header with User Menu** ✅
3. **Basic Filtering** ✅
   - Date range picker
   - Payment status filter
4. **Sales Table** ✅
   - Invoice number
   - Sale date
   - Customer info
   - Branch name
   - Amounts (subtotal, discount, tax, total)
   - Payment status badges
   - Actions (view details)
5. **Pagination** ✅
6. **Invoice Detail Modal** ✅
7. **Responsive Design** ✅
8. **Bilingual Support** (English/Bengali) ✅

---

## 🚀 Enhancement Implementation Guide

To implement the full design from the business requirements document, follow these steps:

### Phase 1: Enhanced Filtering & Search

#### 1. Add Search Functionality

**Update Sales.jsx** - Add search state and handler:

```jsx
// Add to state
const [searchQuery, setSearchQuery] = useState("");
const searchTimeoutRef = useRef(null);

// Add search handler with debounce
const handleSearch = (value) => {
  setSearchQuery(value);

  if (searchTimeoutRef.current) {
    clearTimeout(searchTimeoutRef.current);
  }

  searchTimeoutRef.current = setTimeout(() => {
    setFilters({ ...filters, search: value });
  }, 500);
};

// Add search box to UI
<div className="search-box">
  <input
    type="text"
    placeholder="Search by invoice, customer, phone..."
    value={searchQuery}
    onChange={(e) => handleSearch(e.target.value)}
  />
</div>;
```

#### 2. Add Statistics Cards

```jsx
// Add statistics state
const [statistics, setStatistics] = useState({
  total_sales: 0,
  total_amount: 0,
  paid_amount: 0,
  due_amount: 0,
});

// Calculate from sales data
const calculateStatistics = (salesData) => {
  const stats = salesData.reduce(
    (acc, sale) => {
      acc.total_sales += 1;
      acc.total_amount += parseFloat(sale.total || 0);
      acc.paid_amount += parseFloat(sale.paid_amount || 0);
      acc.due_amount += parseFloat(sale.due_amount || 0);
      return acc;
    },
    { total_sales: 0, total_amount: 0, paid_amount: 0, due_amount: 0 },
  );

  setStatistics(stats);
};

// Add stats cards UI
<div className="stats-grid">
  <div className="stat-card">
    <div className="stat-label">Total Sales</div>
    <div className="stat-value">{statistics.total_sales}</div>
  </div>
  <div className="stat-card">
    <div className="stat-label">Total Amount</div>
    <div className="stat-value">৳{statistics.total_amount.toFixed(2)}</div>
  </div>
  <div className="stat-card">
    <div className="stat-label">Paid Amount</div>
    <div className="stat-value">৳{statistics.paid_amount.toFixed(2)}</div>
  </div>
  <div className="stat-card">
    <div className="stat-label">Due Amount</div>
    <div className="stat-value">৳{statistics.due_amount.toFixed(2)}</div>
  </div>
</div>;
```

#### 3. Add Date Presets

```jsx
const [datePreset, setDatePreset] = useState("all");

const handleDatePreset = (preset) => {
  setDatePreset(preset);
  const today = new Date();
  let startDate = "";
  let endDate = today.toISOString().split("T")[0];

  switch (preset) {
    case "today":
      startDate = endDate;
      break;
    case "last7":
      const last7 = new Date(today);
      last7.setDate(last7.getDate() - 7);
      startDate = last7.toISOString().split("T")[0];
      break;
    case "last30":
      const last30 = new Date(today);
      last30.setDate(last30.getDate() - 30);
      startDate = last30.toISOString().split("T")[0];
      break;
    // ... more presets
  }

  setFilters({ ...filters, start_date: startDate, end_date: endDate });
};

// Add preset buttons
<div className="date-presets">
  <button onClick={() => handleDatePreset("today")}>Today</button>
  <button onClick={() => handleDatePreset("last7")}>Last 7 Days</button>
  <button onClick={() => handleDatePreset("last30")}>Last 30 Days</button>
  <button onClick={() => handleDatePreset("thisMonth")}>This Month</button>
</div>;
```

#### 4. Add Advanced Filters Panel

```jsx
const [showFilters, setShowFilters] = useState(false);

// Add more filter fields
const [filters, setFilters] = useState({
  search: "",
  branch_id: "",
  payment_status: "",
  payment_method: "",
  start_date: "",
  end_date: "",
  user_id: "", // Salesman filter
  min_amount: "",
  max_amount: "",
});

// Add filter panel UI
{
  showFilters && (
    <div className="filters-panel">
      <div className="filter-grid">
        {/* Branch Filter (Owner/Manager) */}
        {(user?.role === "owner" || user?.role === "manager") && (
          <select
            value={filters.branch_id}
            onChange={(e) =>
              setFilters({ ...filters, branch_id: e.target.value })
            }
          >
            <option value="">All Branches</option>
            {branches.map((branch) => (
              <option key={branch.id} value={branch.id}>
                {branch.name}
              </option>
            ))}
          </select>
        )}

        {/* Payment Method Filter */}
        <select
          value={filters.payment_method}
          onChange={(e) =>
            setFilters({ ...filters, payment_method: e.target.value })
          }
        >
          <option value="">All Methods</option>
          <option value="cash">Cash</option>
          <option value="card">Card</option>
          <option value="bkash">bKash</option>
          <option value="mobile_banking">Mobile Banking</option>
        </select>

        {/* Amount Range */}
        <input
          type="number"
          placeholder="Min Amount"
          value={filters.min_amount}
          onChange={(e) =>
            setFilters({ ...filters, min_amount: e.target.value })
          }
        />
        <input
          type="number"
          placeholder="Max Amount"
          value={filters.max_amount}
          onChange={(e) =>
            setFilters({ ...filters, max_amount: e.target.value })
          }
        />
      </div>

      <button
        onClick={() =>
          setFilters({
            /* reset */
          })
        }
      >
        Clear Filters
      </button>
    </div>
  );
}
```

### Phase 2: Enhanced Table

#### 5. Add More Columns to Table

```jsx
<table className="sales-table">
  <thead>
    <tr>
      <th>Invoice</th>
      <th>Date</th>
      <th>Branch</th>
      <th>Customer</th>
      <th>Items Count</th>
      <th>Total</th>
      <th>Paid</th>
      <th>Due</th>
      <th>Status</th>
      <th>Payment Method</th>
      <th>Salesman</th>
      {user?.role !== "salesman" && <th>Profit</th>}
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    {sales.map((sale) => (
      <tr key={sale.id}>
        <td>
          <a onClick={() => handleViewDetails(sale)}>{sale.invoice_no}</a>
        </td>
        <td>{formatDate(sale.sale_date)}</td>
        <td>{sale.branch?.name}</td>
        <td>
          <div>{sale.customer_name || "Walk-in"}</div>
          {sale.customer_phone && <small>{sale.customer_phone}</small>}
        </td>
        <td>{sale.items?.length || 0}</td>
        <td>৳{sale.total}</td>
        <td>৳{sale.paid_amount}</td>
        <td className={sale.due_amount > 0 ? "text-red" : ""}>
          ৳{sale.due_amount}
        </td>
        <td>
          <span className={`badge ${getStatusClass(sale.payment_status)}`}>
            {sale.payment_status}
          </span>
        </td>
        <td>
          {getPaymentMethodIcon(sale.payment_method)} {sale.payment_method}
        </td>
        <td>{sale.user?.name}</td>
        {user?.role !== "salesman" && <td>৳{calculateProfit(sale)}</td>}
        <td>
          <button onClick={() => handleViewDetails(sale)}>👁️</button>
          <button onClick={() => handlePrint(sale)}>🖨️</button>
          {user?.role === "owner" && (
            <button onClick={() => handleDelete(sale)}>🗑️</button>
          )}
        </td>
      </tr>
    ))}
  </tbody>
</table>
```

#### 6. Add Payment Method Icons

```jsx
const getPaymentMethodIcon = (method) => {
  const icons = {
    cash: "💵",
    card: "💳",
    bkash: "📱",
    mobile_banking: "📲",
    bank_transfer: "🏦",
  };
  return icons[method] || "💰";
};
```

### Phase 3: Export Functionality

#### 7. Implement CSV Export

```jsx
const handleExportCSV = async () => {
  try {
    setLoading(true);

    // Build params from current filters
    const params = {};
    Object.keys(filters).forEach((key) => {
      if (filters[key] !== "" && filters[key] != null) {
        params[key] = filters[key];
      }
    });

    // Generate CSV from current data
    const csvContent = generateCSV(sales);
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", `sales_${new Date().getTime()}.csv`);
    link.style.visibility = "hidden";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    setSuccess("Sales exported successfully");
  } catch (err) {
    setError("Failed to export sales");
  } finally {
    setLoading(false);
  }
};

const generateCSV = (data) => {
  const headers = [
    "Invoice No",
    "Date",
    "Branch",
    "Customer",
    "Phone",
    "Items",
    "Subtotal",
    "Discount",
    "Tax",
    "Total",
    "Paid",
    "Due",
    "Status",
    "Method",
    "Salesman",
  ];

  const rows = data.map((sale) => [
    sale.invoice_no || "",
    sale.sale_date || "",
    sale.branch?.name || "",
    sale.customer_name || "Walk-in",
    sale.customer_phone || "",
    sale.items?.length || 0,
    sale.subtotal || 0,
    sale.discount || 0,
    sale.tax || 0,
    sale.total || 0,
    sale.paid_amount || 0,
    sale.due_amount || 0,
    sale.payment_status || "",
    sale.payment_method || "",
    sale.user?.name || "",
  ]);

  return [headers, ...rows]
    .map((row) => row.map((cell) => `"${cell}"`).join(","))
    .join("\n");
};

// Add export button
<button onClick={handleExportCSV}>📥 Export CSV</button>;
```

### Phase 4: Enhanced Detail Modal

#### 8. Improved Detail Modal

```jsx
const handleViewDetails = async (sale) => {
  try {
    setLoading(true);
    const data = await saleService.getSaleById(sale.id);
    setSelectedSale(data);
    setShowDetailModal(true);
  } catch (err) {
    setError("Failed to load sale details");
  } finally {
    setLoading(false);
  }
};

// Enhanced modal content
{
  showDetailModal && selectedSale && (
    <div className="modal-overlay" onClick={() => setShowDetailModal(false)}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <h2>Sale Details</h2>
          <button onClick={() => setShowDetailModal(false)}>×</button>
        </div>

        <div className="modal-body">
          {/* Sale Information */}
          <div className="detail-section">
            <h3>Sale Information</h3>
            <div className="detail-grid">
              <div>
                <strong>Invoice:</strong> {selectedSale.invoice_no}
              </div>
              <div>
                <strong>Date:</strong> {formatDate(selectedSale.sale_date)}
              </div>
              <div>
                <strong>Branch:</strong> {selectedSale.branch?.name}
              </div>
              <div>
                <strong>Salesman:</strong> {selectedSale.user?.name}
              </div>
            </div>
          </div>

          {/* Customer Information */}
          <div className="detail-section">
            <h3>Customer Information</h3>
            <div>
              <strong>Name:</strong> {selectedSale.customer_name || "Walk-in"}
            </div>
            <div>
              <strong>Phone:</strong> {selectedSale.customer_phone || "-"}
            </div>
          </div>

          {/* Items Table */}
          <div className="detail-section">
            <h3>Products</h3>
            <table>
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                {selectedSale.items?.map((item) => (
                  <tr key={item.id}>
                    <td>
                      <div>{item.product?.name}</div>
                      <small>{item.product?.sku}</small>
                    </td>
                    <td>{item.quantity}</td>
                    <td>৳{item.unit_price}</td>
                    <td>৳{item.subtotal}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Payment Summary */}
          <div className="detail-section">
            <h3>Payment Summary</h3>
            <div className="payment-summary">
              <div className="summary-row">
                <span>Subtotal:</span>
                <span>৳{selectedSale.subtotal}</span>
              </div>
              <div className="summary-row">
                <span>Discount:</span>
                <span>-৳{selectedSale.discount}</span>
              </div>
              <div className="summary-row">
                <span>Tax:</span>
                <span>+৳{selectedSale.tax}</span>
              </div>
              <div className="summary-row total">
                <span>Grand Total:</span>
                <span>৳{selectedSale.total}</span>
              </div>
              <div className="summary-row">
                <span>Paid:</span>
                <span>৳{selectedSale.paid_amount}</span>
              </div>
              <div className="summary-row due">
                <span>Due:</span>
                <span>৳{selectedSale.due_amount}</span>
              </div>
            </div>
          </div>

          {/* Profit Info (Owner/Manager only) */}
          {user?.role !== "salesman" && (
            <div className="detail-section">
              <h3>Profit Information</h3>
              <div>
                <strong>Total Profit:</strong> ৳{calculateProfit(selectedSale)}
              </div>
              <div>
                <strong>Profit Margin:</strong> {calculateMargin(selectedSale)}%
              </div>
            </div>
          )}
        </div>

        <div className="modal-footer">
          <button onClick={() => handlePrint(selectedSale)}>🖨️ Print</button>
          <button onClick={() => setShowDetailModal(false)}>Close</button>
        </div>
      </div>
    </div>
  );
}
```

### Phase 5: Delete Functionality

#### 9. Delete Sale (Owner Only)

```jsx
const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
const [saleToDelete, setSaleToDelete] = useState(null);

const handleDeleteClick = (sale) => {
  setSaleToDelete(sale);
  setShowDeleteConfirm(true);
};

const confirmDelete = async () => {
  if (!saleToDelete) return;

  try {
    setLoading(true);
    await saleService.deleteSale(saleToDelete.id);
    setSuccess("Sale deleted successfully");
    setShowDeleteConfirm(false);
    setSaleToDelete(null);
    fetchSales(pagination.current_page); // Refresh list
  } catch (err) {
    setError(err.response?.data?.message || "Failed to delete sale");
  } finally {
    setLoading(false);
  }
};

// Delete confirmation modal
{
  showDeleteConfirm && saleToDelete && (
    <div className="modal-overlay">
      <div className="modal-content modal-sm">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this sale?</p>
        <p>
          <strong>Invoice: {saleToDelete.invoice_no}</strong>
        </p>
        <p className="warning">This action cannot be undone.</p>
        <div className="modal-actions">
          <button onClick={() => setShowDeleteConfirm(false)}>Cancel</button>
          <button className="btn-danger" onClick={confirmDelete}>
            Delete
          </button>
        </div>
      </div>
    </div>
  );
}

// Add delete button to table (Owner only)
{
  user?.role === "owner" && (
    <button
      className="btn-delete"
      onClick={() => handleDeleteClick(sale)}
      title="Delete"
    >
      🗑️
    </button>
  );
}
```

---

## 🎨 CSS Styling Guide

### Sales.css - Enhanced Styles

```css
/* Statistics Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  color: white;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 0.75rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
}

.stat-label {
  font-size: 0.875rem;
  opacity: 0.9;
}

/* Search Box */
.search-box {
  position: relative;
  flex: 1;
  max-width: 500px;
}

.search-box input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 3rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 1rem;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1.25rem;
  height: 1.25rem;
  color: #94a3b8;
}

/* Date Presets */
.date-presets {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}

.preset-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background: white;
  cursor: pointer;
  transition: all 0.2s;
}

.preset-btn:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
}

.preset-btn.active {
  background: #4f46e5;
  color: white;
  border-color: #4f46e5;
}

/* Filters Panel */
.filters-panel {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  padding: 1.5rem;
  margin-top: 1rem;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.filter-field label {
  display: block;
  margin-bottom: 0.25rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #475569;
}

.filter-field input,
.filter-field select {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
}

/* Table Enhancements */
.sales-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 0.75rem;
  overflow: hidden;
}

.sales-table thead {
  background: #f1f5f9;
}

.sales-table th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: #475569;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.sales-table td {
  padding: 1rem;
  border-top: 1px solid #f1f5f9;
  color: #334155;
}

.sales-table tbody tr:hover {
  background: #f8fafc;
}

.invoice-link {
  color: #4f46e5;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
}

.invoice-link:hover {
  text-decoration: underline;
}

.customer-cell {
  display: flex;
  flex-direction: column;
}

.customer-name {
  font-weight: 500;
}

.customer-phone {
  font-size: 0.75rem;
  color: #64748b;
}

.amount-cell {
  font-weight: 600;
  font-family: monospace;
}

/* Status Badges */
.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.status-paid {
  background: #dcfce7;
  color: #166534;
}

.status-partial {
  background: #fef3c7;
  color: #92400e;
}

.status-unpaid {
  background: #fee2e2;
  color: #991b1b;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn-action {
  padding: 0.5rem;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-action svg {
  width: 1.25rem;
  height: 1.25rem;
}

.btn-view {
  background: #dbeafe;
  color: #1e40af;
}

.btn-view:hover {
  background: #bfdbfe;
}

.btn-print {
  background: #e0e7ff;
  color: #4338ca;
}

.btn-print:hover {
  background: #c7d2fe;
}

.btn-delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete:hover {
  background: #fecaca;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 1rem;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-close {
  padding: 0.5rem;
  border: none;
  background: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
}

.modal-body {
  padding: 1.5rem;
}

.detail-section {
  margin-bottom: 2rem;
}

.detail-section h3 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #1e293b;
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.detail-table {
  width: 100%;
  border-collapse: collapse;
}

.detail-table th,
.detail-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #f1f5f9;
}

.detail-table thead {
  background: #f8fafc;
}

.payment-summary {
  background: #f8fafc;
  border-radius: 0.5rem;
  padding: 1rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
}

.summary-row.total {
  font-size: 1.25rem;
  font-weight: 700;
  color: #4f46e5;
  border-top: 2px solid #e2e8f0;
  padding-top: 1rem;
  margin-top: 0.5rem;
}

.summary-row.due {
  color: #dc2626;
  font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .date-presets {
    flex-direction: column;
  }

  .filter-grid {
    grid-template-columns: 1fr;
  }

  .table-container {
    overflow-x: auto;
  }

  .sales-table {
    min-width: 800px;
  }

  .detail-grid {
    grid-template-columns: 1fr;
  }
}

/* Print Styles */
@media print {
  .sales-sidebar,
  .sales-header,
  .filters-section,
  .pagination,
  .action-buttons,
  .modal-footer {
    display: none !important;
  }

  .modal-content {
    max-width: 100%;
    max-height: none;
    box-shadow: none;
  }
}
```

---

## 🔧 Backend Enhancements (Optional)

### Add Statistics Endpoint

**Create a new method in SaleController.php**:

```php
/**
 * Get sales statistics
 */
public function statistics(Request $request): JsonResponse
{
    $query = Sale::query();

    // Apply filters
    if ($request->has('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }

    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
    }

    // Calculate statistics
    $stats = [
        'total_sales' => $query->count(),
        'total_amount' => $query->sum('total'),
        'paid_amount' => $query->sum('paid_amount'),
        'due_amount' => $query->sum('due_amount'),
        'average_sale' => $query->avg('total'),
        'total_profit' => $query->with('items')->get()->sum('total_profit'),
    ];

    // Payment status breakdown
    $stats['by_status'] = [
        'paid' => Sale::where('payment_status', 'paid')->count(),
        'partial' => Sale::where('payment_status', 'partial')->count(),
        'unpaid' => Sale::where('payment_status', 'unpaid')->count(),
    ];

    // Payment method breakdown
    $stats['by_method'] = Sale::select('payment_method', DB::raw('count(*) as count'))
        ->groupBy('payment_method')
        ->get();

    return response()->json($stats);
}
```

**Add route in api.php**:

```php
Route::get('sales/statistics', [SaleController::class, 'statistics']);
```

### Add CSV Export Endpoint

```php
/**
 * Export sales to CSV
 */
public function exportCSV(Request $request)
{
    $query = Sale::with(['branch', 'user', 'items']);

    // Apply filters (same as index)
    // ...

    $sales = $query->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="sales_' . date('Y-m-d') . '.csv"',
    ];

    $callback = function() use ($sales) {
        $file = fopen('php://output', 'w');

        // Headers
        fputcsv($file, ['Invoice', 'Date', 'Branch', 'Customer', 'Phone', 'Items', 'Subtotal', 'Discount', 'Tax', 'Total', 'Paid', 'Due', 'Status', 'Method', 'Salesman']);

        // Data
        foreach ($sales as $sale) {
            fputcsv($file, [
                $sale->invoice_no,
                $sale->sale_date,
                $sale->branch->name,
                $sale->customer_name ?? 'Walk-in',
                $sale->customer_phone ?? '',
                $sale->items->count(),
                $sale->subtotal,
                $sale->discount,
                $sale->tax,
                $sale->total,
                $sale->paid_amount,
                $sale->due_amount,
                $sale->payment_status,
                $sale->payment_method,
                $sale->user->name,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
```

**Add route**:

```php
Route::get('sales/export/csv', [SaleController::class, 'exportCSV']);
```

---

## 📋 Implementation Checklist

### ✅ Completed

- [x] Backend API endpoints (index, show, update, delete)
- [x] Sales service layer (frontend)
- [x] Basic sales list page
- [x] Pagination
- [x] Date range filter
- [x] Payment status filter
- [x] Detail modal
- [x] Responsive design
- [x] Bilingual support

### 🚧 To Implement (Follow guides above)

- [ ] Search functionality with debounce
- [ ] Statistics cards
- [ ] Date preset buttons
- [ ] Advanced filters panel
  - [ ] Branch filter
  - [ ] Payment method filter
  - [ ] Amount range filter
  - [ ] Salesman filter (manager/owner)
- [ ] Enhanced table columns
  - [ ] Items count
  - [ ] Paid/Due amounts
  - [ ] Payment method icons
  - [ ] Salesman column
  - [ ] Profit column (owner/manager)
- [ ] Export functionality
  - [ ] CSV export
  - [ ] PDF export (optional)
- [ ] Enhanced detail modal
  - [ ] Complete sale information
  - [ ] Customer history link
  - [ ] Profit information (role-based)
- [ ] Delete functionality (owner only)
- [ ] Role-based access control
  - [ ] Hide profit from salesmen
  - [ ] Branch filtering (owner sees all)
  - [ ] Delete button (owner only)

### 🔮 Future Enhancements

- [ ] Sale return/refund processing
- [ ] Edit customer information
- [ ] Add notes to sales
- [ ] Print customization
- [ ] Email invoice
- [ ] WhatsApp invoice
- [ ] Customer purchase history
- [ ] Sales analytics charts
- [ ] Commission tracking
- [ ] Audit trail/timeline

---

## 🧪 Testing Guide

### Test Cases

1. **Pagination Test**
   - Load page with >15 sales
   - Verify pagination controls appear
   - Click next/previous buttons
   - Change items per page

2. **Filter Test**
   - Select date range, verify results
   - Select payment status, verify filter
   - Combine multiple filters
   - Clear filters

3. **Search Test**
   - Search by invoice number
   - Search by customer name
   - Search by phone number
   - Verify debounce (500ms)

4. **Detail Modal Test**
   - Click on invoice number
   - Verify all details load
   - Test print button
   - Close modal

5. **Role-Based Access Test**
   - Login as salesman: verify prof doesn't show
   - Login as manager: verify branch filter locked
   - Login as owner: verify all options available

6. **Export Test**
   - Click export button
   - Verify CSV downloads
   - Check file content

7. **Delete Test** (Owner only)
   - Click delete button
   - Verify confirmation modal
   - Confirm delete
   - Verify sale removed and stock restored

---

## 📖 API Usage Examples

### Fetch Sales with Filters

```javascript
const fetchSalesWithFilters = async () => {
  const params = {
    page: 1,
    per_page: 25,
    branch_id: 2,
    payment_status: "partial",
    start_date: "2026-02-01",
    end_date: "2026-02-19",
    search: "INV-20260219",
  };

  const response = await api.get("/sales", { params });
  console.log(response.data);
};
```

### Get Sale Details

```javascript
const getSaleDetails = async (saleId) => {
  const response = await api.get(`/sales/${saleId}`);
  console.log(response.data.sale);
};
```

### Delete Sale

```javascript
const deleteSale = async (saleId) => {
  try {
    const response = await api.delete(`/sales/${saleId}`);
    console.log("Deleted:", response.data.message);
  } catch (error) {
    console.error("Error:", error.response.data.message);
  }
};
```

---

## 🎯 Summary

You now have:

1. ✅ **Complete backend** - Fully functional sales API
2. ✅ **Sales service layer** - All API methods wrapped
3. ✅ **Basic Sales page** - List, pagination, filters, modal
4. 📘 **Implementation guides** - Step-by-step for all enhancements
5. 🎨 **CSS styles** - Complete styling guide
6. ✅ **Documentation** - Comprehensive reference

**Next Steps**:

1. Follow the implementation guides above for each feature
2. Test thoroughly with different user roles
3. Customize styles to match your brand
4. Add role-based access control checks
5. Implement export functionality
6. Add statistics cards
7. Enhance detail modal

The foundation is solid and production-ready. The enhancement guides provide clear, copy-paste-ready code for all remaining features!
