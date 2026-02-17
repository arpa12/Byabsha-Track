# Spatie Laravel Permission Integration Guide

## Overview

This guide shows how to integrate [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) package into ByabshaTrack for more granular permission control.

**Current System:** Simple role-based access (owner, manager, salesman)  
**With Spatie:** Role + Permission-based access with fine-grained control

---

## Benefits of Spatie Permission

### Current System Limitations

- ❌ Fixed role-based permissions
- ❌ Cannot grant custom permissions to specific users
- ❌ Hard to add new permission levels
- ❌ Permissions defined in code (middleware)

### With Spatie Permission

- ✅ Dynamic role and permission management
- ✅ Assign multiple roles to users
- ✅ Grant specific permissions to users
- ✅ Database-driven permissions
- ✅ Permission caching for performance
- ✅ Easy to add new roles/permissions without code changes

---

## Installation Steps

### Step 1: Install Package

```bash
cd backend
composer require spatie/laravel-permission
```

Expected output:

```
Using version ^6.0 for spatie/laravel-permission
...
Package manifest generated successfully.
```

---

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

This creates:

- `config/permission.php` - Configuration file
- Migration file for roles and permissions tables

---

### Step 3: Run Migrations

```bash
php artisan migrate
```

This creates 5 new tables:

- `roles` - Store roles (owner, manager, salesman)
- `permissions` - Store permissions (create-product, view-report, etc.)
- `model_has_roles` - User-role relationships
- `model_has_permissions` - User-permission relationships
- `role_has_permissions` - Role-permission relationships

---

### Step 4: Update User Model

Edit `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Keep for backward compatibility
        'branch_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Branch relationship
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relationships
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Role helper methods (backward compatibility + Spatie)
    public function hasRoleName(string $role): bool
    {
        return $this->role === $role;
    }

    public function isOwner(): bool
    {
        return $this->hasRoleName('owner') || $this->hasRole('owner');
    }

    public function isManager(): bool
    {
        return $this->hasRoleName('manager') || $this->hasRole('manager');
    }

    public function isSalesman(): bool
    {
        return $this->hasRoleName('salesman') || $this->hasRole('salesman');
    }
}
```

---

### Step 5: Create Permission Seeder

Create `database/seeders/SpatieRolePermissionSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Branch permissions
            'view-branches',
            'create-branch',
            'edit-branch',
            'delete-branch',

            // User permissions
            'view-users',
            'create-user',
            'edit-user',
            'delete-user',

            // Category permissions
            'view-categories',
            'create-category',
            'edit-category',
            'delete-category',

            // Product permissions
            'view-products',
            'create-product',
            'edit-product',
            'delete-product',

            // Supplier permissions
            'view-suppliers',
            'create-supplier',
            'edit-supplier',
            'delete-supplier',

            // Purchase permissions
            'view-purchases',
            'create-purchase',
            'edit-purchase',
            'delete-purchase',

            // Sale permissions
            'view-sales',
            'create-sale',
            'edit-sale',
            'delete-sale',

            // Expense permissions
            'view-expenses',
            'create-expense',
            'edit-expense',
            'delete-expense',

            // Report permissions
            'view-reports',
            'view-all-branch-reports',
            'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions

        // Owner role - Full access
        $ownerRole = Role::create(['name' => 'owner', 'guard_name' => 'web']);
        $ownerRole->givePermissionTo(Permission::all());

        // Manager role - Branch management
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo([
            'view-categories', 'create-category', 'edit-category',
            'view-products', 'create-product', 'edit-product',
            'view-suppliers', 'create-supplier', 'edit-supplier',
            'view-purchases', 'create-purchase', 'edit-purchase',
            'view-sales', 'create-sale', 'view-sales',
            'view-expenses', 'create-expense', 'edit-expense',
            'view-reports',
        ]);

        // Salesman role - Sales only
        $salesmanRole = Role::create(['name' => 'salesman', 'guard_name' => 'web']);
        $salesmanRole->givePermissionTo([
            'view-products',
            'view-sales',
            'create-sale',
        ]);

        // Assign roles to existing users
        $owner = User::where('email', 'owner@byabshatrack.com')->first();
        if ($owner) {
            $owner->assignRole('owner');
        }

        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $manager->assignRole('manager');
        }

        $salesmen = User::where('role', 'salesman')->get();
        foreach ($salesmen as $salesman) {
            $salesman->assignRole('salesman');
        }

        $this->command->info('Spatie roles and permissions created successfully!');
    }
}
```

---

### Step 6: Run Permission Seeder

```bash
php artisan db:seed --class=SpatieRolePermissionSeeder
```

---

### Step 7: Update Middleware

Create new middleware `app/Http/Middleware/CheckPermission.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (!$request->user()->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated.'
            ], 403);
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($request->user()->can($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to perform this action.',
            'required_permissions' => $permissions
        ], 403);
    }
}
```

---

### Step 8: Register Middleware

Update `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'check.role' => \App\Http\Middleware\CheckRole::class,
        'check.owner' => \App\Http\Middleware\CheckOwner::class,
        'check.branch' => \App\Http\Middleware\CheckBranchAccess::class,
        'check.permission' => \App\Http\Middleware\CheckPermission::class, // New
    ]);
})
```

---

## Usage Examples

### Route Protection with Permissions

```php
// routes/api.php

// Products - require view-products permission
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['auth:sanctum', 'check.permission:view-products']);

// Create product - require create-product permission
Route::post('/products', [ProductController::class, 'store'])
    ->middleware(['auth:sanctum', 'check.permission:create-product']);

// Reports - require view-reports permission
Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])
    ->middleware(['auth:sanctum', 'check.permission:view-reports']);

// Branches - require create-branch permission (owner only)
Route::post('/branches', [BranchController::class, 'store'])
    ->middleware(['auth:sanctum', 'check.permission:create-branch']);
```

---

### Controller-Level Authorization

```php
// app/Http/Controllers/Api/ProductController.php

public function index(Request $request)
{
    // Check permission in controller
    if (!$request->user()->can('view-products')) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to view products.'
        ], 403);
    }

    $products = Product::with('category', 'branch')->get();

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}

public function store(Request $request)
{
    // Using authorize method (throws 403 if fails)
    $this->authorize('create', Product::class);

    // ... create product
}
```

---

### Checking Permissions in Code

```php
// Check if user has permission
if ($user->can('create-product')) {
    // User can create products
}

// Check if user has any permission
if ($user->hasAnyPermission(['create-product', 'edit-product'])) {
    // User can create or edit products
}

// Check if user has all permissions
if ($user->hasAllPermissions(['view-products', 'create-product'])) {
    // User can view and create products
}

// Check if user has role
if ($user->hasRole('owner')) {
    // User is owner
}

// Check if user has any role
if ($user->hasAnyRole(['owner', 'manager'])) {
    // User is owner or manager
}
```

---

### Assign Permissions to Existing Users

```php
// Assign role to user
$user = User::find(1);
$user->assignRole('manager');

// Give specific permission to user
$user->givePermissionTo('view-all-branch-reports');

// Remove permission
$user->revokePermissionTo('view-all-branch-reports');

// Sync permissions (replace all permissions)
$user->syncPermissions(['view-products', 'create-sale']);

// Remove role
$user->removeRole('salesman');
```

---

### Assign Permissions to Roles

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get role
$managerRole = Role::findByName('manager');

// Give permission to role
$managerRole->givePermissionTo('export-reports');

// Remove permission from role
$managerRole->revokePermissionTo('delete-purchase');

// Sync role permissions
$managerRole->syncPermissions([
    'view-products',
    'create-product',
    'edit-product',
]);
```

---

## Frontend Integration

### Updated Auth Context with Permissions

```javascript
// src/contexts/AuthContext.jsx

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [permissions, setPermissions] = useState([]);

    const fetchUser = async () => {
        try {
            const response = await api.get("/auth/me");
            const userData = response.data.data;
            setUser(userData);

            // Fetch user permissions
            const permResponse = await api.get("/auth/permissions");
            setPermissions(permResponse.data.data.permissions);
        } catch (error) {
            // Handle error
        }
    };

    const hasPermission = (permission) => {
        return permissions.includes(permission);
    };

    const hasAnyPermission = (perms) => {
        return perms.some((perm) => permissions.includes(perm));
    };

    return (
        <AuthContext.Provider
            value={{
                user,
                permissions,
                hasPermission,
                hasAnyPermission,
                // ... other methods
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}
```

### Conditional Rendering with Permissions

```javascript
import { useAuth } from "../contexts/AuthContext";

function ProductManagement() {
    const { hasPermission } = useAuth();

    return (
        <div>
            <h1>Products</h1>

            {hasPermission("create-product") && (
                <button onClick={handleCreate}>Add Product</button>
            )}

            <ProductList />

            {hasPermission("edit-product") && <EditButton />}

            {hasPermission("delete-product") && <DeleteButton />}
        </div>
    );
}
```

---

## API Endpoints for Permission Management

Add these controller methods:

```php
// app/Http/Controllers/Api/AuthController.php

/**
 * Get current user permissions
 */
public function permissions(Request $request)
{
    $user = $request->user();

    return response()->json([
        'success' => true,
        'data' => [
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]
    ]);
}

/**
 * Check if user has specific permission
 */
public function checkPermission(Request $request)
{
    $request->validate([
        'permission' => 'required|string'
    ]);

    $hasPermission = $request->user()->can($request->permission);

    return response()->json([
        'success' => true,
        'data' => [
            'permission' => $request->permission,
            'has_permission' => $hasPermission
        ]
    ]);
}
```

Add routes:

```php
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/permissions', [AuthController::class, 'permissions']);
    Route::post('/auth/check-permission', [AuthController::class, 'checkPermission']);
});
```

---

## Permission List

### Branch Permissions

- `view-branches` - View all branches
- `create-branch` - Create new branch
- `edit-branch` - Edit branch details
- `delete-branch` - Delete branch

### User Permissions

- `view-users` - View all users
- `create-user` - Create new user
- `edit-user` - Edit user details
- `delete-user` - Delete user

### Category Permissions

- `view-categories` - View categories
- `create-category` - Create category
- `edit-category` - Edit category
- `delete-category` - Delete category

### Product Permissions

- `view-products` - View products
- `create-product` - Create product
- `edit-product` - Edit product
- `delete-product` - Delete product

### Supplier Permissions

- `view-suppliers` - View suppliers
- `create-supplier` - Create supplier
- `edit-supplier` - Edit supplier
- `delete-supplier` - Delete supplier

### Purchase Permissions

- `view-purchases` - View purchases
- `create-purchase` - Create purchase
- `edit-purchase` - Edit purchase
- `delete-purchase` - Delete purchase

### Sale Permissions

- `view-sales` - View sales
- `create-sale` - Create sale (POS)
- `edit-sale` - Edit sale
- `delete-sale` - Delete sale

### Expense Permissions

- `view-expenses` - View expenses
- `create-expense` - Create expense
- `edit-expense` - Edit expense
- `delete-expense` - Delete expense

### Report Permissions

- `view-reports` - View branch reports
- `view-all-branch-reports` - View reports for all branches
- `export-reports` - Export reports to PDF/Excel

---

## Cache Clearing

Spatie Permission caches permissions for performance. Clear cache after changes:

```bash
# Clear permission cache
php artisan permission:cache-reset

# Or use in code
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

---

## Testing

### Test Permission Assignment

```bash
php artisan tinker
```

```php
// Get user
$user = User::find(1);

// Check roles
$user->getRoleNames(); // Collection of role names

// Check permissions
$user->getAllPermissions(); // Collection of Permission models
$user->getAllPermissions()->pluck('name'); // ['view-products', 'create-product', ...]

// Test permission
$user->can('create-product'); // true or false

// Assign role
$user->assignRole('manager');

// Give permission
$user->givePermissionTo('export-reports');
```

---

## Migration Path

### Option 1: Keep Both Systems (Recommended Initially)

- Keep `role` field in users table
- Add Spatie permissions alongside
- Gradually migrate routes to use permissions
- Remove role field later

### Option 2: Full Migration

- Assign Spatie roles to all users
- Update all middleware to use permissions
- Remove `role` field from users table
- Update all controllers

---

## Rollback Instructions

If you need to remove Spatie Permission:

```bash
# Remove package
composer remove spatie/laravel-permission

# Drop tables (create migration)
php artisan make:migration drop_permission_tables

# In migration:
Schema::dropIfExists('role_has_permissions');
Schema::dropIfExists('model_has_roles');
Schema::dropIfExists('model_has_permissions');
Schema::dropIfExists('permissions');
Schema::dropIfExists('roles');

# Run migration
php artisan migrate
```

---

## Performance Considerations

### Permission Caching

Spatie caches permissions automatically. Configure in `config/permission.php`:

```php
'cache' => [
    'expiration_time' => \DateInterval::createFromDateString('24 hours'),
    'key' => 'spatie.permission.cache',
    'store' => 'default',
],
```

### Eager Loading

Always eager load roles and permissions:

```php
// Load users with roles and permissions
$users = User::with('roles', 'permissions')->get();

// Load users with specific role
$managers = User::role('manager')->get();

// Load users with permission
$canCreateProduct = User::permission('create-product')->get();
```

---

## Conclusion

Spatie Laravel Permission adds powerful, flexible permission management to ByabshaTrack:

✅ Dynamic role and permission assignment  
✅ Database-driven permissions (no code changes)  
✅ Fine-grained access control  
✅ Easy integration with existing auth  
✅ Performance optimized with caching  
✅ Production-ready

Use this for enterprise-level permission management where granular control is needed.

---

## Resources

- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Sanctum Documentation](https://laravel.com/docs/sanctum)
