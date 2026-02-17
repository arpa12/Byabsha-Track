# POS Frontend Documentation

## Overview

The Point of Sale (POS) frontend is a React-based application built with functional components and hooks. It provides a complete sales interface with product search, cart management, payment processing, and invoice generation.

## File Structure

```
frontend/src/
├── pages/
│   └── POS.jsx                    # Main POS page component
├── components/
│   └── POS/
│       ├── ProductSearch.jsx      # Product search interface
│       ├── Cart.jsx               # Shopping cart display
│       ├── CartItem.jsx           # Individual cart item component
│       ├── PaymentSection.jsx     # Payment details & checkout
│       └── InvoiceModal.jsx       # Invoice display modal
└── services/
    └── posService.js              # POS API service functions
```

---

## Components

### 1. POS.jsx (Main Page)

**Location**: `src/pages/POS.jsx`

**Purpose**: Main container component that manages the entire POS flow.

**State Management**:

```javascript
// Product & Cart
const [products, setProducts] = useState([]); // Search results
const [cart, setCart] = useState([]); // Cart items
const [searchQuery, setSearchQuery] = useState(""); // Search input

// Branch & User
const [branches, setBranches] = useState([]);
const [selectedBranch, setSelectedBranch] = useState("");

// Payment Details
const [paymentMethod, setPaymentMethod] = useState("cash");
const [discountType, setDiscountType] = useState("percentage");
const [discountValue, setDiscountValue] = useState(0);
const [taxRate, setTaxRate] = useState(0);
const [paidAmount, setPaidAmount] = useState(0);

// Customer Info
const [customerName, setCustomerName] = useState("");
const [customerPhone, setCustomerPhone] = useState("");

// UI State
const [loading, setLoading] = useState(false);
const [error, setError] = useState("");
const [success, setSuccess] = useState("");
const [invoice, setInvoice] = useState(null);
const [showInvoice, setShowInvoice] = useState(false);
```

**Key Functions**:

- `fetchBranches()` - Load available branches on mount
- `searchProducts(query)` - Debounced product search (300ms delay)
- `addToCart(product)` - Add product with stock validation
- `updateQuantity(productId, newQuantity)` - Update cart item quantity
- `removeFromCart(productId)` - Remove item from cart
- `calculateTotals()` - Calculate subtotal, discount, tax, total
- `completeSale()` - Submit sale to backend API
- `resetForm()` - Clear form after successful sale

**Props Passed to Children**:

```javascript
// To ProductSearch
<ProductSearch
  searchQuery={searchQuery}
  setSearchQuery={setSearchQuery}
  products={products}
  loading={loading}
  onAddToCart={addToCart}
/>

// To Cart
<Cart
  cart={cart}
  onUpdateQuantity={updateQuantity}
  onRemoveItem={removeFromCart}
  onClearCart={clearCart}
  totals={totals}
/>

// To PaymentSection
<PaymentSection
  totals={totals}
  paymentMethod={paymentMethod}
  setPaymentMethod={setPaymentMethod}
  // ... all payment-related props
  onCompleteSale={completeSale}
  loading={loading}
  cartEmpty={cart.length === 0}
/>

// To InvoiceModal
<InvoiceModal
  invoice={invoice}
  onClose={() => setShowInvoice(false)}
/>
```

---

### 2. ProductSearch.jsx

**Location**: `src/components/POS/ProductSearch.jsx`

**Purpose**: Product search interface with real-time results.

**Features**:

- Search by product name or SKU
- Debounced search (triggered in parent)
- Loading state indicator
- Click-to-add products to cart
- Display product details (name, SKU, category, price)

**Props**:

```javascript
{
  searchQuery: string,
  setSearchQuery: (query: string) => void,
  products: Array<Product>,
  loading: boolean,
  onAddToCart: (product: Product) => void
}
```

**UI States**:

1. Empty state: "Start typing to search..."
2. Loading state: Spinner animation
3. Results state: List of matching products
4. No results: "No products found"

---

### 3. Cart.jsx & CartItem.jsx

**Location**: `src/components/POS/Cart.jsx`, `src/components/POS/CartItem.jsx`

**Purpose**: Display and manage shopping cart items.

**Cart Features**:

- Empty cart message
- Item list with scrolling
- Cart summary (subtotal, discount, tax, total)
- Clear cart button

**CartItem Features**:

- Product details display
- Quantity controls (+/- buttons, direct input)
- Stock availability indicator
- Item subtotal calculation
- Remove item button

**Props (Cart)**:

```javascript
{
  cart: Array<CartItem>,
  onUpdateQuantity: (productId, newQuantity) => void,
  onRemoveItem: (productId) => void,
  onClearCart: () => void,
  totals: {
    subtotal: string,
    discount: string,
    tax: string,
    total: string
  }
}
```

**Props (CartItem)**:

```javascript
{
  item: {
    product_id: number,
    product_name: string,
    product_sku: string,
    unit_price: number,
    cost_price: number,
    quantity: number,
    available_stock: number
  },
  onUpdateQuantity: (productId, newQuantity) => void,
  onRemove: (productId) => void
}
```

---

### 4. PaymentSection.jsx

**Location**: `src/components/POS/PaymentSection.jsx`

**Purpose**: Handle payment details and complete sale.

**Features**:

- Customer information (optional)
- Discount configuration (percentage or fixed)
- Tax rate input
- Payment method selection (5 methods)
- Paid amount with quick add buttons
- Change/Due calculation
- Complete sale button

**Payment Methods**:

1. Cash 💵
2. Card 💳
3. bKash 📱
4. Mobile Banking 📲
5. Bank Transfer 🏦

**Props**:

```javascript
{
  totals: Object,
  paymentMethod: string,
  setPaymentMethod: (method: string) => void,
  discountType: 'percentage' | 'fixed',
  setDiscountType: (type: string) => void,
  discountValue: number,
  setDiscountValue: (value: number) => void,
  taxRate: number,
  setTaxRate: (rate: number) => void,
  paidAmount: number,
  setPaidAmount: (amount: number) => void,
  customerName: string,
  setCustomerName: (name: string) => void,
  customerPhone: string,
  setCustomerPhone: (phone: string) => void,
  onCompleteSale: () => void,
  loading: boolean,
  cartEmpty: boolean
}
```

**Calculations**:

```javascript
// Change (if overpaid)
const change = paid > total ? (paid - total).toFixed(2) : "0.00";

// Due (if underpaid)
const due = paid < total ? (total - paid).toFixed(2) : "0.00";
```

---

### 5. InvoiceModal.jsx

**Location**: `src/components/POS/InvoiceModal.jsx`

**Purpose**: Display and print completed sale invoice.

**Features**:

- Professional invoice layout
- Business information header
- Customer and salesman details
- Itemized product list
- Payment summary with all calculations
- Profit information (internal)
- Print functionality
- Responsive modal design

**Props**:

```javascript
{
  invoice: {
    invoice_no: string,
    sale_date: string,
    business: {
      name: string,
      address: string,
      phone: string,
      email: string
    },
    customer: {
      name: string,
      phone: string
    },
    salesman: {
      name: string,
      email: string
    },
    items: Array<{
      product_name: string,
      product_sku: string,
      category: string,
      quantity: number,
      unit_price: number,
      subtotal: number
    }>,
    payment: {
      subtotal: number,
      discount_amount: number,
      discount_type: string,
      discount_value: number,
      tax_amount: number,
      tax_rate: number,
      total_amount: number,
      paid_amount: number,
      due_amount: number,
      change_amount: number,
      payment_method: string,
      payment_status: string
    },
    profit: {
      total_profit: number,
      profit_margin: number
    }
  },
  onClose: () => void
}
```

**Print Function**:

```javascript
const handlePrint = () => {
  const printWindow = window.open("", "", "width=800,height=600");
  printWindow.document.write(/* HTML content */);
  printWindow.print();
};
```

---

## Services

### posService.js

**Location**: `src/services/posService.js`

**Purpose**: Centralized API calls for POS operations.

**Available Functions**:

```javascript
// Search products
searchProducts(query: string, perPage: number = 10)
// Returns: Array<Product>

// Get product stock for a branch
getProductStock(productId: number, branchId: number)
// Returns: { quantity: number }

// Get all branches
getBranches()
// Returns: Array<Branch>

// Complete POS sale
completeSale(saleData: Object)
// Returns: { data: Invoice }

// Get sale by ID
getSale(saleId: number)
// Returns: Sale

// Get today's sales
getTodaySales(branchId: number = null)
// Returns: Array<Sale>

// Get product by ID
getProduct(productId: number)
// Returns: Product

// Get product by SKU
getProductBySKU(sku: string)
// Returns: Product | null
```

**Example Usage**:

```javascript
import posService from "../services/posService";

// In component
const searchProducts = async (query) => {
  try {
    const results = await posService.searchProducts(query, 10);
    setProducts(results);
  } catch (error) {
    console.error("Search failed:", error);
  }
};
```

---

## State Flow

### 1. Product Search Flow

```
User types in search box
  ↓
300ms debounce delay
  ↓
searchProducts() API call
  ↓
Update products state
  ↓
ProductSearch displays results
  ↓
User clicks "Add to Cart"
  ↓
Stock validation API call
  ↓
Add to cart array or show error
```

### 2. Cart Management Flow

```
Product added to cart
  ↓
Check if product exists in cart
  ↓
If exists: increment quantity
If new: add new item
  ↓
Cart component re-renders
  ↓
Update totals calculation
  ↓
PaymentSection shows new total
```

### 3. Checkout Flow

```
User fills payment details
  ↓
User clicks "Complete Sale"
  ↓
Validate cart not empty
Validate branch selected
  ↓
Build sale data object
  ↓
POST /api/sales/pos
  ↓
Success: Show invoice modal
Error: Display error message
  ↓
Reset form after timeout
```

---

## Calculation Logic

### Totals Calculation

```javascript
const calculateTotals = () => {
  // 1. Calculate subtotal
  const subtotal = cart.reduce(
    (sum, item) => sum + item.unit_price * item.quantity,
    0,
  );

  // 2. Calculate discount
  let discount = 0;
  if (discountType === "percentage") {
    discount = (subtotal * discountValue) / 100;
  } else {
    discount = discountValue;
  }

  // 3. Calculate tax (on discounted amount)
  const afterDiscount = subtotal - discount;
  const tax = (afterDiscount * taxRate) / 100;

  // 4. Calculate total
  const total = afterDiscount + tax;

  return {
    subtotal: subtotal.toFixed(2),
    discount: discount.toFixed(2),
    tax: tax.toFixed(2),
    total: total.toFixed(2),
  };
};
```

### Payment Status Logic (Backend)

```javascript
// Determined by backend based on paid_amount vs total
if (paid_amount >= total) {
  payment_status = "paid";
  change_amount = paid_amount - total;
} else if (paid_amount > 0) {
  payment_status = "partial";
  due_amount = total - paid_amount;
} else {
  payment_status = "unpaid";
  due_amount = total;
}
```

---

## API Integration

### Request Format

**Endpoint**: `POST /api/sales/pos`

**Headers**:

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:

```json
{
  "branch_id": 1,
  "customer_name": "John Doe",
  "customer_phone": "01712345678",
  "payment_method": "cash",
  "discount_type": "percentage",
  "discount_value": 10,
  "tax_rate": 5,
  "paid_amount": 1000,
  "cart_items": [
    {
      "product_id": 1,
      "quantity": 2,
      "unit_price": 500.0,
      "cost_price": 400.0
    }
  ]
}
```

**Success Response** (201):

```json
{
  "success": true,
  "message": "Sale completed successfully",
  "data": {
    "invoice_no": "INV-20260217-00001",
    "sale_date": "2026-02-17 14:30:00",
    "business": {
      /* branch details */
    },
    "customer": {
      /* customer info */
    },
    "salesman": {
      /* user info */
    },
    "items": [
      /* sale items */
    ],
    "payment": {
      /* payment breakdown */
    },
    "profit": {
      /* profit info */
    }
  }
}
```

**Error Response** (422):

```json
{
  "message": "Validation error",
  "errors": [
    {
      "product_name": "USB Cable",
      "requested_quantity": 5,
      "available_quantity": 2,
      "shortage": 3
    }
  ]
}
```

---

## Error Handling

### Stock Validation Errors

```javascript
try {
  const response = await api.post("/sales/pos", saleData);
  setInvoice(response.data.data);
  setShowInvoice(true);
} catch (err) {
  if (err.response?.data?.errors) {
    const stockErrors = err.response.data.errors;
    if (Array.isArray(stockErrors)) {
      const errorMsg = stockErrors
        .map(
          (e) =>
            `${e.product_name}: Need ${e.requested_quantity}, only ${e.available_quantity} available`,
        )
        .join("; ");
      setError(errorMsg);
    }
  }
}
```

### Network Errors

```javascript
if (error.response?.status === 401) {
  // Handled by axios interceptor
  // Redirects to /login
} else if (error.response?.status === 403) {
  // Unauthorized - insufficient permissions
} else if (error.response?.status === 422) {
  // Validation error
} else {
  // Server error or network issue
  setError("Failed to complete sale");
}
```

---

## Styling

### Tailwind CSS Classes

**Colors**:

- Primary: `bg-blue-500`, `text-blue-600`
- Success: `bg-green-500`, `text-green-600`
- Danger: `bg-red-500`, `text-red-600`
- Gray: `bg-gray-50`, `text-gray-700`

**Common Patterns**:

```javascript
// Input field
className =
  "w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500";

// Button primary
className =
  "px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition";

// Card
className = "bg-white rounded-lg shadow-md p-6";

// Grid layout
className = "grid grid-cols-1 lg:grid-cols-3 gap-4";
```

---

## Performance Optimizations

### 1. Debounced Search

```javascript
useEffect(() => {
  const timer = setTimeout(() => {
    searchProducts(searchQuery);
  }, 300);

  return () => clearTimeout(timer);
}, [searchQuery, searchProducts]);
```

### 2. useCallback for Functions

```javascript
const searchProducts = useCallback(async (query) => {
  // Function body
}, []); // Dependencies array
```

### 3. Conditional Rendering

```javascript
{
  cart.length === 0 ? <EmptyState /> : <CartItemsList />;
}
```

---

## Future Enhancements

- [ ] Barcode scanner integration
- [ ] Keyboard shortcuts (Enter to search, F9 to checkout)
- [ ] Recent sales history
- [ ] Customer database integration
- [ ] Loyalty points/rewards
- [ ] Multiple cart tabs
- [ ] Draft sales (save for later)
- [ ] Receipt printer integration
- [ ] Offline mode with sync
- [ ] Product recommendations
- [ ] Sales analytics dashboard

---

## Testing

### Manual Testing Checklist

1. **Product Search**
   - [ ] Search by product name
   - [ ] Search by SKU
   - [ ] Empty search clears results
   - [ ] Loading state shows spinner

2. **Cart Management**
   - [ ] Add product to cart
   - [ ] Increase quantity
   - [ ] Decrease quantity
   - [ ] Remove item
   - [ ] Clear entire cart
   - [ ] Prevent exceeding stock

3. **Payment Processing**
   - [ ] Calculate discount (percentage)
   - [ ] Calculate discount (fixed)
   - [ ] Calculate tax
   - [ ] Select payment method
   - [ ] Enter paid amount
   - [ ] Show change/due
   - [ ] Complete sale

4. **Invoice**
   - [ ] Display invoice modal
   - [ ] Print invoice
   - [ ] Close modal

5. **Error Handling**
   - [ ] Stock insufficient error
   - [ ] Empty cart error
   - [ ] Network error
   - [ ] Validation errors

---

## Troubleshooting

### Issue: Products not loading

**Solution**: Check API endpoint and authentication token

```javascript
console.log("API URL:", import.meta.env.VITE_API_URL);
console.log("Token:", localStorage.getItem("token"));
```

### Issue: Stock validation failing

**Solution**: Ensure branch_id is correctly passed

```javascript
console.log("Selected Branch:", selectedBranch);
console.log("Stock API:", `/products/${productId}/stock?branch_id=${branchId}`);
```

### Issue: Invoice not printing

**Solution**: Check popup blocker settings

```javascript
// Test popup
const testPrint = () => {
  const printWindow = window.open("", "", "width=800,height=600");
  if (!printWindow) {
    alert("Please allow popups for this site");
  }
};
```

---

## Related Documentation

- Backend API: See `POS_API_DOCUMENTATION.md`
- Stock Management: See `STOCK_MANAGEMENT.md`
- Database Schema: See `DATABASE_SCHEMA.md`
- Report Service: See `REPORT_SERVICE_GUIDE.md`
