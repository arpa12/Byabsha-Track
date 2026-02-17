# POS System Quick Start Guide

## Getting Started

### Prerequisites

1. Backend server running on `http://localhost:8000`
2. Frontend server running on `http://localhost:5173`
3. MySQL database configured and seeded
4. Valid authentication token (logged in user)

### Starting the Servers

#### Backend

```bash
cd backend
php artisan serve
```

#### Frontend

```bash
cd frontend
npm run dev
```

---

## Accessing the POS

1. Open browser: `http://localhost:5173`
2. Login with credentials:
   - **Owner**: owner@byabshatrack.com / password
   - **Manager**: manager@byabshatrack.com / password
   - **Salesman**: salesman@byabshatrack.com / password
3. Click "POS" in the sidebar menu

---

## Using the POS System

### Step 1: Search for Products

1. Type product name or SKU in the search box
2. Wait for results to appear (300ms debounce)
3. Click "Add to Cart" on desired product

**Example Searches**:

- "USB Cable"
- "USB-C-001" (SKU)
- "Laptop"

![Product Search](https://via.placeholder.com/800x400?text=Product+Search+Interface)

---

### Step 2: Manage Cart

**Add Items**:

- Click "Add to Cart" from search results

**Adjust Quantity**:

- Use +/- buttons
- Or type directly in quantity input
- Maximum = available stock

**Remove Items**:

- Click trash icon (🗑️) on any item
- Or click "Clear Cart" to remove all

**Stock Indicator**:

- Each item shows "Stock: X units"
- Cannot exceed available stock

![Cart Management](https://via.placeholder.com/800x400?text=Cart+Management)

---

### Step 3: Enter Customer Info (Optional)

```
Customer Name: [Optional - defaults to "Walk-in Customer"]
Phone Number: [Optional - format: 01XXXXXXXXX]
```

**Use Cases**:

- Leave blank for anonymous walk-in customers
- Fill for registered customers or future tracking
- Phone number helpful for returns/exchanges

---

### Step 4: Apply Discount (Optional)

**Percentage Discount**:

1. Select "Percentage (%)" from dropdown
2. Enter percentage (e.g., 10 for 10%)
3. Discount auto-calculated from subtotal

**Fixed Discount**:

1. Select "Fixed Amount (৳)" from dropdown
2. Enter amount (e.g., 50 for ৳50 off)
3. Discount deducted from subtotal

**Example**:

```
Subtotal: ৳1000
Discount: 10% = -৳100
After Discount: ৳900
```

---

### Step 5: Add Tax (Optional)

1. Enter tax rate as percentage (e.g., 5 for 5%)
2. Tax calculated on amount AFTER discount
3. Tax added to total

**Example**:

```
After Discount: ৳900
Tax: 5% = +৳45
Total: ৳945
```

---

### Step 6: Select Payment Method

Choose from 5 payment methods:

| Method         | Icon | Use Case                 |
| -------------- | ---- | ------------------------ |
| Cash           | 💵   | Cash payments            |
| Card           | 💳   | Credit/Debit card        |
| bKash          | 📱   | Mobile financial service |
| Mobile Banking | 📲   | Other mobile banking     |
| Bank Transfer  | 🏦   | Direct bank transfer     |

**Quick Shortcuts** (for cash):

- +৳100, +৳500, +৳1000 buttons
- "Exact Amount" button

---

### Step 7: Enter Paid Amount

**Full Payment**:

```
Total: ৳945
Paid: ৳945
Status: Paid ✅
Change: ৳0
```

**Overpayment**:

```
Total: ৳945
Paid: ৳1000
Status: Paid ✅
Change: ৳55 (give to customer)
```

**Partial Payment**:

```
Total: ৳945
Paid: ৳500
Status: Partial ⚠️
Due: ৳445
```

**No Payment (Credit)**:

```
Total: ৳945
Paid: ৳0
Status: Unpaid 🔴
Due: ৳945
```

---

### Step 8: Complete Sale

1. Review all details in payment section
2. Ensure cart is not empty
3. Click **"Complete Sale"** button
4. Wait for processing (shows spinner)

**Success**:

- ✅ "Sale completed successfully!" message
- 🎉 Invoice modal appears
- Cart automatically clears

**Error**:

- ❌ Error message displays (e.g., stock insufficient)
- Cart remains unchanged
- Fix error and try again

---

### Step 9: View & Print Invoice

**Invoice Sections**:

1. **Business Info**: Branch name, address, phone, email
2. **Invoice Details**: Invoice number, date/time
3. **Customer Info**: Name and phone (if provided)
4. **Salesman Info**: Current user details
5. **Items Table**: Product details, quantities, prices
6. **Payment Summary**: All calculations breakdown
7. **Profit Info**: Internal profit tracking

**Actions**:

- **Print Invoice**: Opens print dialog
- **Close**: Returns to POS (cart already cleared)

**Print Dialog**:

- Browser's print dialog opens
- Choose printer or save as PDF
- Professional formatted invoice
- No UI elements (buttons, colors removed)

![Invoice Modal](https://via.placeholder.com/800x600?text=Invoice+Modal)

---

## Common Workflows

### Workflow 1: Quick Cash Sale

```
1. Search "USB Cable"
2. Click "Add to Cart"
3. Click "Exact Amount" button
4. Click "Complete Sale"
5. Click "Print Invoice"
6. Done! ✅
```

**Time**: ~15 seconds

---

### Workflow 2: Multiple Items with Discount

```
1. Search and add "Laptop" (qty: 1)
2. Search and add "Mouse" (qty: 2)
3. Search and add "Keyboard" (qty: 1)
4. Select discount: "Percentage"
5. Enter discount: 10
6. Select payment: "Card"
7. Enter paid amount (exact)
8. Click "Complete Sale"
9. Print invoice
```

**Time**: ~45 seconds

---

### Workflow 3: Partial Payment

```
1. Add items to cart
2. Customer pays only part of total
3. Enter partial amount in "Paid Amount"
4. Note: Due amount shows in red
5. Complete sale
6. Invoice shows "Partial" status with due amount
7. Track due payment separately
```

**Use Case**: Credit sales for regular customers

---

## Keyboard Shortcuts (Future)

Currently not implemented. Planned shortcuts:

- `Ctrl + F`: Focus search box
- `Ctrl + Enter`: Complete sale
- `Esc`: Clear search / Close modal
- `F1-F5`: Quick select payment method
- `Ctrl + P`: Print invoice

---

## Tips & Best Practices

### 1. Stock Management

✅ **Always check stock before adding to cart**

- System validates automatically
- Shows available quantity on each item
- Prevents overselling

### 2. Customer Information

✅ **Collect phone numbers when possible**

- Helps with returns
- Useful for marketing
- Enables loyalty programs

### 3. Discount Usage

✅ **Be consistent with discount policies**

- Percentage discounts for promotions
- Fixed discounts for specific amounts
- Document discount reasons

### 4. Payment Methods

✅ **Record accurate payment methods**

- Important for cash reconciliation
- Helps track payment trends
- Needed for accounting

### 5. Invoice Printing

✅ **Print immediately after sale**

- Customer proof of purchase
- Your record copy
- Legal requirement in many places

### 6. Error Handling

✅ **Read error messages carefully**

- Stock errors: Check inventory
- Validation errors: Fix input
- Network errors: Check connection

---

## Troubleshooting

### Problem: Search not working

**Symptoms**: No results when typing

**Solutions**:

1. Check internet connection
2. Verify backend is running (`http://localhost:8000`)
3. Check browser console for errors
4. Try refreshing the page
5. Clear browser cache

**Test**:

```bash
curl http://localhost:8000/api/products
```

---

### Problem: Cannot add to cart

**Symptoms**: Error message when clicking "Add to Cart"

**Common Causes**:

1. **Out of stock**: Product has 0 quantity
2. **Branch not selected**: No branch in dropdown
3. **Permission issue**: Token expired

**Solutions**:

1. Check stock in database
2. Select branch from dropdown
3. Logout and login again

---

### Problem: Stock validation error

**Symptoms**: "Insufficient stock" error when completing sale

**Causes**:

- Another sale processed simultaneously
- Stock updated after adding to cart
- Database sync issue

**Solutions**:

1. Refresh stock by removing and re-adding item
2. Reduce quantity
3. Check actual stock in database:

```sql
SELECT quantity FROM branch_stocks
WHERE product_id = ? AND branch_id = ?;
```

---

### Problem: Complete Sale button disabled

**Symptoms**: Button is gray and unclickable

**Reasons**:

1. Cart is empty
2. Sale is processing (loading state)

**Solutions**:

1. Add items to cart
2. Wait for previous operation to complete

---

### Problem: Invoice not printing

**Symptoms**: Print dialog doesn't open

**Common Causes**:

1. Popup blocker enabled
2. Print permission denied
3. Browser settings

**Solutions**:

1. Allow popups for this site
2. Check browser print settings
3. Try different browser
4. Use "Save as PDF" instead

**Firefox**:

```
Settings → Privacy & Security → Permissions → Block pop-up windows
→ Add exception for localhost:5173
```

**Chrome**:

```
Settings → Privacy and security → Site Settings → Pop-ups and redirects
→ Add localhost:5173 to allowed
```

---

### Problem: Calculations seem wrong

**Symptoms**: Totals don't match expectations

**Check**:

1. Discount applied correctly?
   - Percentage: (subtotal × discount%) / 100
   - Fixed: Direct deduction
2. Tax applied AFTER discount?
   - Tax = (subtotal - discount) × tax%
3. Rounding differences?
   - System uses 2 decimal places

**Example Calculation**:

```
Cart Items:
- Product A: ৳500 × 2 = ৳1000
- Product B: ৳300 × 1 = ৳300
Subtotal: ৳1300

Discount (10%): ৳1300 × 10% = ৳130
After Discount: ৳1300 - ৳130 = ৳1170

Tax (5%): ৳1170 × 5% = ৳58.50
Total: ৳1170 + ৳58.50 = ৳1228.50
```

---

## Advanced Features

### Using Different Branches

If you have multiple branches:

1. Select branch from dropdown (top-right)
2. Only that branch's stock is used
3. Sales recorded to selected branch
4. Cannot switch branch mid-sale (clear cart first)

### Walk-in vs Registered Customers

**Walk-in Customer** (default):

- Leave name/phone blank
- Shows as "Walk-in Customer" on invoice
- No tracking

**Registered Customer**:

- Enter name and phone
- Can track purchase history
- Useful for loyalty programs
- Required for returns/exchanges

### Payment Status Tracking

System automatically determines status:

| Paid Amount      | Total | Status  | Invoice Color          |
| ---------------- | ----- | ------- | ---------------------- |
| = Total          | ৳1000 | Paid    | Green ✅               |
| > Total          | ৳1000 | Paid    | Green ✅ (with change) |
| 0 < Paid < Total | ৳1000 | Partial | Orange ⚠️              |
| 0                | ৳1000 | Unpaid  | Red 🔴                 |

---

## FAQ

### Q: Can I edit a sale after completion?

**A**: No, sales are final. This ensures data integrity. For corrections:

1. Process a return/refund (future feature)
2. Create a new sale with correct details
3. Adjust inventory manually if needed

### Q: What if I make a mistake?

**A**: Before clicking "Complete Sale":

- Edit cart items freely
- Change payment details
- Clear cart and start over

After completion:

- Cannot undo
- Create reverse entry if needed

### Q: Can multiple users use POS simultaneously?

**A**: Yes! Each user has their own session:

- Separate carts
- Concurrent sales allowed
- Stock validated at checkout
- First-come-first-served for limited stock

### Q: Where are sales stored?

**A**: Backend database (`sales` and `sale_items` tables)

- View in Sales List page (coming soon)
- Access via Reports
- Export for accounting

### Q: Can I use barcode scanner?

**A**: Not yet implemented, but planned:

- Search by SKU (manual entry works now)
- Barcode scanner will auto-fill SKU
- Press Enter to add to cart

### Q: What about returns/refunds?

**A**: Coming in future version:

- Search invoice by number
- Select items to return
- Adjust stock automatically
- Process refund

---

## Support

### Need Help?

1. **Check Documentation**:
   - `POS_FRONTEND_DOCUMENTATION.md` - Technical details
   - `POS_API_DOCUMENTATION.md` - API reference
   - This file - User guide

2. **Check Logs**:
   - Browser Console (F12)
   - Backend logs: `backend/storage/logs/laravel.log`

3. **Database Queries**:

   ```sql
   -- Check recent sales
   SELECT * FROM sales ORDER BY created_at DESC LIMIT 10;

   -- Check product stock
   SELECT p.name, bs.quantity, bs.branch_id
   FROM products p
   JOIN branch_stocks bs ON p.id = bs.product_id;
   ```

4. **API Testing**:

   ```bash
   # Test authentication
   curl http://localhost:8000/api/me \
     -H "Authorization: Bearer YOUR_TOKEN"

   # Test product search
   curl http://localhost:8000/api/products?search=usb \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

---

## What's Next?

After mastering the POS, explore:

- **Sales List**: View all completed sales
- **Reports**: Daily/monthly sales reports
- **Products**: Manage inventory
- **Purchases**: Record restocking
- **Expenses**: Track business costs
- **Dashboard**: Overall business metrics

---

## Comparison with Old System

If migrating from manual/old POS:

| Feature            | Old System        | New POS          |
| ------------------ | ----------------- | ---------------- |
| Product Search     | Manual lookup     | Instant search   |
| Stock Check        | Manual count      | Auto-validated   |
| Invoice Generation | Handwritten       | Auto-generated   |
| Calculations       | Manual/Calculator | Automatic        |
| Record Keeping     | Paper             | Digital database |
| Reports            | Manual counting   | Instant reports  |
| Multi-branch       | Complex           | Simple dropdown  |

**Benefits**:

- ⚡ Faster checkout (15 sec vs 5 min)
- ✅ No calculation errors
- 📊 Instant reports
- 🔒 Secure & tracked
- 🌐 Access from anywhere
- 📱 Multi-branch support

---

## Glossary

- **POS**: Point of Sale - where sales transactions occur
- **SKU**: Stock Keeping Unit - unique product code
- **Invoice**: Receipt/bill given to customer
- **Cart**: Temporary list of items before checkout
- **Subtotal**: Sum before discounts and taxes
- **Grand Total**: Final amount after all calculations
- **Change**: Amount to return when overpaid
- **Due**: Remaining amount when underpaid
- **Walk-in**: Customer without recorded details
- **Stock**: Available product quantity

---

Happy Selling! 🎉
