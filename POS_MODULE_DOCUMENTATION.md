# POS Module - Complete Implementation Guide

## Overview

The ByabshaTrack POS (Point of Sale) module is a fully-featured, production-ready sales system designed for multi-branch retail operations. It includes real-time inventory management, barcode scanning, flexible payment options, and comprehensive invoice generation.

---

## ✨ Features Implemented

### Frontend Features (React)

1. **Product Search & Selection**
   - ✅ Search products by name or SKU
   - ✅ Real-time search with debouncing (300ms)
   - ✅ Product dropdown with price display
   - ✅ Click to add to cart

2. **Barcode Scanner Support**
   - ✅ Dedicated barcode input field
   - ✅ Scan or manual barcode entry
   - ✅ Enter key to add product instantly
   - ✅ One-click "Add" button
   - ✅ Auto-clear after successful scan

3. **Shopping Cart Management**
   - ✅ Add products to cart
   - ✅ Update quantity with +/- buttons
   - ✅ Remove individual items
   - ✅ Clear entire cart
   - ✅ Real-time stock validation
   - ✅ Visual quantity controls

4. **Pricing & Discounts**
   - ✅ Subtotal calculation
   - ✅ Discount (Percentage or Fixed amount)
   - ✅ Tax calculation (percentage-based)
   - ✅ Grand total with real-time updates

5. **Payment Processing**
   - ✅ Multiple payment methods:
     - Cash
     - Card
     - bKash (mobile money)
   - ✅ Partial payment support
   - ✅ Cash change calculation
   - ✅ Due amount tracking

6. **Invoice Generation**
   - ✅ Detailed invoice modal
   - ✅ Business & branch information
   - ✅ Customer details
   - ✅ Itemized product list
   - ✅ Payment breakdown
   - ✅ Print functionality
   - ✅ Invoice number generation
   - ✅ Date and time stamp

7. **Multi-Branch Support**
   - ✅ Branch selector in header
   - ✅ Branch-wise stock checking
   - ✅ Role-based access (Owner sees all, Staff sees assigned branch)

8. **User Experience**
   - ✅ Responsive design (mobile & desktop)
   - ✅ Bilingual support (English/Bengali)
   - ✅ Collapsible sidebar navigation
   - ✅ Loading states
   - ✅ Error handling with user-friendly messages
   - ✅ Success notifications

### Backend Features (Laravel)

1. **API Endpoint**
   - Route: `POST /api/sales/pos`
   - Authentication: Required (Sanctum)
   - Access: All authenticated users

2. **Stock Management**
   - ✅ Real-time stock availability check
   - ✅ Pessimistic locking to prevent race conditions
   - ✅ Automatic stock deduction on sale
   - ✅ Branch-wise inventory tracking
   - ✅ Insufficient stock error messages

3. **Transaction Safety**
   - ✅ Database transactions with BEGIN/COMMIT
   - ✅ Automatic ROLLBACK on errors
   - ✅ Atomic operations (all or nothing)
   - ✅ Stock validation before deduction

4. **Validation Rules**
   - ✅ Non-empty cart validation
   - ✅ Branch existence check
   - ✅ Product existence check
   - ✅ Positive quantity validation
   - ✅ Valid payment method check
   - ✅ Stock sufficiency check

5. **Invoice Data**
   - ✅ Auto-generated invoice number (INV-YYYYMMDD-00001)
   - ✅ Complete business information
   - ✅ Customer details
   - ✅ Salesman information
   - ✅ Itemized breakdown
   - ✅ Payment details
   - ✅ Profit calculation (internal)
   - ✅ Timestamp tracking

6. **Error Handling**
   - ✅ Structured JSON error responses
   - ✅ Stock shortage details
   - ✅ Product availability messages
   - ✅ Validation error listing

---

## 🏗️ Architecture

### Service Layer Pattern

```
Request → Controller → Validation → Controller Logic → Response
                            ↓
                    Database Transaction
                            ↓
                    (Stock Check → Sale Creation → Stock Update)
```

### Clean Code Principles

1. **Separation of Concerns**: Controller handles HTTP, business logic in transaction blocks
2. **Single Responsibility**: Each function has one clear purpose
3. **DRY (Don't Repeat Yourself)**: Reusable validation and error handling
4. **Transaction Safety**: All-or-nothing approach with rollback
5. **Pessimistic Locking**: Prevents concurrent stock issues

---

## 📁 File Structure

```
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── SaleController.php        # POS API endpoint
│   ├── Models/
│   │   ├── Sale.php                          # Sale model
│   │   ├── SaleItem.php                      # Sale items model
│   │   ├── Product.php                       # Product model
│   │   └── BranchStock.php                   # Inventory model
│   └── Services/
│       └── ReportService.php                 # Sales reporting
├── routes/
│   └── api.php                               # Route: POST /api/sales/pos

frontend/
├── src/
│   ├── pages/
│   │   ├── POS.jsx                           # Main POS page
│   │   └── POS.css                           # POS styling
│   ├── services/
│   │   ├── api.js                            # Axios instance
│   │   └── posService.js                     # POS API calls
│   └── context/
│       ├── AuthContext.jsx                   # User authentication
│       └── LanguageContext.jsx               # i18n support
```

---

## 🔌 API Documentation

### Complete Sale - POST `/api/sales/pos`

**Authentication**: Required (Bearer Token)

#### Request Body

```json
{
  "branch_id": 1,
  "customer_name": "John Doe",
  "customer_phone": "01712345678",
  "discount_type": "percentage",
  "discount_value": 10,
  "tax_rate": 5,
  "payment_method": "cash",
  "paid_amount": 500,
  "note": "Regular customer",
  "cart_items": [
    {
      "product_id": 1,
      "quantity": 2,
      "unit_price": 250
    },
    {
      "product_id": 3,
      "quantity": 1,
      "unit_price": 150
    }
  ]
}
```

#### Validation Rules

| Field                    | Type    | Required | Validation                                               |
| ------------------------ | ------- | -------- | -------------------------------------------------------- |
| branch_id                | integer | Yes      | Must exist in branches table                             |
| customer_name            | string  | No       | Max 255 characters                                       |
| customer_phone           | string  | No       | Max 20 characters                                        |
| discount_type            | string  | No       | Either "fixed" or "percentage"                           |
| discount_value           | number  | No       | Min: 0                                                   |
| tax_rate                 | number  | No       | Min: 0, Max: 100                                         |
| payment_method           | string  | Yes      | One of: cash, card, bkash, mobile_banking, bank_transfer |
| paid_amount              | number  | No       | Min: 0                                                   |
| note                     | string  | No       | Max 500 characters                                       |
| cart_items               | array   | Yes      | Min 1 item                                               |
| cart_items.\*.product_id | integer | Yes      | Must exist in products table                             |
| cart_items.\*.quantity   | number  | Yes      | Min: 0.01                                                |
| cart_items.\*.unit_price | number  | Yes      | Min: 0                                                   |

#### Success Response (201 Created)

```json
{
  "success": true,
  "message": "Sale completed successfully",
  "invoice": {
    "invoice_no": "INV-20260219-00001",
    "sale_id": 1,
    "date": "2026-02-19",
    "time": "02:30 PM",
    "business": {
      "name": "ByabshaTrack",
      "branch": "Main Branch",
      "address": "123 Market Street, Dhaka",
      "phone": "01712345678",
      "email": "info@byabshatrack.com"
    },
    "customer": {
      "name": "John Doe",
      "phone": "01712345678"
    },
    "salesman": {
      "name": "Jane Smith",
      "id": 2
    },
    "items": [
      {
        "product_id": 1,
        "product_name": "iPhone 14 Pro",
        "product_sku": "IPH14PRO-512",
        "category": "Electronics",
        "quantity": 2,
        "unit_price": 250,
        "unit_cost": 200,
        "subtotal": 500,
        "profit": 100
      }
    ],
    "total_items": 2,
    "total_quantity": 3,
    "payment": {
      "subtotal": 650,
      "discount": {
        "type": "percentage",
        "value": 10,
        "amount": 65
      },
      "tax": {
        "rate": 5,
        "amount": 29.25
      },
      "grand_total": 614.25,
      "paid_amount": 500,
      "due_amount": 114.25,
      "change_amount": 0,
      "payment_method": "cash",
      "payment_status": "partial"
    },
    "profit": {
      "total_profit": 120,
      "profit_margin": 18.46
    }
  }
}
```

#### Error Responses

**Validation Error (422)**

```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "cart_items": ["The cart items field is required."],
    "branch_id": ["The selected branch id is invalid."]
  }
}
```

**Insufficient Stock (422)**

```json
{
  "success": false,
  "message": "Insufficient stock",
  "error": {
    "product_id": 1,
    "product_name": "iPhone 14 Pro",
    "available_quantity": 5,
    "requested_quantity": 10,
    "shortage": 5
  }
}
```

**Product Not Available (422)**

```json
{
  "success": false,
  "message": "Product not available in this branch",
  "error": {
    "product_id": 1,
    "product_name": "iPhone 14 Pro",
    "available_quantity": 0,
    "requested_quantity": 2
  }
}
```

**Server Error (500)**

```json
{
  "success": false,
  "message": "Failed to process sale",
  "error": "Database connection failed"
}
```

---

## 💻 Frontend Usage

### Adding Product by Search

1. User types product name or SKU in search box
2. Dropdown shows matching products with prices
3. Click product to add to cart
4. Stock checked automatically

### Adding Product by Barcode

1. Focus on barcode input field
2. Scan barcode with scanner OR type manually
3. Press Enter or click "Add" button
4. Product added to cart instantly
5. Shows error if barcode not found

### Completing a Sale

1. Add products to cart
2. (Optional) Enter customer details
3. (Optional) Apply discount
4. (Optional) Add tax
5. Select payment method
6. Enter paid amount
7. Review change/due amount
8. Click "Complete Sale"
9. Invoice modal appears
10. Print or close invoice

---

## 🔒 Security Features

1. **Authentication Required**: All POS operations require valid token
2. **Pessimistic Locking**: Prevents concurrent stock modifications
3. **Input Validation**: All inputs sanitized and validated
4. **SQL Injection Protection**: Eloquent ORM parameterized queries
5. **XSS Protection**: React escapes all outputs by default
6. **CSRF Protection**: Sanctum handles CSRF for API
7. **Rate Limiting**: Laravel's built-in rate limiter (60 requests/minute)

---

## 🧪 Testing Scenarios

### Stock Validation Tests

1. ✅ **Prevent sale with insufficient stock**
   - Add 10 items when only 5 available
   - Expected: Error message with shortage details

2. ✅ **Prevent sale when product not in branch**
   - Try to sell product not stocked in current branch
   - Expected: "Product not available in this branch"

3. ✅ **Handle concurrent sales**
   - Two salesmen try to sell same last item
   - Expected: One succeeds, other gets stock error

### Payment Tests

4. ✅ **Calculate change correctly**
   - Total: ৳500, Paid: ৳600
   - Expected: Change ৳100

5. ✅ **Track due amount**
   - Total: ৳500, Paid: ৳300
   - Expected: Due ৳200, Status "partial"

6. ✅ **Full payment**
   - Total: ৳500, Paid: ৳500
   - Expected: Status "paid"

### Discount Tests

7. ✅ **Percentage discount**
   - Subtotal: ৳1000, Discount: 10%
   - Expected: Discount ৳100, Total ৳900

8. ✅ **Fixed discount**
   - Subtotal: ৳1000, Discount: ৳150
   - Expected: Total ৳850

### Edge Cases

9. ✅ **Empty cart submission**
   - Try to complete sale with no items
   - Expected: "Cart is empty" error

10. ✅ **Negative quantity**
    - Try to add -5 units
    - Expected: Validation error

11. ✅ **Invalid barcode**
    - Scan non-existent barcode
    - Expected: "Product not found with this barcode"

---

## 🚀 Performance Optimizations

1. **Debounced Search**: 300ms delay prevents excessive API calls
2. **Pessimistic Locking**: `lockForUpdate()` ensures data consistency
3. **Eager Loading**: Load relationships upfront (`.with()`)
4. **Indexed Queries**: Database indexes on foreign keys and search fields
5. **Transaction Batching**: All operations in single transaction
6. **Minimal Re-renders**: React `useCallback` for stable function references

---

## 📊 Database Schema

### Sales Table

```sql
sales (
  id,
  invoice_no (unique),
  branch_id (foreign key),
  user_id (foreign key),
  customer_name,
  customer_phone,
  sale_date,
  subtotal (decimal),
  discount (decimal),
  tax (decimal),
  total (decimal),
  paid_amount (decimal),
  due_amount (decimal),
  payment_status (enum: paid, partial, unpaid),
  payment_method (enum: cash, card, bkash, mobile_banking, bank_transfer),
  note (text),
  created_at,
  updated_at,
  deleted_at
)
```

### Sale Items Table

```sql
sale_items (
  id,
  sale_id (foreign key),
  product_id (foreign key),
  quantity (decimal),
  unit_cost (decimal),
  unit_price (decimal),
  subtotal (decimal),
  profit (decimal),
  created_at,
  updated_at
)
```

### Branch Stocks Table

```sql
branch_stocks (
  id,
  branch_id (foreign key),
  product_id (foreign key),
  quantity (decimal),
  created_at,
  updated_at,
  UNIQUE(branch_id, product_id)
)
```

---

## 🎨 UI/UX Highlights

1. **Modern Gradient Design**: Beautiful purple-blue gradient theme
2. **Intuitive Layout**: Left side for products/cart, right side for payment
3. **Real-time Feedback**: Instant calculations and stock warnings
4. **Mobile Responsive**: Works on tablets and phones
5. **Clear Typography**: Easy-to-read fonts with proper hierarchy
6. **Visual Indicators**: Color-coded buttons and status messages
7. **Smooth Animations**: Hover effects and transitions
8. **Print-friendly Invoice**: Optimized layout for thermal printers

---

## 🌍 Internationalization (i18n)

- **English**: Full support
- **Bengali**: Native language support
- **Toggle**: Language switcher in header
- **All Labels**: Translated in both languages
- **Numbers**: Currency symbol (৳) properly displayed

---

## 🔮 Future Enhancements

1. **Receipt Printer Integration**: Direct USB/Bluetooth printer support
2. **Customer Management**: Save frequent customer details
3. **Loyalty Program**: Points and rewards system
4. **Split Payment**: Multiple payment methods in one sale
5. **Return/Refund**: Handle product returns
6. **Hold/Park Sales**: Save incomplete sales for later
7. **Offline Mode**: Local storage for network issues
8. **Sales Graph**: Real-time sales visualization
9. **Quick Keys**: Keyboard shortcuts for faster operations
10. **Sound Feedback**: Beep on successful scan

---

## 📝 Code Quality Standards

1. **Clean Code**: Meaningful variable names, single responsibility
2. **Comments**: Documenting complex logic
3. **Error Handling**: Try-catch blocks with user-friendly messages
4. **Type Safety**: PropTypes/TypeScript for type checking
5. **Consistent Style**: ESLint + Prettier formatting
6. **Modular Design**: Reusable components and services
7. **Git Workflow**: Feature branches, descriptive commits

---

## 🛠️ Troubleshooting

### Issue: Barcode scanner not working

**Solution**:

- Check if scanner is in keyboard emulation mode
- Ensure barcode input field is focused
- Verify barcode format matches product barcodes in database

### Issue: Stock always showing as unavailable

**Solution**:

- Check `branch_stocks` table has records for the selected branch
- Verify `quantity` column is not NULL
- Ensure `product_id` matches correctly

### Issue: Invoice not displaying after sale

**Solution**:

- Check browser console for errors
- Verify API response includes `invoice` object
- Check if `showInvoice` state is set to `true`

### Issue: Payment method not saving

**Solution**:

- Ensure payment method is one of: cash, card, bkash, mobile_banking, bank_transfer
- Check validation rules in backend
- Verify frontend is sending correct value (not "mobile", should be "bkash")

---

## 📞 Support

For issues or questions:

- **Email**: support@byabshatrack.com
- **GitHub**: [Repository Issues](https://github.com/byabshatrack/issues)
- **Documentation**: See `/docs` folder

---

## 📄 License

This POS module is part of ByabshaTrack system.
© 2026 ByabshaTrack. All rights reserved.

---

## ✅ Completion Checklist

- [x] Product search by name/SKU
- [x] Barcode scanning support
- [x] Add to cart functionality
- [x] Update cart quantities
- [x] Remove cart items
- [x] Clear cart
- [x] Subtotal calculation
- [x] Discount (percentage/fixed)
- [x] Tax calculation
- [x] Payment method selection (cash/card/bkash)
- [x] Change calculation
- [x] Complete sale button
- [x] Stock validation
- [x] Prevent insufficient stock
- [x] Prevent empty cart
- [x] Prevent negative quantity
- [x] Database transactions
- [x] Rollback on error
- [x] Invoice generation
- [x] Print invoice
- [x] Multi-branch support
- [x] Error handling
- [x] Success messages
- [x] Loading states
- [x] Responsive design
- [x] Bilingual support

**Status**: ✅ PRODUCTION READY

---

_Last Updated: February 19, 2026_
