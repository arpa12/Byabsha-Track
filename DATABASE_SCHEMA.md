# Database Schema Documentation

## Overview

Complete database schema for ByabshaTrack multi-branch POS and inventory management system with optimized indexes for high performance.

---

## Tables Summary

| #   | Table          | Purpose                     | Foreign Keys                    | Indexes |
| --- | -------------- | --------------------------- | ------------------------------- | ------- |
| 1   | branches       | Store branch information    | -                               | 2       |
| 2   | users          | User authentication & roles | branch_id                       | 4       |
| 3   | categories     | Product categorization      | parent_id (self)                | 4       |
| 4   | suppliers      | Supplier management         | -                               | 4       |
| 5   | products       | Product master data         | category_id                     | 5       |
| 6   | branch_stocks  | Branch-wise inventory       | branch_id, product_id           | 3       |
| 7   | purchases      | Purchase orders             | branch_id, supplier_id, user_id | 8       |
| 8   | purchase_items | Purchase line items         | purchase_id, product_id         | 3       |
| 9   | sales          | Sales transactions          | branch_id, user_id              | 9       |
| 10  | sale_items     | Sale line items             | sale_id, product_id             | 4       |
| 11  | expenses       | Branch expenses             | branch_id, user_id              | 6       |

**Total Tables**: 11  
**Total Foreign Keys**: 15  
**Total Performance Indexes**: 52

---

## Detailed Schema

### 1. branches

**Purpose**: Store information about different business branches

```sql
CREATE TABLE branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    INDEX idx_branches_is_active (is_active),
    INDEX idx_branches_code (code)
);
```

**Indexes Purpose**:

- `is_active`: Filter active branches quickly
- `code`: Fast branch lookup by code

---

### 2. users

**Purpose**: User authentication and role-based access control

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('owner', 'manager', 'salesman') DEFAULT 'salesman',
    branch_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,

    INDEX idx_users_role (role),
    INDEX idx_users_branch_id (branch_id),
    INDEX idx_users_is_active (is_active),
    INDEX idx_users_branch_role (branch_id, role)
);
```

**Foreign Keys**:

- `branch_id` → branches.id (SET NULL on delete)

**Indexes Purpose**:

- `role`: Quick role-based filtering
- `branch_id`: Fast branch staff lookups
- `is_active`: Filter active users
- `branch_id, role`: Composite index for branch-wise staff queries

---

### 3. categories

**Purpose**: Hierarchical product categorization

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE,

    INDEX idx_categories_slug (slug),
    INDEX idx_categories_parent_id (parent_id),
    INDEX idx_categories_is_active (is_active),
    INDEX idx_categories_parent_active (parent_id, is_active)
);
```

**Foreign Keys**:

- `parent_id` → categories.id (CASCADE on delete) - Self-referencing for hierarchy

**Indexes Purpose**:

- `slug`: Fast category lookup by slug
- `parent_id`: Quick subcategory retrieval
- `is_active`: Filter active categories
- `parent_id, is_active`: Composite for hierarchical active categories

---

### 4. suppliers

**Purpose**: Supplier information and balance tracking

```sql
CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(255) NOT NULL,
    address TEXT NULL,
    opening_balance DECIMAL(15,2) DEFAULT 0.00,
    current_balance DECIMAL(15,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    INDEX idx_suppliers_phone (phone),
    INDEX idx_suppliers_email (email),
    INDEX idx_suppliers_is_active (is_active),
    INDEX idx_suppliers_current_balance (current_balance)
);
```

**Indexes Purpose**:

- `phone`: Quick supplier lookup by phone
- `email`: Fast email-based search
- `is_active`: Filter active suppliers
- `current_balance`: Find suppliers with due balances

---

### 5. products

**Purpose**: Master product information

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(255) UNIQUE NOT NULL,
    barcode VARCHAR(255) UNIQUE NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    description TEXT NULL,
    unit VARCHAR(255) DEFAULT 'pcs',
    purchase_price DECIMAL(15,2) DEFAULT 0.00,
    selling_price DECIMAL(15,2) DEFAULT 0.00,
    minimum_stock DECIMAL(10,2) DEFAULT 0.00,
    image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,

    INDEX idx_products_category_id (category_id),
    INDEX idx_products_is_active (is_active),
    INDEX idx_products_sku (sku),
    INDEX idx_products_barcode (barcode),
    INDEX idx_products_category_active (category_id, is_active)
);
```

**Foreign Keys**:

- `category_id` → categories.id (CASCADE on delete)

**Indexes Purpose**:

- `category_id`: Fast category-wise product retrieval
- `is_active`: Filter active products
- `sku`: Quick SKU-based lookup
- `barcode`: Fast barcode scanning
- `category_id, is_active`: Composite for filtered category queries

---

### 6. branch_stocks (product_stocks)

**Purpose**: Branch-wise inventory tracking

```sql
CREATE TABLE branch_stocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,

    UNIQUE KEY unique_branch_product (branch_id, product_id),
    INDEX idx_branch_stocks_branch_id (branch_id),
    INDEX idx_branch_stocks_product_id (product_id),
    INDEX idx_branch_stocks_quantity (quantity)
);
```

**Foreign Keys**:

- `branch_id` → branches.id (CASCADE on delete)
- `product_id` → products.id (CASCADE on delete)

**Constraints**:

- UNIQUE(branch_id, product_id) - One stock record per product per branch

**Indexes Purpose**:

- `branch_id`: Fast branch inventory queries
- `product_id`: Quick product availability check
- `quantity`: Low stock alerts and queries

---

### 7. purchases

**Purpose**: Purchase order header information

```sql
CREATE TABLE purchases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(255) UNIQUE NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    purchase_date DATE NOT NULL,
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    discount DECIMAL(15,2) DEFAULT 0.00,
    tax DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    paid_amount DECIMAL(15,2) DEFAULT 0.00,
    due_amount DECIMAL(15,2) DEFAULT 0.00,
    payment_status ENUM('paid', 'partial', 'unpaid') DEFAULT 'unpaid',
    note TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_purchases_invoice_no (invoice_no),
    INDEX idx_purchases_branch_id (branch_id),
    INDEX idx_purchases_supplier_id (supplier_id),
    INDEX idx_purchases_user_id (user_id),
    INDEX idx_purchases_purchase_date (purchase_date),
    INDEX idx_purchases_payment_status (payment_status),
    INDEX idx_purchases_branch_date (branch_id, purchase_date),
    INDEX idx_purchases_supplier_status (supplier_id, payment_status)
);
```

**Foreign Keys**:

- `branch_id` → branches.id (CASCADE on delete)
- `supplier_id` → suppliers.id (CASCADE on delete)
- `user_id` → users.id (CASCADE on delete)

**Indexes Purpose**:

- `invoice_no`: Fast invoice lookup
- `branch_id`: Branch-wise purchase reports
- `supplier_id`: Supplier purchase history
- `user_id`: Track purchases by user
- `purchase_date`: Date-based filtering
- `payment_status`: Find pending payments
- `branch_id, purchase_date`: Branch daily/monthly reports
- `supplier_id, payment_status`: Supplier due payments

---

### 8. purchase_items

**Purpose**: Line items for each purchase

```sql
CREATE TABLE purchase_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(15,2) NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,

    INDEX idx_purchase_items_purchase_id (purchase_id),
    INDEX idx_purchase_items_product_id (product_id),
    INDEX idx_purchase_items_purchase_product (purchase_id, product_id)
);
```

**Foreign Keys**:

- `purchase_id` → purchases.id (CASCADE on delete)
- `product_id` → products.id (CASCADE on delete)

**Indexes Purpose**:

- `purchase_id`: Quick retrieval of all items in a purchase
- `product_id`: Find all purchases of a product
- `purchase_id, product_id`: Composite for item lookups

---

### 9. sales

**Purpose**: Sales transaction header information

```sql
CREATE TABLE sales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(255) UNIQUE NOT NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    customer_name VARCHAR(255) NULL,
    customer_phone VARCHAR(255) NULL,
    sale_date DATE NOT NULL,
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    discount DECIMAL(15,2) DEFAULT 0.00,
    tax DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    paid_amount DECIMAL(15,2) DEFAULT 0.00,
    due_amount DECIMAL(15,2) DEFAULT 0.00,
    payment_status ENUM('paid', 'partial', 'unpaid') DEFAULT 'paid',
    payment_method ENUM('cash', 'card', 'mobile_banking', 'bank_transfer') DEFAULT 'cash',
    note TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_sales_invoice_no (invoice_no),
    INDEX idx_sales_branch_id (branch_id),
    INDEX idx_sales_user_id (user_id),
    INDEX idx_sales_sale_date (sale_date),
    INDEX idx_sales_payment_status (payment_status),
    INDEX idx_sales_payment_method (payment_method),
    INDEX idx_sales_customer_phone (customer_phone),
    INDEX idx_sales_branch_date (branch_id, sale_date),
    INDEX idx_sales_user_date (user_id, sale_date)
);
```

**Foreign Keys**:

- `branch_id` → branches.id (CASCADE on delete)
- `user_id` → users.id (CASCADE on delete)

**Indexes Purpose**:

- `invoice_no`: Fast invoice lookup
- `branch_id`: Branch-wise sales reports
- `user_id`: Salesman performance tracking
- `sale_date`: Date-based filtering
- `payment_status`: Track pending payments
- `payment_method`: Payment method analysis
- `customer_phone`: Customer purchase history
- `branch_id, sale_date`: Branch daily/monthly reports
- `user_id, sale_date`: Salesman performance reports

---

### 10. sale_items

**Purpose**: Line items for each sale with profit calculation

```sql
CREATE TABLE sale_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity DECIMAL(15,2) NOT NULL,
    unit_cost DECIMAL(15,2) NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    profit DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,

    INDEX idx_sale_items_sale_id (sale_id),
    INDEX idx_sale_items_product_id (product_id),
    INDEX idx_sale_items_profit (profit),
    INDEX idx_sale_items_sale_product (sale_id, product_id)
);
```

**Foreign Keys**:

- `sale_id` → sales.id (CASCADE on delete)
- `product_id` → products.id (CASCADE on delete)

**Indexes Purpose**:

- `sale_id`: Quick retrieval of all items in a sale
- `product_id`: Find all sales of a product (top sellers)
- `profit`: Profit analysis and reports
- `sale_id, product_id`: Composite for item lookups

**Profit Calculation**:

```
profit = (unit_price - unit_cost) × quantity
```

---

### 11. expenses

**Purpose**: Track branch expenses for net profit calculation

```sql
CREATE TABLE expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    amount DECIMAL(15,2) NOT NULL,
    expense_date DATE NOT NULL,
    category VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_expenses_branch_id (branch_id),
    INDEX idx_expenses_user_id (user_id),
    INDEX idx_expenses_expense_date (expense_date),
    INDEX idx_expenses_category (category),
    INDEX idx_expenses_branch_date (branch_id, expense_date),
    INDEX idx_expenses_category_date (category, expense_date)
);
```

**Foreign Keys**:

- `branch_id` → branches.id (CASCADE on delete)
- `user_id` → users.id (CASCADE on delete)

**Indexes Purpose**:

- `branch_id`: Branch-wise expense reports
- `user_id`: Track who recorded the expense
- `expense_date`: Date-based filtering
- `category`: Category-wise expense analysis
- `branch_id, expense_date`: Branch daily/monthly expense reports
- `category, expense_date`: Category trend analysis

---

## Relationships Diagram

```
branches (1) ─┬─→ (N) users
              ├─→ (N) branch_stocks
              ├─→ (N) purchases
              ├─→ (N) sales
              └─→ (N) expenses

categories (1) ─┬─→ (N) products
                └─→ (N) categories (self-referencing)

suppliers (1) ──→ (N) purchases

products (1) ─┬─→ (N) branch_stocks
              ├─→ (N) purchase_items
              └─→ (N) sale_items

users (1) ─┬─→ (N) purchases
           ├─→ (N) sales
           └─→ (N) expenses

purchases (1) ──→ (N) purchase_items

sales (1) ──→ (N) sale_items
```

---

## Data Flow

### Purchase Flow

```
1. Create Purchase (purchases table)
2. Add Purchase Items (purchase_items table)
3. Automatically Increase Stock (branch_stocks table)
4. Update Supplier Balance (suppliers table)
```

### Sales Flow

```
1. Create Sale (sales table)
2. Add Sale Items with Cost & Price (sale_items table)
3. Calculate Profit per Item
4. Automatically Decrease Stock (branch_stocks table)
5. Validate Stock Availability
```

### Profit Calculation

```
Gross Profit = SUM(sale_items.profit)
Total Expenses = SUM(expenses.amount)
Net Profit = Gross Profit - Total Expenses
```

---

## Index Strategy

### Single Column Indexes (27)

Used for simple filtering and sorting operations:

- Status fields (is_active, payment_status)
- Identifier fields (code, sku, barcode, invoice_no)
- Date fields (purchase_date, sale_date, expense_date)
- Foreign key fields (for JOIN optimization)
- Numeric fields for range queries (quantity, current_balance, profit)

### Composite Indexes (25)

Used for complex queries with multiple WHERE conditions:

- **branch_id + date**: Branch-wise daily/monthly reports
- **category_id + is_active**: Filtered category queries
- **supplier_id + payment_status**: Supplier due/paid purchases
- **user_id + sale_date**: Salesman performance tracking
- **parent_id + is_active**: Hierarchical category queries

### Unique Indexes (6)

Ensure data integrity:

- branches.code
- products.sku
- products.barcode
- purchases.invoice_no
- sales.invoice_no
- categories.slug
- branch_stocks(branch_id, product_id)

---

## Performance Considerations

### Query Optimization

1. **Foreign key indexes**: All foreign keys have indexes for fast JOINs
2. **Date indexes**: Enable fast date range queries for reports
3. **Status indexes**: Quick filtering of active/inactive records
4. **Composite indexes**: Optimize common multi-column queries

### Expected Performance

- **Product search by SKU/Barcode**: O(log n) - indexed
- **Branch inventory lookup**: O(log n) - composite index
- **Daily sales report**: O(log n) - date + branch composite index
- **Supplier due payments**: O(log n) - composite index
- **Top selling products**: O(n log n) - aggregation with index

### Recommendations

1. Regularly analyze slow queries using `EXPLAIN`
2. Monitor index usage with `SHOW INDEX FROM table_name`
3. Consider partitioning large tables (sales, sale_items) by date
4. Implement database replication for read-heavy operations
5. Use Redis/Memcached for frequently accessed data

---

## Migration Files

All migration files are located in:

```
backend/database/migrations/
```

### Migration Order (Sequential)

1. `2024_01_01_000000_create_branches_table.php`
2. `2024_01_01_000001_add_role_to_users_table.php`
3. `2024_01_02_000000_create_suppliers_table.php`
4. `2024_01_03_000000_create_categories_table.php`
5. `2024_01_04_000000_create_products_table.php`
6. `2024_01_05_000000_create_branch_stocks_table.php`
7. `2024_01_06_000000_create_purchases_table.php`
8. `2024_01_07_000000_create_purchase_items_table.php`
9. `2024_01_08_000000_create_sales_table.php`
10. `2024_01_09_000000_create_sale_items_table.php`
11. `2024_01_10_000000_create_expenses_table.php`

---

## Running Migrations

### Fresh Migration

```bash
cd backend
php artisan migrate:fresh --seed
```

### Regular Migration

```bash
php artisan migrate
```

### Rollback

```bash
php artisan migrate:rollback
```

### Check Status

```bash
php artisan migrate:status
```

---

## Database Size Estimates

### Expected Data Volume (1 Year)

- **Branches**: ~10 records (< 1KB)
- **Users**: ~50 records (< 5KB)
- **Categories**: ~100 records (< 10KB)
- **Suppliers**: ~200 records (< 20KB)
- **Products**: ~5,000 records (~500KB)
- **Branch Stocks**: ~15,000 records (~1.5MB)
- **Purchases**: ~3,600 records (~500KB)
- **Purchase Items**: ~36,000 records (~5MB)
- **Sales**: ~36,000 records (~5MB)
- **Sale Items**: ~150,000 records (~25MB)
- **Expenses**: ~3,600 records (~500KB)

**Total Estimated Size**: ~40MB data + ~20MB indexes = **~60MB/year**

### Growth Projection

- **Year 1**: 60MB
- **Year 2**: 120MB
- **Year 3**: 180MB
- **Year 5**: 300MB

The database will remain highly performant for at least 5-10 years with proper indexing.

---

## Backup Strategy

### Daily Backups

```bash
mysqldump -u root -p byabsha_track > backup_$(date +%Y%m%d).sql
```

### Weekly Full Backup + Daily Incremental

```bash
# Full backup (Sunday)
mysqldump --single-transaction -u root -p byabsha_track > full_backup.sql

# Incremental (Mon-Sat)
mysqldump --single-transaction --flush-logs -u root -p byabsha_track > incremental.sql
```

---

## Security Considerations

1. **Password Hashing**: User passwords hashed with bcrypt
2. **Soft Deletes**: Data recovery possible with soft deletes
3. **Foreign Key Constraints**: Data integrity maintained
4. **Enum Validation**: Role and status fields validated at DB level
5. **Unique Constraints**: Prevent duplicate SKUs, invoices, etc.

---

## Maintenance Tasks

### Monthly

- Analyze slow queries
- Check index usage
- Optimize tables: `OPTIMIZE TABLE table_name`
- Review disk space usage

### Quarterly

- Review and update indexes based on query patterns
- Archive old data (>2 years)
- Performance benchmarking

### Yearly

- Full database audit
- Schema optimization review
- Capacity planning

---

_Last Updated: February 17, 2026_
_Schema Version: 1.0_
_Total Indexes: 52_
