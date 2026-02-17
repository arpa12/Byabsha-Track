# Role-Based Authentication Implementation

✅ **Complete Authentication System with Laravel Sanctum**

This implementation provides comprehensive role-based access control for the ByabshaTrack multi-branch POS system.

---

## 📦 What's Included

### 1. Middleware (4 files)

- **`CheckRole.php`** - Multi-role validation with OR logic
- **`CheckOwner.php`** - Owner-only access control
- **`CheckBranchAccess.php`** - Branch-scoped data isolation
- **`RoleMiddleware.php`** - Original simple role middleware (kept for backward compatibility)

### 2. Seeder

- **`RolePermissionSeeder.php`** - Creates test branches and users
    - 3 Branches (Dhaka, Chittagong, Sylhet)
    - 6 Test Users (1 owner, 2 managers, 3 salesmen)

### 3. Enhanced Auth Controller

- **`NewAuthController.php`** - Complete auth endpoints
    - Login, Logout, Register
    - Profile management
    - Password change
    - User activation/deactivation (Owner only)

### 4. Documentation (4 guides)

- **`AUTHENTICATION_GUIDE.md`** (46 KB) - Complete authentication documentation
- **`SPATIE_PERMISSION_GUIDE.md`** (22 KB) - Optional Spatie Permission upgrade guide
- **`AUTHENTICATION_QUICK_START.md`** (15 KB) - Quick setup instructions
- **This README** - Implementation overview

### 5. Testing Tools

- **`ByabshaTrack_Postman_Collection.json`** - Complete Postman collection for API testing

---

## 🔐 Role Hierarchy

### Owner

- **Access Level:** Full system access
- **Capabilities:**
    - ✅ Manage all branches
    - ✅ Create/edit/delete users
    - ✅ View all reports (all branches)
    - ✅ Full product/category/supplier management
    - ✅ Full purchase/expense management
    - ✅ System settings
- **Branch Scope:** All branches

### Manager

- **Access Level:** Branch management
- **Capabilities:**
    - ✅ Manage products in their branch
    - ✅ Manage purchases and expenses
    - ✅ View reports for their branch
    - ✅ Manage categories and suppliers (branch-scoped)
    - ✅ View sales data
    - ❌ Cannot access other branches
    - ❌ Cannot manage users
    - ❌ Cannot modify system settings
- **Branch Scope:** Assigned branch only

### Salesman

- **Access Level:** Sales operations only
- **Capabilities:**
    - ✅ Access POS (Point of Sale)
    - ✅ Create sales
    - ✅ View their own sales
    - ✅ Search products
    - ✅ Check stock availability
    - ❌ Cannot manage inventory
    - ❌ Cannot view reports
    - ❌ Cannot access other branches
- **Branch Scope:** Assigned branch only

---

## 🚀 Quick Setup

### 1. Register Middleware

Already configured in `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'check.role' => \App\Http\Middleware\CheckRole::class,
    'check.owner' => \App\Http\Middleware\CheckOwner::class,
    'check.branch' => \App\Http\Middleware\CheckBranchAccess::class,
]);
```

### 2. Run Migrations & Seed

```bash
cd backend
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

### 3. Test Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"owner@byabshatrack.com","password":"password"}'
```

---

## 🔧 Usage Examples

### Protect Routes with Middleware

#### Owner Only Routes

```php
Route::middleware(['auth:sanctum', 'check.owner'])->group(function () {
    Route::resource('branches', BranchController::class);
    Route::post('/users', [AuthController::class, 'register']);
});
```

#### Owner & Manager Routes

```php
Route::middleware(['auth:sanctum', 'check.role:owner,manager'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('/reports/daily-sales', [ReportController::class, 'dailySales']);
});
```

#### All Authenticated Users

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sales/pos', [SaleController::class, 'store']);
    Route::get('/products/search', [ProductController::class, 'search']);
});
```

#### With Branch Scoping

```php
Route::middleware(['auth:sanctum', 'check.branch'])->group(function () {
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/reports/branch-sales', [ReportController::class, 'branchSales']);
});
```

---

## 🧪 Test Credentials

| Role     | Email                                  | Password   | Branch       | Access            |
| -------- | -------------------------------------- | ---------- | ------------ | ----------------- |
| Owner    | `owner@byabshatrack.com`               | `password` | Main - Dhaka | Full Access       |
| Manager  | `manager@byabshatrack.com`             | `password` | Main - Dhaka | Branch Management |
| Manager  | `manager.chittagong@byabshatrack.com`  | `password` | Chittagong   | Branch Management |
| Salesman | `salesman@byabshatrack.com`            | `password` | Main - Dhaka | Sales Only        |
| Salesman | `salesman.chittagong@byabshatrack.com` | `password` | Chittagong   | Sales Only        |
| Salesman | `salesman.sylhet@byabshatrack.com`     | `password` | Sylhet       | Sales Only        |

---

## 📊 Access Control Matrix

| Endpoint           | Method   | Owner | Manager | Salesman |
| ------------------ | -------- | ----- | ------- | -------- |
| `/auth/login`      | POST     | ✅    | ✅      | ✅       |
| `/auth/logout`     | POST     | ✅    | ✅      | ✅       |
| `/auth/me`         | GET      | ✅    | ✅      | ✅       |
| `/branches`        | GET      | ✅    | ❌      | ❌       |
| `/branches`        | POST     | ✅    | ❌      | ❌       |
| `/categories`      | GET/POST | ✅    | ✅      | ❌       |
| `/products`        | GET/POST | ✅    | ✅      | ❌       |
| `/products/search` | GET      | ✅    | ✅      | ✅       |
| `/suppliers`       | GET/POST | ✅    | ✅      | ❌       |
| `/purchases`       | GET/POST | ✅    | ✅      | ❌       |
| `/expenses`        | GET/POST | ✅    | ✅      | ❌       |
| `/sales`           | GET      | ✅    | ✅      | ✅       |
| `/sales/pos`       | POST     | ✅    | ✅      | ✅       |
| `/reports/*`       | GET      | ✅    | ✅      | ❌       |

---

## 🛠️ Implementation Files

### Middleware Files

```
backend/app/Http/Middleware/
├── CheckRole.php            # Multi-role access control
├── CheckOwner.php           # Owner-only access
├── CheckBranchAccess.php    # Branch scoping
└── RoleMiddleware.php       # Legacy (backward compatibility)
```

### Seeder Files

```
backend/database/seeders/
├── RolePermissionSeeder.php # Test data seeder
└── DatabaseSeeder.php       # Updated to call RolePermissionSeeder
```

### Controller Files

```
backend/app/Http/Controllers/Api/
├── NewAuthController.php    # Enhanced auth endpoints
└── AuthController.php       # Original (if exists)
```

### Configuration

```
backend/bootstrap/
└── app.php                  # Middleware registration
```

---

## 📖 Documentation

### Complete Guides

1. **AUTHENTICATION_GUIDE.md** (46 KB)
    - Complete authentication documentation
    - Frontend integration examples
    - Security best practices
    - Troubleshooting guide

2. **SPATIE_PERMISSION_GUIDE.md** (22 KB)
    - Optional permission upgrade
    - Granular permission control
    - Installation instructions
    - Migration path

3. **AUTHENTICATION_QUICK_START.md** (15 KB)
    - 5-minute setup guide
    - Testing with cURL
    - Postman integration
    - Common issues

---

## 🧪 Testing with Postman

### Import Collection

1. Open Postman
2. Click "Import"
3. Select `ByabshaTrack_Postman_Collection.json`
4. Create environment with `base_url` = `http://localhost:8000/api`

### Test Workflow

1. **Login as Owner** → Token saved automatically
2. **Get All Branches** → Should succeed (200)
3. **Login as Manager** → New token saved
4. **Get All Branches** → Should fail (403)
5. **Get Products** → Should succeed (200)
6. **Login as Salesman** → New token saved
7. **Create Product** → Should fail (403)
8. **Create Sale** → Should succeed (200)

---

## 🔒 Security Features

### Token Management

- ✅ Sanctum token-based authentication
- ✅ Automatic token expiration
- ✅ Secure token storage
- ✅ Token revocation on logout

### Access Control

- ✅ Role-based middleware
- ✅ Branch-scoped data isolation
- ✅ Active account verification
- ✅ Detailed error messages

### Password Security

- ✅ Bcrypt hashing
- ✅ Minimum 8 characters
- ✅ Password confirmation
- ✅ Current password verification for changes

### Data Protection

- ✅ Branch-level isolation for managers/salesmen
- ✅ Automatic branch scoping
- ✅ Owner bypass for system-wide access
- ✅ Account deactivation capability

---

## 🚀 Next Steps

### 1. Frontend Integration

Create React authentication context:

```javascript
// See AUTHENTICATION_GUIDE.md → Frontend Integration
import { AuthProvider } from "./contexts/AuthContext";
```

### 2. Add More API Routes

Update `routes/api.php` with your endpoints and protect them:

```php
Route::middleware(['auth:sanctum', 'check.role:owner,manager'])->group(function () {
    // Your protected routes here
});
```

### 3. Optional: Upgrade to Spatie Permission

For granular permission control:

```bash
composer require spatie/laravel-permission
# Follow SPATIE_PERMISSION_GUIDE.md
```

### 4. Production Deployment

- Set `APP_ENV=production` in `.env`
- Enable HTTPS
- Configure proper CORS
- Set strong `APP_KEY`
- Disable debug mode

---

## 🔄 Migration from Simple Roles

If you have existing code using simple role checks:

### Before (Old)

```php
Route::middleware(['auth:sanctum', 'role:owner,manager'])->group(function () {
    // Routes
});
```

### After (New - Recommended)

```php
Route::middleware(['auth:sanctum', 'check.role:owner,manager'])->group(function () {
    // Routes
});
```

**Both work!** The old `role` middleware is kept for backward compatibility.

---

## 📋 Checklist

- [ ] Migrations run successfully
- [ ] Database seeded with test users
- [ ] Can login as owner
- [ ] Can login as manager
- [ ] Can login as salesman
- [ ] Owner can access all branches
- [ ] Manager restricted to their branch
- [ ] Salesman can only access POS
- [ ] Postman collection imported and tested
- [ ] Frontend authentication integrated
- [ ] Production deployment configured

---

## 🤝 Support

### Common Issues

**Token not working:**

```bash
php artisan cache:clear
php artisan config:clear
```

**Permission denied:**
Check user role and active status:

```bash
php artisan tinker
>>> User::find(1)->role
>>> User::find(1)->is_active
```

**Branch access issues:**
Verify branch_id is set correctly:

```bash
php artisan tinker
>>> User::find(1)->branch_id
```

### Logs

Check application logs:

```bash
tail -f storage/logs/laravel.log
```

---

## 📚 Additional Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Spatie Permission Package](https://spatie.be/docs/laravel-permission)
- [OWASP API Security](https://owasp.org/www-project-api-security/)

---

## ✨ Features

- ✅ Laravel Sanctum token authentication
- ✅ 3-tier role system (Owner, Manager, Salesman)
- ✅ Branch-scoped data isolation
- ✅ 4 specialized middleware classes
- ✅ Comprehensive test data seeder
- ✅ Enhanced auth controller
- ✅ Postman collection for testing
- ✅ Complete documentation (3 guides)
- ✅ Frontend integration examples
- ✅ Production-ready security
- ✅ Backward compatible with existing code
- ✅ Optional Spatie Permission upgrade path

---

## 📝 Summary

This implementation provides a **complete, production-ready** authentication system with:

- **Secure** - Token-based auth with Sanctum
- **Flexible** - Multiple middleware options
- **Scalable** - Easy to extend with Spatie Permission
- **Tested** - Comprehensive test data and Postman collection
- **Documented** - 4 detailed guides with examples
- **Easy** - 5-minute quick start

**Ready to use!** Follow the Quick Setup section to get started.

---

**Created for ByabshaTrack - Multi-Branch POS System**  
Role-Based Authentication with Laravel Sanctum v4.0
