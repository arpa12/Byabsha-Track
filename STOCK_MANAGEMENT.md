# Stock Management System Documentation

## Overview

Comprehensive stock management system with **database transactions** and **pessimistic locking** to ensure data integrity in multi-user environments.

---

## Core Features

### ✅ Automatic Stock Updates

- **Purchase**: Automatically increases stock
- **Sale**: Automatically decreases stock
- **Delete Purchase**: Reverses stock increase
- **Delete Sale**: Reverses stock decrease

### ✅ Data Integrity

- Database transactions for all operations
- Pessimistic locking to prevent race conditions
- Stock validation before sales
- Negative stock prevention

### ✅ Branch-Wise Inventory

- Each branch maintains separate stock levels
- Independent stock tracking per product per branch

---

## Implementation Details

### 1. Purchase Stock Management

**Location**: `PurchaseController@store`

#### Flow:

```php
DB::beginTransaction()
  ↓
1. Create Purchase Record
  ↓
2. Create Purchase Items
  ↓
3. Update Stock (with locking)
   - Lock stock record (lockForUpdate)
   - If exists: Increment quantity
   - If not exists: Create new record
  ↓
4. Update Supplier Balance
  ↓
DB::commit()
```

#### Code:

```php
// Lock and update stock
$stock = BranchStock::lockForUpdate()
    ->where('branch_id', $request->branch_id)
    ->where('product_id', $item['product_id'])
    ->first();

if ($stock) {
    $stock->increment('quantity', $item['quantity']);
} else {
    BranchStock::create([
        'branch_id' => $request->branch_id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
    ]);
}
```

#### Key Points:

- ✅ Uses `lockForUpdate()` for row-level locking
- ✅ Creates stock record if doesn't exist
- ✅ All operations wrapped in transaction
- ✅ Automatic rollback on error

---

### 2. Sale Stock Management

**Location**: `SaleController@store`

#### Flow:

```php
DB::beginTransaction()
  ↓
1. Validate Stock Availability (with locking)
   - Lock all required stock records
   - Check if product exists in branch
   - Check if sufficient quantity available
   - Return detailed error if insufficient
  ↓
2. Create Sale Record
  ↓
3. Create Sale Items with Profit Calculation
  ↓
4. Deduct from Stock (with locking)
   - Lock stock record again
   - Additional safety check for negative values
   - Decrement quantity
  ↓
DB::commit()
```

#### Stock Validation Code:

```php
// Validate with pessimistic locking
foreach ($request->items as $item) {
    $stock = BranchStock::lockForUpdate()
        ->where('branch_id', $request->branch_id)
        ->where('product_id', $item['product_id'])
        ->first();

    $product = Product::find($item['product_id']);

    // Check if product exists in branch
    if (!$stock) {
        DB::rollBack();
        return response()->json([
            'message' => "Product '{$product->name}' is not available in this branch",
            'product' => $product->name,
            'available_quantity' => 0,
            'requested_quantity' => $item['quantity'],
        ], 422);
    }

    // Check if sufficient stock
    if ($stock->quantity < $item['quantity']) {
        DB::rollBack();
        return response()->json([
            'message' => "Insufficient stock for product: {$product->name}",
            'product' => $product->name,
            'available_quantity' => (float) $stock->quantity,
            'requested_quantity' => (float) $item['quantity'],
            'shortage' => (float) ($item['quantity'] - $stock->quantity),
        ], 422);
    }
}
```

#### Stock Deduction Code:

```php
// Deduct with safety check
$stock = BranchStock::lockForUpdate()
    ->where('branch_id', $request->branch_id)
    ->where('product_id', $item['product_id'])
    ->first();

if ($stock) {
    $newQuantity = $stock->quantity - $item['quantity'];

    // Additional safety check
    if ($newQuantity < 0) {
        throw new \Exception("Stock quantity cannot be negative for product: {$product->name}");
    }

    $stock->decrement('quantity', $item['quantity']);
}
```

#### Key Points:

- ✅ **Two-phase locking**: Validate first, then deduct
- ✅ **Detailed error messages** with quantities
- ✅ **Safety checks** to prevent negative stock
- ✅ **Automatic rollback** if validation fails

---

### 3. Delete Purchase (Reverse Stock)

**Location**: `PurchaseController@destroy`

#### Flow:

```php
DB::beginTransaction()
  ↓
1. Lock and Validate Stock Records
   - Check if reversal would cause negative stock
  ↓
2. Decrement Stock Quantities
  ↓
3. Update Supplier Balance
  ↓
4. Soft Delete Purchase
  ↓
DB::commit()
```

#### Code:

```php
foreach ($purchase->items as $item) {
    $stock = BranchStock::lockForUpdate()
        ->where('branch_id', $purchase->branch_id)
        ->where('product_id', $item->product_id)
        ->first();

    if ($stock) {
        // Prevent negative stock
        if ($stock->quantity < $item->quantity) {
            throw new \Exception(
                "Cannot delete purchase: would result in negative stock for product ID {$item->product_id}"
            );
        }
        $stock->decrement('quantity', $item->quantity);
    }
}
```

#### Key Points:

- ✅ Validates before deletion
- ✅ Prevents negative stock scenarios
- ✅ Uses soft delete for recovery
- ✅ Maintains supplier balance integrity

---

### 4. Delete Sale (Restore Stock)

**Location**: `SaleController@destroy`

#### Flow:

```php
DB::beginTransaction()
  ↓
1. Lock Stock Records
  ↓
2. Add Back Stock Quantities
   - If record exists: Increment
   - If not exists: Create new
  ↓
3. Soft Delete Sale
  ↓
DB::commit()
```

#### Code:

```php
foreach ($sale->items as $item) {
    $stock = BranchStock::lockForUpdate()
        ->where('branch_id', $sale->branch_id)
        ->where('product_id', $item->product_id)
        ->first();

    if ($stock) {
        // Add back to stock
        $stock->increment('quantity', $item->quantity);
    } else {
        // Create stock record if it doesn't exist
        BranchStock::create([
            'branch_id' => $sale->branch_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
        ]);
    }
}
```

#### Key Points:

- ✅ Always succeeds (stock can only increase)
- ✅ Creates record if missing
- ✅ Uses soft delete for audit trail

---

## Pessimistic Locking

### Why Pessimistic Locking?

Prevents **race conditions** in concurrent operations:

#### Without Locking (❌ Race Condition):

```
Time | User A (Sale)         | User B (Sale)         | Stock
-----|----------------------|----------------------|-------
T0   | Read stock = 10      |                      | 10
T1   |                      | Read stock = 10      | 10
T2   | Sell 8 units         |                      | 2
T3   |                      | Sell 8 units         | -6 ❌
```

#### With Locking (✅ Safe):

```
Time | User A (Sale)         | User B (Sale)         | Stock
-----|----------------------|----------------------|-------
T0   | Lock & Read = 10     |                      | 10
T1   |                      | Wait (locked)        | 10
T2   | Sell 8 units         |                      | 2
T3   | Commit & Release     |                      | 2
T4   |                      | Lock & Read = 2      | 2
T5   |                      | Error: Insufficient! | 2 ✅
```

### How It Works:

```php
// lockForUpdate() acquires a row-level lock
$stock = BranchStock::lockForUpdate()
    ->where('branch_id', $branchId)
    ->where('product_id', $productId)
    ->first();

// Other transactions wait here until lock is released
// Lock is released when:
// - DB::commit() is called
// - DB::rollBack() is called
// - Transaction ends
```

---

## Error Handling

### Sale Validation Errors

#### 1. Product Not in Branch

```json
{
  "message": "Product 'iPhone 14 Pro' is not available in this branch",
  "product": "iPhone 14 Pro",
  "available_quantity": 0,
  "requested_quantity": 5
}
```

#### 2. Insufficient Stock

```json
{
  "message": "Insufficient stock for product: Samsung Galaxy S23",
  "product": "Samsung Galaxy S23",
  "available_quantity": 3.0,
  "requested_quantity": 5.0,
  "shortage": 2.0
}
```

#### 3. Negative Stock Prevention

```json
{
  "message": "Failed to create sale",
  "error": "Stock quantity cannot be negative for product: iPhone 14 Pro"
}
```

### Purchase Deletion Errors

#### Cannot Delete (Would Cause Negative Stock)

```json
{
  "message": "Failed to delete purchase",
  "error": "Cannot delete purchase: would result in negative stock for product ID 5"
}
```

---

## Database Transactions

### Transaction Lifecycle

```php
try {
    // 1. Start transaction
    DB::beginTransaction();

    // 2. Perform operations
    // - Lock records
    // - Validate
    // - Update

    // 3. Commit if all successful
    DB::commit();

} catch (\Exception $e) {
    // 4. Rollback on any error
    DB::rollBack();

    // 5. Return error response
    return response()->json([...], 500);
}
```

### ACID Properties

| Property        | Implementation                                       |
| --------------- | ---------------------------------------------------- |
| **Atomicity**   | All operations succeed or none (DB::commit/rollBack) |
| **Consistency** | Validation prevents invalid states                   |
| **Isolation**   | Pessimistic locking prevents interference            |
| **Durability**  | MySQL InnoDB storage engine                          |

---

## Stock Flow Examples

### Example 1: Purchase Flow

**Scenario**: Purchase 50 units of Product A for Branch 1

```
Before:
- branch_stocks: Product A, Branch 1 = 20 units

API Request:
POST /api/purchases
{
    "branch_id": 1,
    "supplier_id": 5,
    "items": [
        {
            "product_id": 10,
            "quantity": 50,
            "unit_price": 500
        }
    ]
}

Operations:
1. Lock stock record (Branch 1, Product A)
2. Current quantity: 20
3. Increment by 50
4. New quantity: 70
5. Commit transaction

After:
- branch_stocks: Product A, Branch 1 = 70 units ✅
```

### Example 2: Sale Flow (Success)

**Scenario**: Sell 15 units of Product B from Branch 2

```
Before:
- branch_stocks: Product B, Branch 2 = 30 units

API Request:
POST /api/sales
{
    "branch_id": 2,
    "items": [
        {
            "product_id": 15,
            "quantity": 15,
            "unit_price": 800
        }
    ]
}

Operations:
1. Lock stock record (Branch 2, Product B)
2. Validate: 30 >= 15 ✅
3. Create sale record
4. Lock stock again
5. Decrement by 15
6. New quantity: 15
7. Commit transaction

After:
- branch_stocks: Product B, Branch 2 = 15 units ✅
```

### Example 3: Sale Flow (Failure)

**Scenario**: Try to sell 25 units when only 10 available

```
Before:
- branch_stocks: Product C, Branch 3 = 10 units

API Request:
POST /api/sales
{
    "branch_id": 3,
    "items": [
        {
            "product_id": 20,
            "quantity": 25,
            "unit_price": 600
        }
    ]
}

Operations:
1. Lock stock record (Branch 3, Product C)
2. Validate: 10 < 25 ❌
3. Rollback transaction
4. Return error response

Response:
{
    "message": "Insufficient stock for product: Product C",
    "available_quantity": 10.0,
    "requested_quantity": 25.0,
    "shortage": 15.0
}

After:
- branch_stocks: Product C, Branch 3 = 10 units (unchanged) ✅
```

### Example 4: Delete Purchase

**Scenario**: Delete a purchase of 20 units (current stock: 50)

```
Before:
- branch_stocks: Product D, Branch 1 = 50 units

API Request:
DELETE /api/purchases/123

Operations:
1. Lock stock record
2. Validate: 50 >= 20 ✅
3. Decrement by 20
4. New quantity: 30
5. Soft delete purchase
6. Commit transaction

After:
- branch_stocks: Product D, Branch 1 = 30 units ✅
```

### Example 5: Delete Sale

**Scenario**: Delete a sale of 10 units

```
Before:
- branch_stocks: Product E, Branch 2 = 15 units

API Request:
DELETE /api/sales/456

Operations:
1. Lock stock record
2. Increment by 10
3. New quantity: 25
4. Soft delete sale
5. Commit transaction

After:
- branch_stocks: Product E, Branch 2 = 25 units ✅
```

---

## Concurrent Operations

### Scenario: Multiple Users Selling Same Product

**Setup**:

- Product: Laptop
- Branch: Main Branch
- Initial Stock: 5 units
- User A wants to sell: 3 units
- User B wants to sell: 3 units

**Timeline**:

```
T0: Stock = 5

T1: User A starts transaction
    - Lock acquired by User A

T2: User B starts transaction
    - Attempts to lock (WAITS)

T3: User A validates stock
    - 5 >= 3 ✅

T4: User A creates sale
    - Stock decrements to 2

T5: User A commits
    - Lock released

T6: User B acquires lock
    - Reads stock = 2

T7: User B validates stock
    - 2 < 3 ❌
    - Error returned

Result:
- User A: Sale successful ✅
- User B: Insufficient stock error ✅
- Final Stock: 2 units ✅
- Data Integrity: MAINTAINED ✅
```

---

## Performance Considerations

### Lock Duration

- Locks held only during transaction
- Minimize operations within transaction
- Average lock duration: < 100ms

### Scalability

- Row-level locking (not table-level)
- Different products can be processed concurrently
- Only same product in same branch causes waiting

### Optimizations

- Indexed foreign keys for fast lookups
- Composite indexes on (branch_id, product_id)
- Query optimization with eager loading

---

## Testing Stock Management

### Test Cases

#### 1. Test Purchase Stock Increase

```bash
# Create purchase
POST /api/purchases
# Verify stock increased in branch_stocks table
```

#### 2. Test Sale Stock Decrease

```bash
# Create sale
POST /api/sales
# Verify stock decreased in branch_stocks table
```

#### 3. Test Insufficient Stock Prevention

```bash
# Try to sell more than available
POST /api/sales (quantity > available)
# Expect: 422 error with detailed message
```

#### 4. Test Concurrent Sales

```bash
# Run two simultaneous sale requests
# Both should complete successfully OR
# One succeeds, one gets insufficient stock error
```

#### 5. Test Purchase Deletion

```bash
# Delete purchase
DELETE /api/purchases/{id}
# Verify stock decreased accordingly
```

#### 6. Test Sale Deletion

```bash
# Delete sale
DELETE /api/sales/{id}
# Verify stock increased back
```

### Manual Testing Queries

```sql
-- Check current stock
SELECT bs.*, p.name, b.name as branch_name
FROM branch_stocks bs
JOIN products p ON p.id = bs.product_id
JOIN branches b ON b.id = bs.branch_id
WHERE bs.branch_id = 1;

-- Track stock changes over time
SELECT
    'Purchase' as type,
    p.purchase_date as date,
    pi.product_id,
    pi.quantity,
    '+' as operation
FROM purchase_items pi
JOIN purchases p ON p.id = pi.purchase_id
WHERE p.branch_id = 1

UNION ALL

SELECT
    'Sale' as type,
    s.sale_date as date,
    si.product_id,
    si.quantity,
    '-' as operation
FROM sale_items si
JOIN sales s ON s.id = si.sale_id
WHERE s.branch_id = 1

ORDER BY date DESC;
```

---

## API Endpoints

### Create Purchase (Increases Stock)

```
POST /api/purchases
Content-Type: application/json
Authorization: Bearer {token}

{
    "branch_id": 1,
    "supplier_id": 5,
    "purchase_date": "2026-02-17",
    "items": [
        {
            "product_id": 10,
            "quantity": 50,
            "unit_price": 500
        }
    ]
}
```

### Create Sale (Decreases Stock)

```
POST /api/sales
Content-Type: application/json
Authorization: Bearer {token}

{
    "branch_id": 1,
    "sale_date": "2026-02-17",
    "payment_method": "cash",
    "items": [
        {
            "product_id": 10,
            "quantity": 5,
            "unit_price": 700
        }
    ]
}
```

### Check Stock Availability

```
GET /api/branch-stocks?branch_id=1&product_id=10
Authorization: Bearer {token}
```

---

## Best Practices

### ✅ DO:

- Always use transactions for stock operations
- Lock records before reading/updating
- Validate stock before creating sales
- Handle exceptions gracefully
- Return detailed error messages
- Test concurrent scenarios

### ❌ DON'T:

- Update stock without transactions
- Skip validation checks
- Allow negative stock values
- Update stock directly without controllers
- Hold locks for long operations
- Ignore race conditions

---

## Troubleshooting

### Issue: Deadlock Detected

**Cause**: Two transactions waiting for each other's locks  
**Solution**:

- Order operations consistently
- Minimize transaction duration
- Retry failed transactions

### Issue: Stock Mismatch

**Cause**: Manual database updates or failed transactions  
**Solution**:

- Always use API for stock operations
- Check application logs
- Run stock reconciliation script

### Issue: Performance Degradation

**Cause**: Too many concurrent operations  
**Solution**:

- Monitor slow query log
- Check index usage
- Consider queue-based processing for batch operations

---

## Summary

✅ **Automatic Stock Management**: Purchases increase, sales decrease  
✅ **Data Integrity**: Database transactions wrap all operations  
✅ **Concurrency Safe**: Pessimistic locking prevents race conditions  
✅ **Validation**: Prevents insufficient stock sales  
✅ **Error Handling**: Detailed messages with quantities  
✅ **Reversible**: Delete operations properly restore stock  
✅ **Branch-Specific**: Each branch tracks independent inventory  
✅ **Production-Ready**: Follows Laravel best practices

---

_Last Updated: February 17, 2026_  
_Version: 1.0_  
_Status: Production Ready_
