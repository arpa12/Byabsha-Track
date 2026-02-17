# POS API Test Examples

## Prerequisites

1. Backend server running: `php artisan serve`
2. User authenticated with Bearer token
3. Products and stock available in database

## Get Auth Token First

```bash
# Login to get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "salesman@byabshatrack.com",
    "password": "password"
  }'

# Response will contain:
# {
#   "access_token": "your_token_here",
#   "token_type": "Bearer",
#   ...
# }
```

---

## Test 1: Simple Cash Sale

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "payment_method": "cash",
    "paid_amount": 1000,
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 2,
        "unit_price": 500
      }
    ]
  }'
```

**Expected Result:**

- Subtotal: 1000
- Total: 1000
- Paid: 1000
- Change: 0
- Stock: Product 1 quantity decreased by 2

---

## Test 2: Sale with Percentage Discount

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "customer_name": "John Doe",
    "customer_phone": "01712345678",
    "discount_type": "percentage",
    "discount_value": 10,
    "payment_method": "cash",
    "paid_amount": 1000,
    "cart_items": [
      {
        "product_id": 2,
        "quantity": 3,
        "unit_price": 300
      }
    ]
  }'
```

**Expected Result:**

- Subtotal: 900
- Discount (10%): 90
- Total: 810
- Paid: 1000
- Change: 190

---

## Test 3: Sale with Fixed Discount and Tax

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "customer_name": "Jane Smith",
    "discount_type": "fixed",
    "discount_value": 50,
    "tax_rate": 5,
    "payment_method": "card",
    "paid_amount": 1000,
    "cart_items": [
      {
        "product_id": 3,
        "quantity": 2,
        "unit_price": 500
      }
    ]
  }'
```

**Expected Result:**

- Subtotal: 1000
- Discount (fixed): 50
- After Discount: 950
- Tax (5%): 47.5
- Total: 997.5
- Paid: 1000
- Change: 2.5

---

## Test 4: Multiple Products with Bkash

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "customer_name": "Ahmed Khan",
    "customer_phone": "01812345678",
    "discount_type": "percentage",
    "discount_value": 5,
    "tax_rate": 3,
    "payment_method": "bkash",
    "paid_amount": 2500,
    "note": "Special customer",
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 1,
        "unit_price": 800
      },
      {
        "product_id": 2,
        "quantity": 2,
        "unit_price": 500
      },
      {
        "product_id": 3,
        "quantity": 1,
        "unit_price": 300
      }
    ]
  }'
```

**Expected Result:**

- Subtotal: 800 + 1000 + 300 = 2100
- Discount (5%): 105
- After Discount: 1995
- Tax (3%): 59.85
- Total: 2054.85
- Paid: 2500
- Change: 445.15

---

## Test 5: Partial Payment

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "customer_name": "Karim Mia",
    "customer_phone": "01912345678",
    "payment_method": "cash",
    "paid_amount": 500,
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 1,
        "unit_price": 1000
      }
    ]
  }'
```

**Expected Result:**

- Total: 1000
- Paid: 500
- Due: 500
- Payment Status: "partial"
- Change: 0

---

## Test 6: Insufficient Stock (Error Case)

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "payment_method": "cash",
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 999999,
        "unit_price": 500
      }
    ]
  }'
```

**Expected Response (422):**

```json
{
  "success": false,
  "message": "Insufficient stock",
  "error": {
    "product_id": 1,
    "product_name": "Product Name",
    "available_quantity": 10,
    "requested_quantity": 999999,
    "shortage": 999989
  }
}
```

---

## Test 7: Validation Error (Missing Required Fields)

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1
  }'
```

**Expected Response (422):**

```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "payment_method": ["The payment method field is required."],
    "cart_items": ["The cart items field is required."]
  }
}
```

---

## Test 8: Walk-in Customer (No Customer Info)

```bash
curl -X POST http://localhost:8000/api/sales/pos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_id": 1,
    "payment_method": "cash",
    "cart_items": [
      {
        "product_id": 1,
        "quantity": 1,
        "unit_price": 500
      }
    ]
  }'
```

**Expected Result:**

- Customer name: "Walk-in Customer"
- Customer phone: null

---

## Testing with Postman

### Import Collection

1. Open Postman
2. Create new collection: "ByabshaTrack POS API"
3. Add environment variables:
   - `base_url`: http://localhost:8000/api
   - `token`: Your Bearer token

### Create Request

1. Method: POST
2. URL: `{{base_url}}/sales/pos`
3. Headers:
   - Authorization: Bearer `{{token}}`
   - Content-Type: application/json
   - Accept: application/json
4. Body (raw JSON):

```json
{
  "branch_id": 1,
  "customer_name": "Test Customer",
  "payment_method": "cash",
  "paid_amount": 1000,
  "cart_items": [
    {
      "product_id": 1,
      "quantity": 2,
      "unit_price": 500
    }
  ]
}
```

---

## JavaScript/Axios Example

```javascript
import axios from "axios";

const processPOSSale = async () => {
  try {
    const response = await axios.post(
      "http://localhost:8000/api/sales/pos",
      {
        branch_id: 1,
        customer_name: "John Doe",
        customer_phone: "01712345678",
        discount_type: "percentage",
        discount_value: 10,
        tax_rate: 5,
        payment_method: "cash",
        paid_amount: 1000,
        cart_items: [
          {
            product_id: 1,
            quantity: 2,
            unit_price: 500,
          },
        ],
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      },
    );

    console.log("Sale Success:", response.data);
    return response.data.invoice;
  } catch (error) {
    if (error.response) {
      console.error("Error:", error.response.data);
    }
    throw error;
  }
};

// Usage
processPOSSale()
  .then((invoice) => {
    console.log("Invoice No:", invoice.invoice_no);
    console.log("Total:", invoice.payment.grand_total);
  })
  .catch((err) => console.error("Failed:", err));
```

---

## Verification Steps

After each test, verify:

1. **Sale Record Created**

```sql
SELECT * FROM sales ORDER BY id DESC LIMIT 1;
```

2. **Sale Items Created**

```sql
SELECT * FROM sale_items WHERE sale_id = (SELECT MAX(id) FROM sales);
```

3. **Stock Decreased**

```sql
SELECT * FROM branch_stocks WHERE product_id = X AND branch_id = Y;
```

4. **Invoice Number Generated**

```sql
SELECT invoice_no FROM sales ORDER BY id DESC LIMIT 5;
```

---

## Common Issues & Solutions

### Issue 1: 401 Unauthorized

**Solution**: Get fresh token by logging in again

### Issue 2: 422 Validation Error

**Solution**: Check all required fields are present and valid

### Issue 3: 422 Insufficient Stock

**Solution**:

- Check available stock: `SELECT * FROM branch_stocks WHERE product_id = X`
- Reduce quantity or purchase more stock

### Issue 4: 500 Server Error

**Solution**:

- Check Laravel logs: `storage/logs/laravel.log`
- Verify database connection
- Ensure all migrations ran successfully

---

## Performance Testing

### Load Test with Apache Bench

```bash
# 100 requests, 10 concurrent
ab -n 100 -c 10 -p pos_request.json \
   -T "application/json" \
   -H "Authorization: Bearer YOUR_TOKEN" \
   http://localhost:8000/api/sales/pos
```

### Expected Performance

- Average response time: < 200ms
- 95th percentile: < 500ms
- Throughput: > 50 req/sec

---

_Happy Testing! 🚀_
