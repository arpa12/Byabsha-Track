# Role-Based Authentication System

## Overview

ByabshaTrack uses Laravel Sanctum for API authentication combined with role-based access control. The system supports three user roles with different permission levels:

- **Owner** - Full system access across all branches
- **Manager** - Branch-level access with management capabilities
- **Salesman** - Limited access for sales operations only

---

## Architecture

### Authentication Flow

```
1. User Login → Generate Sanctum Token → Return Token
2. API Request → Include Token in Header → Validate Token
3. Check User Role → Verify Permissions → Allow/Deny Access
```

### Database Schema

**Users Table:**

```sql
- id (primary key)
- name (string)
- email (unique string)
- password (hashed)
- role (enum: 'owner', 'manager', 'salesman')
- branch_id (foreign key to branches)
- is_active (boolean)
- created_at, updated_at
```

---

## User Roles & Permissions

### 1. Owner Role

**Access Level:** Full system access

**Permissions:**

- ✅ Manage all branches
- ✅ Create/edit/delete branches
- ✅ Manage all users across all branches
- ✅ View all reports (all branches)
- ✅ Manage categories, products, suppliers
- ✅ Manage purchases and expenses
- ✅ Access all sales data
- ✅ System settings and configuration

**Use Case:** Business owner, system administrator

**Branch Access:** All branches

---

### 2. Manager Role

**Access Level:** Branch-level management

**Permissions:**

- ✅ View their assigned branch only
- ✅ Manage products in their branch
- ✅ Manage purchases for their branch
- ✅ Manage expenses for their branch
- ✅ View reports for their branch only
- ✅ Manage categories and suppliers (branch-scoped)
- ✅ View sales data for their branch
- ✅ Manage salesmen in their branch
- ❌ Cannot access other branches
- ❌ Cannot create/edit/delete branches
- ❌ Cannot modify system settings

**Use Case:** Branch manager, inventory manager

**Branch Access:** Assigned branch only

---

### 3. Salesman Role

**Access Level:** Sales operations only

**Permissions:**

- ✅ Access POS (Point of Sale)
- ✅ Create sales for their branch
- ✅ View their own sales
- ✅ Search products
- ✅ Check stock availability
- ❌ Cannot manage inventory
- ❌ Cannot manage purchases
- ❌ Cannot view reports
- ❌ Cannot access other branches
- ❌ Cannot manage categories/suppliers

**Use Case:** Sales staff, cashier

**Branch Access:** Assigned branch only

---

## Middleware Implementation

### Available Middleware

#### 1. `check.role` - Multiple Role Check

Allows access if user has any of the specified roles (OR logic).

```php
// In routes/api.php
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['auth:sanctum', 'check.role:owner,manager']);
```

**Features:**

- Checks authentication
- Verifies user is active
- Allows multiple roles
- Returns detailed error messages

---

#### 2. `check.owner` - Owner Only

Restricts access to owners only.

```php
Route::post('/branches', [BranchController::class, 'store'])
    ->middleware(['auth:sanctum', 'check.owner']);
```

**Features:**

- Strictest access control
- Owner-only operations
- Uses `isOwner()` helper method

---

#### 3. `check.branch` - Branch Scope Check

Ensures users can only access their own branch data.

```php
Route::get('/reports/sales', [ReportController::class, 'salesReport'])
    ->middleware(['auth:sanctum', 'check.branch']);
```

**Features:**

- Owners bypass (access all branches)
- Managers/Salesmen restricted to their branch
- Checks `branch_id` in request params
- Automatic branch scoping

---

#### 4. `role` (Legacy) - Original RoleMiddleware

Original simple role check (kept for backward compatibility).

```php
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth:sanctum', 'role:owner,manager']);
```

---

## User Model Helper Methods

The `User` model includes convenient helper methods:

```php
// Check if user has a specific role
$user->hasRole('owner'); // returns boolean

// Check if user is owner
$user->isOwner(); // returns boolean

// Check if user is manager
$user->isManager(); // returns boolean

// Check if user is salesman
$user->isSalesman(); // returns boolean

// Get user's branch
$user->branch; // returns Branch model
```

---

## API Authentication

### Login Endpoint

**POST** `/api/auth/login`

**Request:**

```json
{
    "email": "owner@byabshatrack.com",
    "password": "password"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "System Owner",
            "email": "owner@byabshatrack.com",
            "role": "owner",
            "branch_id": 1,
            "is_active": true
        },
        "token": "1|abcdef123456...",
        "token_type": "Bearer"
    }
}
```

---

### Using the Token

Include the token in all API requests:

**Header:**

```
Authorization: Bearer 1|abcdef123456...
```

**Example (JavaScript/Axios):**

```javascript
axios.get("/api/products", {
    headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
    },
});
```

---

## Error Responses

### 401 Unauthenticated

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 403 Forbidden (Role)

```json
{
    "success": false,
    "message": "Forbidden. You do not have permission to access this resource.",
    "required_roles": ["owner", "manager"],
    "your_role": "salesman"
}
```

### 403 Forbidden (Branch)

```json
{
    "success": false,
    "message": "Forbidden. You can only access data from your assigned branch.",
    "your_branch_id": 1,
    "requested_branch_id": 2
}
```

### 403 Account Deactivated

```json
{
    "success": false,
    "message": "Your account has been deactivated. Please contact administrator."
}
```

---

## Route Protection Examples

### Public Routes (No Auth)

```php
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
```

### All Authenticated Users

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
```

### Owner Only

```php
Route::middleware(['auth:sanctum', 'check.owner'])->group(function () {
    Route::resource('branches', BranchController::class);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
```

### Owner & Manager

```php
Route::middleware(['auth:sanctum', 'check.role:owner,manager'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::get('/reports/daily-sales', [ReportController::class, 'dailySales']);
});
```

### All Roles (Sales)

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sales/pos', [SaleController::class, 'store']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/{product}/stock', [ProductController::class, 'checkStock']);
});
```

### With Branch Scoping

```php
Route::middleware(['auth:sanctum', 'check.branch'])->group(function () {
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/reports/branch-sales', [ReportController::class, 'branchSales']);
});
```

---

## Test Credentials

Use these credentials for testing (seeded by RolePermissionSeeder):

### Owner Account

```
Email: owner@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka
Access: Full system access
```

### Manager Accounts

```
Email: manager@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka
Access: Main branch management only

Email: manager.chittagong@byabshatrack.com
Password: password
Branch: Chittagong Branch
Access: Chittagong branch management only
```

### Salesman Accounts

```
Email: salesman@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka
Access: Sales operations only (Main Branch)

Email: salesman.chittagong@byabshatrack.com
Password: password
Branch: Chittagong Branch
Access: Sales operations only (Chittagong Branch)

Email: salesman.sylhet@byabshatrack.com
Password: password
Branch: Sylhet Branch
Access: Sales operations only (Sylhet Branch)
```

---

## Setup Instructions

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Test Data

```bash
php artisan db:seed --class=RolePermissionSeeder
```

This will create:

- 3 branches (Dhaka, Chittagong, Sylhet)
- 6 test users (1 owner, 2 managers, 3 salesmen)

### 3. Test Authentication

```bash
# Test owner login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"owner@byabshatrack.com","password":"password"}'
```

---

## Frontend Integration

### React Authentication Context

Create an auth context to manage authentication state:

```javascript
// src/contexts/AuthContext.jsx
import { createContext, useState, useContext, useEffect } from "react";
import api from "../services/api";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem("token"));
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (token) {
            api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            fetchUser();
        } else {
            setLoading(false);
        }
    }, [token]);

    const fetchUser = async () => {
        try {
            const response = await api.get("/auth/me");
            setUser(response.data.data);
        } catch (error) {
            localStorage.removeItem("token");
            setToken(null);
        } finally {
            setLoading(false);
        }
    };

    const login = async (email, password) => {
        const response = await api.post("/auth/login", { email, password });
        const { token: newToken, user: userData } = response.data.data;

        localStorage.setItem("token", newToken);
        setToken(newToken);
        setUser(userData);
        api.defaults.headers.common["Authorization"] = `Bearer ${newToken}`;

        return userData;
    };

    const logout = async () => {
        try {
            await api.post("/auth/logout");
        } finally {
            localStorage.removeItem("token");
            setToken(null);
            setUser(null);
            delete api.defaults.headers.common["Authorization"];
        }
    };

    const hasRole = (role) => {
        return user?.role === role;
    };

    const isOwner = () => hasRole("owner");
    const isManager = () => hasRole("manager");
    const isSalesman = () => hasRole("salesman");

    return (
        <AuthContext.Provider
            value={{
                user,
                token,
                loading,
                login,
                logout,
                hasRole,
                isOwner,
                isManager,
                isSalesman,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}

export const useAuth = () => useContext(AuthContext);
```

### Protected Route Component

```javascript
// src/components/ProtectedRoute.jsx
import { Navigate } from "react-router-dom";
import { useAuth } from "../contexts/AuthContext";

export function ProtectedRoute({ children, allowedRoles }) {
    const { user, loading } = useAuth();

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!user) {
        return <Navigate to="/login" replace />;
    }

    if (allowedRoles && !allowedRoles.includes(user.role)) {
        return <Navigate to="/unauthorized" replace />;
    }

    return children;
}
```

### Usage in Routes

```javascript
// src/App.jsx
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { AuthProvider } from "./contexts/AuthContext";
import { ProtectedRoute } from "./components/ProtectedRoute";

function App() {
    return (
        <AuthProvider>
            <BrowserRouter>
                <Routes>
                    <Route path="/login" element={<Login />} />

                    {/* All authenticated users */}
                    <Route
                        path="/dashboard"
                        element={
                            <ProtectedRoute>
                                <Dashboard />
                            </ProtectedRoute>
                        }
                    />

                    {/* Owner only */}
                    <Route
                        path="/branches"
                        element={
                            <ProtectedRoute allowedRoles={["owner"]}>
                                <Branches />
                            </ProtectedRoute>
                        }
                    />

                    {/* Owner and Manager */}
                    <Route
                        path="/products"
                        element={
                            <ProtectedRoute allowedRoles={["owner", "manager"]}>
                                <Products />
                            </ProtectedRoute>
                        }
                    />

                    {/* All roles */}
                    <Route
                        path="/pos"
                        element={
                            <ProtectedRoute>
                                <POS />
                            </ProtectedRoute>
                        }
                    />
                </Routes>
            </BrowserRouter>
        </AuthProvider>
    );
}
```

### Conditional UI Rendering

```javascript
// Example: Show/hide UI elements based on role
import { useAuth } from "../contexts/AuthContext";

function Sidebar() {
    const { isOwner, isManager } = useAuth();

    return (
        <nav>
            <Link to="/dashboard">Dashboard</Link>
            <Link to="/pos">Point of Sale</Link>

            {(isOwner() || isManager()) && (
                <>
                    <Link to="/products">Products</Link>
                    <Link to="/purchases">Purchases</Link>
                    <Link to="/reports">Reports</Link>
                </>
            )}

            {isOwner() && (
                <>
                    <Link to="/branches">Branches</Link>
                    <Link to="/users">Users</Link>
                    <Link to="/settings">Settings</Link>
                </>
            )}
        </nav>
    );
}
```

---

## Security Best Practices

### 1. Token Storage

- ✅ Store tokens in `localStorage` or `sessionStorage`
- ❌ Never store tokens in cookies (CSRF vulnerability)
- ✅ Clear tokens on logout

### 2. Token Management

```javascript
// Automatically add token to all requests
api.defaults.headers.common["Authorization"] = `Bearer ${token}`;

// Handle token expiration
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Token expired, redirect to login
            localStorage.removeItem("token");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    },
);
```

### 3. Password Security

- ✅ Use bcrypt hashing (Laravel default)
- ✅ Minimum 8 characters
- ✅ Never log passwords
- ✅ Use HTTPS in production

### 4. Branch Isolation

- ✅ Always scope queries by branch_id for managers/salesmen
- ✅ Validate branch access in controllers
- ✅ Use middleware for automatic branch scoping

---

## Upgrading to Spatie Permission (Optional)

For more granular permission control, consider installing Spatie Laravel Permission package:

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

This enables:

- Fine-grained permissions (e.g., `create-product`, `view-report`)
- Role hierarchy
- Permission caching
- Database-driven permissions

See `SPATIE_PERMISSION_GUIDE.md` for detailed instructions.

---

## Troubleshooting

### Token Not Working

**Issue:** API returns 401 even with valid token

**Solutions:**

1. Check token format: `Bearer {token}`
2. Verify token hasn't expired
3. Ensure `auth:sanctum` middleware is applied
4. Check user is active: `is_active = true`

### Permission Denied

**Issue:** API returns 403 forbidden

**Solutions:**

1. Verify user has correct role
2. Check middleware is correct: `check.role:owner,manager`
3. For branch access, verify `branch_id` matches
4. Check user account is active

### Branch Access Issues

**Issue:** Manager can't access their own branch data

**Solutions:**

1. Verify `branch_id` is set correctly in database
2. Check `branch_id` parameter in request
3. Ensure `check.branch` middleware is applied correctly
4. Owners bypass branch checks (expected behavior)

---

## API Endpoint Reference

| Endpoint       | Method   | Auth | Roles          | Description       |
| -------------- | -------- | ---- | -------------- | ----------------- |
| `/auth/login`  | POST     | No   | All            | User login        |
| `/auth/logout` | POST     | Yes  | All            | User logout       |
| `/auth/me`     | GET      | Yes  | All            | Get current user  |
| `/branches`    | GET      | Yes  | Owner          | List all branches |
| `/branches`    | POST     | Yes  | Owner          | Create branch     |
| `/categories`  | GET/POST | Yes  | Owner, Manager | Manage categories |
| `/products`    | GET/POST | Yes  | Owner, Manager | Manage products   |
| `/suppliers`   | GET/POST | Yes  | Owner, Manager | Manage suppliers  |
| `/purchases`   | GET/POST | Yes  | Owner, Manager | Manage purchases  |
| `/expenses`    | GET/POST | Yes  | Owner, Manager | Manage expenses   |
| `/sales/pos`   | POST     | Yes  | All            | Create POS sale   |
| `/reports/*`   | GET      | Yes  | Owner, Manager | View reports      |

---

## Conclusion

This authentication system provides:

- ✅ Secure token-based authentication
- ✅ Role-based access control
- ✅ Branch-level data isolation
- ✅ Flexible middleware system
- ✅ Easy frontend integration
- ✅ Production-ready security

For additional features or customization, refer to Laravel Sanctum and middleware documentation.
