# ByabshaTrack (ব্যবসা ট্র্যাক) - Backend API

Multi-branch POS and Inventory Management System API built with Laravel

## Features

- **Multi-branch Support**: Manage multiple branches with separate inventory
- **Product Management**: CRUD operations for products with categories
- **Purchase Management**: Supplier-based purchase orders with automatic stock updates
- **Sales/POS System**: Complete point-of-sale with automatic stock deduction
- **Inventory Tracking**: Real-time branch-wise stock management
- **Profit Reports**: Daily and monthly profit calculations
- **Role-based Access**: Owner, Manager, and Salesman roles
- **RESTful API**: Clean API structure with proper validation
- **Authentication**: Laravel Sanctum token-based authentication

## Tech Stack

- **Framework**: Laravel 11.x
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Validation**: Form Requests
- **Architecture**: Repository pattern with service layer

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (for assets)

### Setup Steps

1. **Clone the repository**

```bash
cd backend
```

2. **Install dependencies**

```bash
composer install
```

3. **Environment configuration**

```bash
cp .env.example .env
```

4. **Update .env file with your database credentials**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=byabsha_track
DB_USERNAME=root
DB_PASSWORD=
```

5. **Generate application key**

```bash
php artisan key:generate
```

6. **Run migrations and seeders**

```bash
php artisan migrate --seed
```

This will create:

- 3 branches (Main, Chittagong, Sylhet)
- 3 users (Owner, Manager, Salesman)
- Sample categories and suppliers

7. **Start the development server**

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## Default Credentials

### Owner Account

- Email: `owner@byabshatrack.com`
- Password: `password`
- Access: Full system access

### Manager Account

- Email: `manager@byabshatrack.com`
- Password: `password`
- Access: Branch management, purchases, sales, reports

### Salesman Account

- Email: `salesman@byabshatrack.com`
- Password: `password`
- Access: Sales/POS only

## API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

#### Register

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "salesman",
  "branch_id": 1
}
```

#### Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "owner@byabshatrack.com",
  "password": "password"
}
```

#### Get User Info

```http
GET /api/me
Authorization: Bearer {token}
```

### Branches (Owner only)

```http
GET    /api/branches
POST   /api/branches
GET    /api/branches/{id}
PUT    /api/branches/{id}
DELETE /api/branches/{id}
```

### Categories (Owner, Manager)

```http
GET    /api/categories
POST   /api/categories
GET    /api/categories/{id}
PUT    /api/categories/{id}
DELETE /api/categories/{id}
```

### Products (Owner, Manager)

```http
GET    /api/products
GET    /api/products/low-stock?branch_id=1
POST   /api/products
GET    /api/products/{id}
PUT    /api/products/{id}
DELETE /api/products/{id}
```

### Suppliers (Owner, Manager)

```http
GET    /api/suppliers
POST   /api/suppliers
GET    /api/suppliers/{id}
PUT    /api/suppliers/{id}
DELETE /api/suppliers/{id}
```

### Purchases (Owner, Manager)

```http
GET    /api/purchases?branch_id=1
POST   /api/purchases
GET    /api/purchases/{id}
PUT    /api/purchases/{id}
DELETE /api/purchases/{id}
```

**Purchase Request Body:**

```json
{
    "branch_id": 1,
    "supplier_id": 1,
    "purchase_date": "2024-01-15",
    "discount": 100,
    "tax": 50,
    "paid_amount": 5000,
    "note": "Sample purchase",
    "items": [
        {
            "product_id": 1,
            "quantity": 10,
            "unit_price": 500
        }
    ]
}
```

### Sales (All authenticated)

```http
GET    /api/sales?branch_id=1
POST   /api/sales
GET    /api/sales/{id}
PUT    /api/sales/{id}
DELETE /api/sales/{id}
```

**Sale Request Body:**

```json
{
    "branch_id": 1,
    "customer_name": "Customer Name",
    "customer_phone": "01700000000",
    "sale_date": "2024-01-15",
    "discount": 50,
    "tax": 20,
    "paid_amount": 1000,
    "payment_method": "cash",
    "note": "Sample sale",
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "unit_price": 600
        }
    ]
}
```

### Expenses (Owner, Manager)

```http
GET    /api/expenses?branch_id=1
GET    /api/expenses/categories
POST   /api/expenses
GET    /api/expenses/{id}
PUT    /api/expenses/{id}
DELETE /api/expenses/{id}
```

### Reports (Owner, Manager)

#### Dashboard

```http
GET /api/reports/dashboard?branch_id=1
```

#### Daily Profit

```http
GET /api/reports/daily-profit?date=2024-01-15&branch_id=1
```

#### Monthly Profit

```http
GET /api/reports/monthly-profit?month=1&year=2024&branch_id=1
```

#### Sales Summary

```http
GET /api/reports/sales-summary?start_date=2024-01-01&end_date=2024-01-31&branch_id=1
```

#### Purchase Summary

```http
GET /api/reports/purchase-summary?start_date=2024-01-01&end_date=2024-01-31&branch_id=1
```

#### Top Selling Products

```http
GET /api/reports/top-selling-products?start_date=2024-01-01&end_date=2024-01-31&branch_id=1&limit=10
```

## Database Structure

### Main Tables

- `branches` - Branch information
- `users` - User accounts with roles
- `categories` - Product categories (hierarchical)
- `products` - Product master data
- `suppliers` - Supplier information
- `branch_stocks` - Branch-wise inventory
- `purchases` - Purchase orders
- `purchase_items` - Purchase order items
- `sales` - Sales transactions
- `sale_items` - Sale transaction items
- `expenses` - Expense records

## Business Logic

### Stock Management

- **On Purchase**: Stock automatically increases in the specified branch
- **On Sale**: Stock automatically decreases from the specified branch
- **Stock Validation**: Sales are prevented if insufficient stock

### Profit Calculation

- Profit = (Selling Price - Purchase Price) × Quantity
- Calculated per item and stored in `sale_items`
- Reports aggregate profit minus expenses

### Role Permissions

- **Owner**: Full access to all features
- **Manager**: Can manage inventory, purchases, sales, and view reports
- **Salesman**: Can only create and view sales

## Development

### Running Tests

```bash
php artisan test
```

### Code Quality

```bash
./vendor/bin/pint  # Format code
```

### Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Set up proper file permissions
7. Configure web server (Apache/Nginx)

## License

This project is proprietary software.

## Support

For support, email support@byabshatrack.com
