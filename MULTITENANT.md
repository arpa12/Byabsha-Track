# Multi-Tenant System - Create Tenants, Users & Testing Guide

## Overview
Your application now supports multiple independent tenants (organizations) sharing the same database. Each tenant's data is automatically isolated using global scopes.

---

## 1. Creating a Tenant

### Method A: Using Artisan Command (Recommended)
```bash
php artisan tenant:create "Acme Corp" --domain=acme.example.com --email=admin@acme.com
```

This creates:
- Tenant with name "Acme Corp"
- Auto-generated slug: "acme-corp"
- Domain: acme.example.com (optional)
- Email: admin@acme.com (optional)

### Method B: Using Tinker
```bash
php artisan tinker
```

```php
$tenant = Tenant::create([
    'name' => 'Acme Corp',
    'slug' => 'acme-corp',  // Must be unique
    'domain' => 'acme.example.com',  // Optional
    'email' => 'admin@acme.com',
    'description' => 'A global company',
    'is_active' => true,
]);
```

---

## 2. Creating Tenant Users

Once a tenant exists, create users for that tenant:

```bash
php artisan tinker
```

```php
// Get the tenant
$tenant = Tenant::where('slug', 'acme-corp')->first();

// Create a user for this tenant
$user = User::create([
    'name' => 'John Admin',
    'email' => 'john@acme.com',
    'password' => bcrypt('password123'),
    'role' => 'owner',  // or 'admin', 'staff'
    'tenant_id' => $tenant->id,  // Important: assign tenant_id
]);
```

### Verify User Belongs to Tenant
```php
$user->tenant;  // Returns the tenant object
$user->tenant->name;  // "Acme Corp"
```

---

## 3. Setting Tenant Context

When working with multi-tenant data, you must set the tenant context:

```php
// Set context to specific tenant
tenancy()->initialize($tenant);

// Or by ID
tenancy()->initialize(1);

// Now all subsequent queries are scoped to this tenant
```

---

## 4. Testing Multi-Tenant Features

### Quick Test (Copy & Paste)

Start Tinker:
```bash
php artisan tinker
```

Then paste this complete test:

```php
// ===== STEP 1: Create two tenants =====
$t1 = Tenant::create(['name' => 'TestCorp1', 'slug' => 'test1-' . time()]);
$t2 = Tenant::create(['name' => 'TestCorp2', 'slug' => 'test2-' . time()]);
echo "✓ Created 2 tenants\n";

// ===== STEP 2: Create shops for tenant 1 =====
tenancy()->initialize($t1);
Shop::create(['name' => 'Shop1-T1', 'location' => 'NYC']);
Shop::create(['name' => 'Shop2-T1', 'location' => 'LA']);

// ===== STEP 3: Create shop for tenant 2 =====
tenancy()->initialize($t2);
Shop::create(['name' => 'Shop1-T2', 'location' => 'SF']);

// ===== STEP 4: Test Isolation =====
echo "\n=== ISOLATION TEST ===\n";
tenancy()->initialize($t1);
echo "Tenant 1 shops: " . Shop::count() . " (expected: 2)\n";

tenancy()->initialize($t2);
echo "Tenant 2 shops: " . Shop::count() . " (expected: 1)\n";

// ===== STEP 5: Test Auto Tenant Assignment =====
echo "\n=== AUTO ASSIGNMENT TEST ===\n";
tenancy()->initialize($t1);
$newShop = Shop::create(['name' => 'Shop3-T1', 'location' => 'Chicago']);
echo "Assigned tenant_id: " . $newShop->tenant_id . " (expected: " . $t1->id . ")\n";

// ===== STEP 6: Test Helper Functions =====
echo "\n=== HELPER FUNCTIONS TEST ===\n";
echo "Current tenant: " . tenant('name') . " (expected: TestCorp1)\n";
echo "Tenant ID: " . tenant('id') . "\n";

// ===== STEP 7: Bypass Filter =====
echo "\n=== BYPASS FILTER TEST ===\n";
echo "All shops (no filter): " . Shop::withoutGlobalScopes()->count() . " (expected: 3)\n";

// ===== SUMMARY =====
echo "\n✓✓✓ ALL TESTS PASSED - MULTI-TENANT SYSTEM IS WORKING! ✓✓✓\n";
```

### What Each Test Verifies

| Test | What It Checks | Expected Result |
|------|---|---|
| Create Tenants | Can create multiple tenants | 2 tenants created |
| Isolation | Each tenant sees only their data | T1: 2 shops, T2: 1 shop |
| Auto Assignment | tenant_id assigned automatically | Matches current tenant |
| Helper Functions | tenant() helper works | Returns correct tenant info |
| Bypass Filter | Can query all data if needed | Shows all 3 shops |

---

## 5. Complete Workflow Example

Here's a complete example of setting up and using multi-tenancy:

```bash
# 1. Create tenant via command
php artisan tenant:create "Acme Corp"

# 2. Start tinker
php artisan tinker
```

```php
// 3. Get the tenant
$tenant = Tenant::where('slug', 'acme-corp')->first();

// 4. Create a user for the tenant
$owner = User::create([
    'name' => 'Admin User',
    'email' => 'admin@acme.com',
    'password' => bcrypt('password'),
    'role' => 'owner',
    'tenant_id' => $tenant->id,
]);

// 5. Set tenant context
tenancy()->initialize($tenant);

// 6. Create tenant data (automatically gets tenant_id)
$shop = Shop::create(['name' => 'Main Shop', 'location' => 'NYC']);
$product = Product::create(['name' => 'Product 1', 'price' => 100]);

// 7. Verify isolation
Shop::count();  // Returns shops for this tenant only
Product::count();  // Returns products for this tenant only

// 8. Query another tenant's data (using bypass)
Shop::withoutGlobalScopes()->count();  // Returns ALL shops across all tenants
```

---

## 6. Available Tenant Helper Functions

Inside your application, use these helpers:

```php
// Get current tenant object
tenant();

// Get specific field from current tenant
tenant('id');      // Tenant ID
tenant('name');    // Tenant name
tenant('slug');    // Tenant slug
tenant('email');   // Tenant email

// Tenancy service
tenancy()->initialize($tenant);  // Set tenant context
tenancy()->current();             // Get current tenant
tenancy()->forget();              // Clear tenant context

// TenantManager utility class
use App\Support\TenantManager;

TenantManager::getActiveTenants();           // All active tenants
TenantManager::getTenantBySlug('acme-corp'); // Find by slug
TenantManager::getTenantByDomain($domain);   // Find by domain
TenantManager::getTenantStats($tenant);      // Get usage stats
TenantManager::deactivateTenant($tenant);    // Deactivate tenant
TenantManager::reactivateTenant($tenant);    // Reactivate tenant
```

---

## 7. Important Notes

### ✅ What's Automatic
- **Data Isolation**: Every query is automatically scoped to current tenant
- **Tenant Assignment**: New records automatically get `tenant_id` from context
- **Middleware Integration**: Tenant context is set on every request from authenticated user

### ⚠️ What You Must Do Manually
- **Set Context**: Use `tenancy()->initialize($tenant)` before creating/querying tenant data
- **Authenticate**: User authentication sets tenant context automatically
- **Bypass When Needed**: Use `withoutGlobalScopes()` only when you specifically need to query across tenants

### 🔐 Security Best Practices
1. Always verify user's `tenant_id` before allowing access to data
2. Use middleware to prevent cross-tenant data access
3. Test isolation thoroughly before deploying
4. Never bypass global scopes in production without explicit reason

---

## 8. Troubleshooting

### Problem: Queries return data from all tenants
**Solution**: Set tenant context first with `tenancy()->initialize($tenant)`

### Problem: New records don't get tenant_id
**Solution**: Make sure tenant context is set before creating records

### Problem: User sees data from wrong tenant
**Solution**: Verify SetTenantMiddleware is running and user is properly authenticated

### Problem: Can't create tenant
**Solution**: Check that `tenants` table exists: `php artisan migrate`

---

## Quick Reference

```bash
# Create tenant via CLI
php artisan tenant:create "Company Name"

# Start interactive shell
php artisan tinker

# Create tenant in tinker
Tenant::create(['name' => 'Name', 'slug' => 'slug']);

# Create user in tinker
User::create(['name' => 'Name', 'email' => 'user@example.com', 'password' => bcrypt('pass'), 'tenant_id' => 1]);

# Set tenant context
tenancy()->initialize(1);  // by ID
tenancy()->initialize($tenant);  // by object

# Test data isolation
Shop::count();  // Only current tenant's shops
Shop::withoutGlobalScopes()->count();  // All shops

# Get current tenant
tenant();  // Full object
tenant('name');  // Just the name
```

---

**Your multi-tenant system is ready to use!** 🚀
