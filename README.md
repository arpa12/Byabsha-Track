# Byabsha Track — Business Tracking System

> A modular, bilingual business tracking system built with **Laravel 12** and **nwidart/laravel-modules**. Track shops, products, sales, restocks, capital — and generate rich reports with PDF export.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)
- [Module Architecture](#module-architecture)
  - [Auth](#1-auth-module)
  - [Landing](#2-landing-module)
  - [Dashboard](#3-dashboard-module)
  - [Shop](#4-shop-module)
  - [Product](#5-product-module)
  - [Sale](#6-sale-module)
  - [Restock](#7-restock-module)
  - [Capital](#8-capital-module)
  - [Report](#9-report-module)
- [Database Schema](#database-schema)
- [Entity Relationships](#entity-relationships)
- [Routes Reference](#routes-reference)
- [Internationalization (i18n)](#internationalization-i18n)
- [Cross-Module Dependencies](#cross-module-dependencies)
- [Project Structure](#project-structure)
- [Scripts](#scripts)
- [License](#license)

---

## Features

| Category | Details |
|---|---|
| **Multi-Shop** | Create and manage multiple independent shops |
| **Product Inventory** | Track products with category, brand, purchase/sale prices, and stock levels |
| **Sales Tracking** | Record per-product sales with automatic profit calculation and stock deduction |
| **Restock Management** | Record restocks with per-unit cost; stock auto-incremented |
| **Capital Tracking** | Auto-calculated capital per shop (`Σ stock × purchase_price`) |
| **Dashboard** | Real-time metrics: today's sales, profit, monthly profit, low-stock alerts |
| **Reports & PDF Export** | Sales, product, shop comparison, daily P&L, monthly P&L — all exportable to PDF |
| **Bilingual UI** | Full English and Bengali (বাংলা) language support |
| **Responsive Design** | Bootstrap 5.3 with mobile sidebar, Bootstrap Icons |
| **Service Layer** | Clean business logic separation via service classes |
| **Modular Architecture** | 9 self-contained modules, each with its own controllers, models, routes, views, and translations |

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 12 (PHP ≥ 8.2) |
| **Module System** | nwidart/laravel-modules v12 |
| **Database** | MySQL |
| **Frontend** | Bootstrap 5.3 + Bootstrap Icons (CDN) |
| **Build Tools** | Vite 7 + Tailwind CSS 4 |
| **PDF Export** | barryvdh/laravel-dompdf v3.1 |
| **Testing** | Pest v4 |
| **Session/Cache/Queue** | Database driver |

---

## Installation

### Prerequisites

- PHP ≥ 8.2 with required extensions
- Composer
- Node.js & npm
- MySQL

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/arpa12/Byabsha-Track.git
   cd Byabsha-Track
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set up database** — edit `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=byabshatrack
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```
   Visit: `http://localhost:8000`

> **Quick setup**: `composer setup` runs steps 2–6 automatically.

---

## Usage

1. **Register / Login** — Access the app through the auth pages
2. **Create Shops** — Add your business locations
3. **Add Products** — Add products to each shop with category, brand, purchase price, sale price, and initial stock
4. **Record Sales** — Create sales per product; stock is automatically deducted, profit calculated
5. **Record Restocks** — Restock products; stock is automatically incremented, capital recalculated
6. **View Dashboard** — Monitor today's sales, profit, monthly trends, and low-stock items
7. **Generate Reports** — Analyze sales, products, shop comparisons, daily/monthly P&L; export to PDF

---

## Module Architecture

All modules live under `Modules/` and are managed via `nwidart/laravel-modules`. Every module is self-contained with its own controllers, models, services, routes, views, and config.

**Active modules** (all enabled):

| # | Module | Purpose |
|---|---|---|
| 1 | Auth | Login / logout authentication |
| 2 | Landing | Public landing page |
| 3 | Dashboard | Business metrics overview |
| 4 | Shop | Shop CRUD management |
| 5 | Product | Product inventory CRUD |
| 6 | Sale | Sales transactions with stock management |
| 7 | Restock | Restock recording with stock updates |
| 8 | Capital | Auto-calculated shop capital tracking |
| 9 | Report | Reports dashboard + PDF exports |

---

### 1. Auth Module

Handles user authentication with guest/auth middleware.

| Component | Details |
|---|---|
| **Controller** | `AuthController` — `showLogin()`, `login()`, `logout()` |
| **Views** | `login.blade.php` |
| **Models** | None (uses core `User` model) |

**Routes:**

| Method | Path | Name | Middleware |
|---|---|---|---|
| GET | `/login` | `login` | guest |
| POST | `/login` | `login.submit` | guest |
| POST | `/logout` | `logout` | auth |

---

### 2. Landing Module

Public-facing landing page — no authentication required.

| Component | Details |
|---|---|
| **Controller** | `LandingController` — `index()` |
| **Views** | `index.blade.php` |

**Routes:**

| Method | Path | Name |
|---|---|---|
| GET | `/` | `landing.index` |

---

### 3. Dashboard Module

Real-time business overview using data from Shop, Product, Sale, and Capital modules.

| Component | Details |
|---|---|
| **Controller** | `DashboardController` — `index()`, `shopDetails($shopId)` |
| **Service** | `DashboardService` |
| **Views** | `index.blade.php`, `partials/shop-details.blade.php` |

**DashboardService methods:**

| Method | Returns |
|---|---|
| `getShopMetrics()` | Per-shop: today_sales, today_profit, monthly_profit, total_capital |
| `getOverallMetrics()` | total_shops, total_products, total_sales_today, total_revenue_today, total_profit_today, low_stock_count (≤ 5 units) |
| `getTodaySales($shopId)` | Sum of today's `total_amount` |
| `getTodayProfit($shopId)` | Sum of today's `profit` |
| `getMonthlyProfit($shopId)` | Sum of current month's `profit` |
| `getTotalCapital($shopId)` | Capital from `shop_capitals` table |

**Routes** (prefix: `/dashboard`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/dashboard` | `dashboard.index` |
| GET | `/dashboard/shop-details/{shop}` | `dashboard.shop-details` |

---

### 4. Shop Module

Create and manage multiple business shops.

| Component | Details |
|---|---|
| **Model** | `Shop` — fillable: `name` |
| **Controller** | `ShopController` — full CRUD (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) |
| **Views** | `index`, `create`, `edit`, `show` |

**Relationships:**
- `products()` → hasMany(Product)
- `sales()` → hasMany(Sale)

**Routes** (prefix: `/shops`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/shops` | `shop.index` |
| GET | `/shops/create` | `shop.create` |
| POST | `/shops` | `shop.store` |
| GET | `/shops/{id}` | `shop.show` |
| GET | `/shops/{id}/edit` | `shop.edit` |
| PUT | `/shops/{id}` | `shop.update` |
| DELETE | `/shops/{id}` | `shop.destroy` |

**API:** `apiResource('shops')` under `auth:sanctum`, prefix `v1`

---

### 5. Product Module

Track products with pricing, stock, and categorization.

| Component | Details |
|---|---|
| **Model** | `Product` — fillable: `shop_id`, `name`, `category`, `brand`, `purchase_price`, `sale_price`, `stock_quantity` |
| **Controller** | `ProductController` — full CRUD with capital recalculation |
| **Views** | `index`, `create`, `edit`, `show` |

**Relationships:**
- `shop()` → belongsTo(Shop)
- `sales()` → hasMany(Sale)
- `restocks()` → hasMany(Restock)

**Validation:** `shop_id` (exists:shops), `name` (required, max:255), `category` / `brand` (nullable), `purchase_price` / `sale_price` (numeric, ≥ 0), `stock_quantity` (integer, ≥ 0)

**Routes** (prefix: `/products`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/products` | `product.index` |
| GET | `/products/create` | `product.create` |
| POST | `/products` | `product.store` |
| GET | `/products/{id}` | `product.show` |
| GET | `/products/{id}/edit` | `product.edit` |
| PUT | `/products/{id}` | `product.update` |
| DELETE | `/products/{id}` | `product.destroy` |

**API:** `apiResource('products')` under `auth:sanctum`, prefix `v1`

> **Note:** Creating, updating, or deleting a product automatically triggers capital recalculation for the associated shop.

---

### 6. Sale Module

Record sales transactions with automatic stock management and profit calculation.

| Component | Details |
|---|---|
| **Model** | `Sale` — fillable: `shop_id`, `product_id`, `quantity`, `sale_price`, `total_amount`, `profit`, `sale_date` |
| **Controller** | `SaleController` — full CRUD with DB transactions |
| **Views** | `index`, `create`, `edit`, `show` |

**Relationships:**
- `shop()` → belongsTo(Shop)
- `product()` → belongsTo(Product)

**Business Logic:**
- `total_amount = quantity × sale_price`
- `profit = (sale_price − purchase_price) × quantity`
- **Create:** stock decremented, capital recalculated
- **Update:** old stock restored → new stock deducted, capital recalculated
- **Delete:** stock restored, capital recalculated
- All operations wrapped in DB transactions for data integrity

**Routes** (prefix: `/sales`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/sales` | `sale.index` |
| GET | `/sales/create` | `sale.create` |
| POST | `/sales` | `sale.store` |
| GET | `/sales/{id}` | `sale.show` |
| GET | `/sales/{id}/edit` | `sale.edit` |
| PUT | `/sales/{id}` | `sale.update` |
| DELETE | `/sales/{id}` | `sale.destroy` |

---

### 7. Restock Module

Record product restocks with per-unit cost tracking.

| Component | Details |
|---|---|
| **Model** | `Restock` — fillable: `product_id`, `shop_id`, `quantity`, `purchase_price_per_unit`, `total_cost`, `restock_date`, `note` |
| **Service** | `RestockService` — `getRestocks()`, `storeRestock()` |
| **Controller** | `RestockController` — `index`, `create`, `store`, `productsByShop` (JSON API) |
| **Views** | `index`, `create` |

**Relationships:**
- `product()` → belongsTo(Product)
- `shop()` → belongsTo(Shop)

**Business Logic:**
- `total_cost = quantity × purchase_price_per_unit`
- Stock is automatically incremented on restock
- Shop capital is auto-recalculated
- Supports filtering by shop and date range

**Routes** (prefix: `/restocks`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/restocks` | `restock.index` |
| GET | `/restocks/create` | `restock.create` |
| POST | `/restocks` | `restock.store` |
| GET | `/restocks/products-by-shop` | `restock.products-by-shop` |

---

### 8. Capital Module

Automatic capital tracking per shop based on current inventory value.

| Component | Details |
|---|---|
| **Model** | `Capital` — table: `shop_capitals`, fillable: `shop_id`, `total_capital` |
| **Service** | `CapitalService` |
| **Controller** | `CapitalController` — `index`, `updateAll`, `updateShop` |
| **Views** | `index` |

**CapitalService methods:**

| Method | Description |
|---|---|
| `calculateShopCapital($shopId)` | Returns `Σ(stock_quantity × purchase_price)` for all products in shop |
| `updateShopCapital($shopId)` | Calculates & upserts capital record |
| `updateAllShopsCapital()` | Recalculates capital for every shop |
| `getAllShopCapitals()` | All capitals with shop + products eager-loaded |
| `getShopCapital($shopId)` | Single capital record |

**Formula:** `Total Capital = Σ (stock_quantity × purchase_price)` for all products in a shop

**Routes** (prefix: `/capitals`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/capitals` | `capital.index` |
| POST | `/capitals/update-all` | `capital.update-all` |
| POST | `/capitals/update-shop/{shopId}` | `capital.update-shop` |

> Capital is automatically recalculated when products, sales, or restocks are created/updated/deleted.

---

### 9. Report Module

Comprehensive reporting with filtering, pagination, and PDF export.

| Component | Details |
|---|---|
| **Service** | `ReportService` (437 lines — the largest service) |
| **Controller** | `ReportController` — 11 methods (6 views + 5 PDF exports) |
| **Views** | 6 HTML views + 5 PDF templates |

**ReportService methods:**

| Method | Description |
|---|---|
| `getSalesReport($filters)` | Full sales list with totals |
| `getSalesSummary($filters)` | Aggregated: total_transactions, total_quantity_sold, total_revenue, total_profit, averages |
| `getSalesByShop($filters)` | Per-shop: total_sales, total_quantity, total_revenue, total_profit |
| `getTopSellingProducts($filters, $limit)` | Top products by quantity sold, with revenue & profit |
| `getStockSummary($filters)` | total_products, stock_value, potential_revenue, potential_profit, low/out-of-stock counts |
| `getProductReport($filters)` | Products with `total_units_sold` and `total_revenue` |
| `getShopComparison($filters)` | Per-shop: products count, stock_value, total_sales, revenue, profit, profit_margin |
| `getDailySales($filters)` | Daily aggregates (last 30 days) |
| `getDailyProfitLoss($filters)` | Day-by-day P&L for a month with per-shop breakdown |
| `getMonthlyProfitLoss($filters)` | Month-by-month P&L for a year with per-shop breakdown, profit margins |

**Report types:**

| Report | View Route | PDF Export Route |
|---|---|---|
| Overview (summary + charts) | `report.index` | — |
| Sales Transactions | `report.sales` | `report.export.sales-pdf` |
| Product / Stock | `report.products` | `report.export.products-pdf` |
| Shop Comparison | `report.shops` | `report.export.shops-pdf` |
| Daily P&L | `report.daily` | `report.export.daily-pdf` |
| Monthly P&L | `report.monthly` | `report.export.monthly-pdf` |

**Routes** (prefix: `/reports`, middleware: `auth`):

| Method | Path | Name |
|---|---|---|
| GET | `/reports` | `report.index` |
| GET | `/reports/sales` | `report.sales` |
| GET | `/reports/products` | `report.products` |
| GET | `/reports/shops` | `report.shops` |
| GET | `/reports/daily` | `report.daily` |
| GET | `/reports/monthly` | `report.monthly` |
| GET | `/reports/export/daily-pdf` | `report.export.daily-pdf` |
| GET | `/reports/export/monthly-pdf` | `report.export.monthly-pdf` |
| GET | `/reports/export/sales-pdf` | `report.export.sales-pdf` |
| GET | `/reports/export/products-pdf` | `report.export.products-pdf` |
| GET | `/reports/export/shops-pdf` | `report.export.shops-pdf` |

**All reports support filters:** `shop_id`, `date_from`, `date_to` (some also: `month`, `year`)

---

## Database Schema

### Core Tables

| Table | Description |
|---|---|
| `users` | id, name, email (unique), email_verified_at, password, remember_token, timestamps |
| `password_reset_tokens` | email (PK), token, created_at |
| `sessions` | id (PK), user_id, ip_address, user_agent, payload, last_activity |
| `cache` / `cache_locks` | Standard Laravel cache tables |
| `jobs` / `job_batches` / `failed_jobs` | Standard Laravel queue tables |

### Application Tables

| Table | Module | Columns |
|---|---|---|
| `shops` | Shop | `id`, `name`, `timestamps` |
| `products` | Product | `id`, `name`, `category` (nullable), `brand` (nullable), `purchase_price` (decimal 10,2), `sale_price` (decimal 10,2), `stock_quantity` (int), `shop_id` (FK → shops), `timestamps` |
| `sales` | Sale | `id`, `shop_id` (FK → shops), `product_id` (FK → products), `quantity` (int), `sale_price` (decimal 10,2), `sale_date` (date), `total_amount` (decimal 10,2), `profit` (decimal 10,2), `timestamps` |
| `shop_capitals` | Capital | `id`, `shop_id` (FK → shops, cascade), `total_capital` (decimal 15,2, default 0), `timestamps` |
| `restocks` | Restock | `id`, `product_id` (FK → products, cascade), `shop_id` (FK → shops, cascade), `quantity` (unsigned int), `purchase_price_per_unit` (decimal 12,2), `total_cost` (decimal 14,2), `restock_date` (date), `note` (text, nullable), `timestamps` |

---

## Entity Relationships

```
┌──────────┐       ┌──────────────┐       ┌──────────┐
│   Shop   │──1:N──│   Product    │──1:N──│   Sale   │
│          │       │              │       │          │
│          │──1:N──│              │──1:N──│          │
│          │       └──────────────┘       └──────────┘
│          │──1:N──┌──────────────┐
│          │       │   Restock    │
│          │       └──────────────┘
│          │──1:1──┌──────────────┐
│          │       │   Capital    │
└──────────┘       │(shop_capitals)│
                   └──────────────┘
```

- **Shop** → has many **Products**, **Sales**, **Restocks** and one **Capital**
- **Product** → belongs to **Shop**, has many **Sales** and **Restocks**
- **Sale** → belongs to **Shop** and **Product**
- **Restock** → belongs to **Shop** and **Product**
- **Capital** → belongs to **Shop** (auto-calculated from product inventory)

---

## Routes Reference

### Public Routes

| Method | Path | Name | Description |
|---|---|---|---|
| GET | `/` | `landing.index` | Landing page |
| GET | `/login` | `login` | Login form |
| POST | `/login` | `login.submit` | Login action |
| GET | `/language/{locale}` | `language.switch` | Switch locale (en/bn) |

### Authenticated Routes (middleware: `auth`)

| Method | Path | Name |
|---|---|---|
| POST | `/logout` | `logout` |
| GET | `/dashboard` | `dashboard.index` |
| GET | `/dashboard/shop-details/{shop}` | `dashboard.shop-details` |
| GET/POST/PUT/DELETE | `/shops/*` | `shop.*` (resource) |
| GET/POST/PUT/DELETE | `/products/*` | `product.*` (resource) |
| GET/POST/PUT/DELETE | `/sales/*` | `sale.*` (resource) |
| GET/POST | `/restocks/*` | `restock.*` |
| GET/POST | `/capitals/*` | `capital.*` |
| GET | `/reports/*` | `report.*` (6 views + 5 PDF exports) |

### API Routes (middleware: `auth:sanctum`, prefix: `/api/v1`)

- `apiResource('shops')` — Shop CRUD
- `apiResource('products')` — Product CRUD
- `apiResource('dashboards')` — Dashboard

---

## Internationalization (i18n)

**Supported languages:** English (`en`) and Bengali (`bn`)

Language switching is handled via:
- `LanguageController@switch` — stores selected locale in session
- `SetLocale` middleware — reads session and sets `App::setLocale()`

All translations are centralized in `resources/lang/{en,bn}/`:

| File | Key Count | Coverage |
|---|---|---|
| `app.php` | ~64 | Layout, sidebar, common actions, alerts |
| `auth.php` | ~16 | Login page |
| `capital.php` | ~30 | Capital management UI |
| `dashboard.php` | ~42 | Dashboard metrics & formulas |
| `landing.php` | ~45 | Landing page content |
| `product.php` | ~114 | Product CRUD labels |
| `report.php` | ~130 | All report views & PDF export labels |
| `restock.php` | ~50 | Restock form/list labels |
| `sale.php` | ~75 | Sale CRUD labels & calculations |
| `shop.php` | ~40 | Shop CRUD labels |

**Total:** ~600+ translation keys across all modules.

---

## Cross-Module Dependencies

```
ProductController ──uses──→ CapitalService
SaleController    ──uses──→ CapitalService
RestockService    ──uses──→ CapitalService
DashboardService  ──reads──→ Shop, Product, Sale, Capital models
ReportService     ──reads──→ Shop, Product, Sale models
```

- **CapitalService** is the central hub — injected into Product, Sale, and Restock operations
- Capital auto-recalculates on any product, sale, or restock change
- Report & Dashboard modules are **read-only** consumers of data from other modules

---

## Project Structure

```
byabshaTrack/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── LanguageController.php      # Language switching
│   │   └── Middleware/
│   │       └── SetLocale.php               # Locale middleware
│   ├── Models/
│   │   └── User.php                        # Core user model
│   └── Providers/
│       └── AppServiceProvider.php
├── Modules/
│   ├── Auth/                               # Authentication
│   │   ├── Http/Controllers/AuthController.php
│   │   ├── routes/web.php
│   │   └── resources/views/login.blade.php
│   ├── Landing/                            # Public landing page
│   │   ├── Http/Controllers/LandingController.php
│   │   ├── routes/web.php
│   │   └── resources/views/index.blade.php
│   ├── Dashboard/                          # Business dashboard
│   │   ├── Http/Controllers/DashboardController.php
│   │   ├── Services/DashboardService.php
│   │   ├── routes/web.php
│   │   └── resources/views/
│   ├── Shop/                               # Shop management
│   │   ├── Http/Controllers/ShopController.php
│   │   ├── Models/Shop.php
│   │   ├── database/migrations/
│   │   ├── routes/web.php
│   │   └── resources/views/
│   ├── Product/                            # Product inventory
│   │   ├── Http/Controllers/ProductController.php
│   │   ├── Models/Product.php
│   │   ├── database/migrations/
│   │   ├── routes/web.php
│   │   └── resources/views/
│   ├── Sale/                               # Sales transactions
│   │   ├── Http/Controllers/SaleController.php
│   │   ├── Models/Sale.php
│   │   ├── database/migrations/
│   │   ├── routes/web.php
│   │   └── resources/views/
│   ├── Restock/                            # Restocking
│   │   ├── Http/Controllers/RestockController.php
│   │   ├── Models/Restock.php
│   │   ├── Services/RestockService.php
│   │   ├── database/migrations/
│   │   ├── routes/web.php
│   │   └── resources/views/
│   ├── Capital/                            # Capital tracking
│   │   ├── Http/Controllers/CapitalController.php
│   │   ├── Models/Capital.php
│   │   ├── Services/CapitalService.php
│   │   ├── database/migrations/
│   │   ├── routes/web.php
│   │   └── resources/views/
│   └── Report/                             # Reports & PDF
│       ├── Http/Controllers/ReportController.php
│       ├── Services/ReportService.php
│       ├── routes/web.php
│       └── resources/views/
│           ├── index.blade.php
│           ├── sales.blade.php
│           ├── products.blade.php
│           ├── shops.blade.php
│           ├── daily.blade.php
│           ├── monthly.blade.php
│           └── pdf/                        # PDF templates
│               ├── daily-pdf.blade.php
│               ├── monthly-pdf.blade.php
│               ├── sales-pdf.blade.php
│               ├── products-pdf.blade.php
│               └── shops-pdf.blade.php
├── resources/
│   ├── views/layouts/app.blade.php         # Main layout (header + sidebar)
│   └── lang/
│       ├── en/                             # English translations
│       └── bn/                             # Bengali translations
├── config/
├── database/migrations/                    # Core migrations
├── routes/web.php                          # Language switch route
├── composer.json
├── package.json
└── vite.config.js
```

---

## Scripts

| Command | Description |
|---|---|
| `composer setup` | Full setup: install deps, generate key, migrate, build assets |
| `composer dev` | Start server + queue + Vite concurrently |
| `composer test` | Clear config cache and run Pest tests |
| `npm run dev` | Start Vite dev server |
| `npm run build` | Build production assets |

---

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
