# Authentication Quick Start Guide

## 🚀 Quick Setup (5 Minutes)

### Step 1: Run Migrations

```bash
cd backend
php artisan migrate
```

### Step 2: Seed Database with Test Users

```bash
php artisan db:seed --class=RolePermissionSeeder
```

This creates:

- ✅ 3 Branches (Dhaka, Chittagong, Sylhet)
- ✅ 6 Test Users (1 owner, 2 managers, 3 salesmen)

### Step 3: Start Server

```bash
php artisan serve
```

Server will start at: `http://localhost:8000`

---

## 🔑 Test Credentials

### Owner (Full Access)

```
Email: owner@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka
```

### Managers (Branch Management)

```
Email: manager@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka

Email: manager.chittagong@byabshatrack.com
Password: password
Branch: Chittagong Branch
```

### Salesmen (Sales Only)

```
Email: salesman@byabshatrack.com
Password: password
Branch: Main Branch - Dhaka

Email: salesman.chittagong@byabshatrack.com
Password: password
Branch: Chittagong Branch

Email: salesman.sylhet@byabshatrack.com
Password: password
Branch: Sylhet Branch
```

---

## 📝 Test Authentication (Using cURL)

### 1. Login as Owner

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "owner@byabshatrack.com",
    "password": "password"
  }'
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

Save the `token` value!

### 2. Test Protected Route (Get Branches - Owner Only)

```bash
curl -X GET http://localhost:8000/api/branches \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 3. Login as Manager

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "manager@byabshatrack.com",
    "password": "password"
  }'
```

### 4. Test Manager Permissions (Should Work)

```bash
# Get products (allowed for managers)
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer MANAGER_TOKEN" \
  -H "Accept: application/json"
```

### 5. Test Manager Restrictions (Should Fail)

```bash
# Get branches (owner only - should return 403)
curl -X GET http://localhost:8000/api/branches \
  -H "Authorization: Bearer MANAGER_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**

```json
{
    "success": false,
    "message": "Forbidden. Only owners can access this resource."
}
```

### 6. Login as Salesman

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "salesman@byabshatrack.com",
    "password": "password"
  }'
```

### 7. Test Salesman Permissions (POS Access)

```bash
# Search products (allowed)
curl -X GET "http://localhost:8000/api/products/search?search=phone" \
  -H "Authorization: Bearer SALESMAN_TOKEN" \
  -H "Accept: application/json"
```

### 8. Logout

```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## 🧪 Test with Postman

### 1. Import Collection

Create a new collection with these requests:

#### A. Login - Owner

```
POST http://localhost:8000/api/auth/login
Headers: Content-Type: application/json
Body (JSON):
{
  "email": "owner@byabshatrack.com",
  "password": "password"
}
```

#### B. Get Current User

```
GET http://localhost:8000/api/auth/me
Headers:
  Authorization: Bearer {{token}}
  Accept: application/json
```

#### C. Get Branches (Owner Only)

```
GET http://localhost:8000/api/branches
Headers:
  Authorization: Bearer {{token}}
  Accept: application/json
```

#### D. Get Products (Owner & Manager)

```
GET http://localhost:8000/api/products
Headers:
  Authorization: Bearer {{token}}
  Accept: application/json
```

#### E. Get Reports (Owner & Manager)

```
GET http://localhost:8000/api/reports/daily-sales?date=2024-01-15&branch_id=1
Headers:
  Authorization: Bearer {{token}}
  Accept: application/json
```

#### F. POS Sale (All Roles)

```
POST http://localhost:8000/api/sales/pos
Headers:
  Authorization: Bearer {{token}}
  Content-Type: application/json
Body (JSON):
{
  "branch_id": 1,
  "customer_name": "John Doe",
  "customer_phone": "01712345678",
  "payment_method": "cash",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "unit_price": 100
    }
  ],
  "subtotal": 200,
  "discount": 10,
  "tax": 0,
  "total": 190
}
```

### 2. Set Token as Variable

After login, save the token:

1. Go to Tests tab in Postman
2. Add this script:

```javascript
if (pm.response.code === 200) {
    const response = pm.response.json();
    pm.environment.set("token", response.data.token);
}
```

3. Create environment variable `{{token}}`
4. Use `{{token}}` in Authorization headers

---

## 🔒 Testing Access Control

### Test Matrix

| Endpoint          | Owner | Manager | Salesman |
| ----------------- | ----- | ------- | -------- |
| POST /auth/login  | ✅    | ✅      | ✅       |
| GET /auth/me      | ✅    | ✅      | ✅       |
| POST /auth/logout | ✅    | ✅      | ✅       |
| GET /branches     | ✅    | ❌      | ❌       |
| POST /branches    | ✅    | ❌      | ❌       |
| GET /categories   | ✅    | ✅      | ❌       |
| POST /categories  | ✅    | ✅      | ❌       |
| GET /products     | ✅    | ✅      | ✅\*     |
| POST /products    | ✅    | ✅      | ❌       |
| GET /suppliers    | ✅    | ✅      | ❌       |
| POST /suppliers   | ✅    | ✅      | ❌       |
| GET /purchases    | ✅    | ✅      | ❌       |
| POST /purchases   | ✅    | ✅      | ❌       |
| GET /expenses     | ✅    | ✅      | ❌       |
| POST /expenses    | ✅    | ✅      | ❌       |
| GET /sales        | ✅    | ✅      | ✅       |
| POST /sales/pos   | ✅    | ✅      | ✅       |
| GET /reports/\*   | ✅    | ✅      | ❌       |

\*Salesman can view products for POS but cannot manage them

---

## 🐛 Common Issues & Solutions

### Issue 1: Token Not Working

**Symptoms:** API returns 401 even with valid token

**Solutions:**

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Regenerate app key
php artisan key:generate
```

### Issue 2: Permission Denied

**Symptoms:** API returns 403 forbidden

**Check:**

1. User role is correct
2. User is active (`is_active = true`)
3. Correct middleware is applied to route
4. Token belongs to the user

**Test:**

```bash
# Check user role
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Issue 3: Branch Access Issues

**Symptoms:** Manager can't access their own branch data

**Solutions:**

1. Verify `branch_id` in user record:

```bash
php artisan tinker

>>> User::where('email', 'manager@byabshatrack.com')->first()->branch_id
```

2. Check branch exists:

```bash
>>> App\Models\Branch::find(1)
```

3. Ensure branch_id is passed in request

### Issue 4: Database Not Seeded

**Symptoms:** No users or branches after migration

**Fix:**

```bash
# Reset and reseed database
php artisan migrate:fresh --seed
```

**Or seed specific seeder:**

```bash
php artisan db:seed --class=RolePermissionSeeder
```

---

## 📊 Verify Database Setup

```bash
php artisan tinker
```

### Check Branches

```php
>>> \App\Models\Branch::count()
=> 3  // Should be 3

>>> \App\Models\Branch::pluck('name')
=> ["Main Branch - Dhaka", "Chittagong Branch", "Sylhet Branch"]
```

### Check Users

```php
>>> \App\Models\User::count()
=> 6  // Should be 6

>>> \App\Models\User::pluck('email', 'role')
=> [
     "owner" => "owner@byabshatrack.com",
     "manager" => "manager@byabshatrack.com",
     "manager" => "manager.chittagong@byabshatrack.com",
     "salesman" => "salesman@byabshatrack.com",
     "salesman" => "salesman.chittagong@byabshatrack.com",
     "salesman" => "salesman.sylhet@byabshatrack.com",
   ]
```

### Test User Authentication

```php
>>> $user = \App\Models\User::where('email', 'owner@byabshatrack.com')->first()
>>> \Illuminate\Support\Facades\Hash::check('password', $user->password)
=> true  // Password is correct

>>> $user->isOwner()
=> true

>>> $user->hasRole('owner')
=> true
```

---

## 🎯 Next Steps

### 1. Frontend Integration

- Set up React AuthContext
- Create Login component
- Implement token management
- Add protected routes

See: `AUTHENTICATION_GUIDE.md` → Frontend Integration section

### 2. Create Additional Users

```bash
php artisan tinker
```

```php
>>> $branch = \App\Models\Branch::find(1);
>>> \App\Models\User::create([
>>>     'name' => 'New Manager',
>>>     'email' => 'newmanager@example.com',
>>>     'password' => \Illuminate\Support\Facades\Hash::make('password123'),
>>>     'role' => 'manager',
>>>     'branch_id' => $branch->id,
>>>     'is_active' => true,
>>> ]);
```

### 3. Upgrade to Spatie Permission (Optional)

For granular permission control, see: `SPATIE_PERMISSION_GUIDE.md`

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 4. Add More Routes

Update `routes/api.php` with your new endpoints and protect them with appropriate middleware.

---

## 📚 Documentation Files

1. **AUTHENTICATION_GUIDE.md** - Complete authentication documentation
2. **SPATIE_PERMISSION_GUIDE.md** - Optional permission upgrade guide
3. **REPORTING_API_DOCUMENTATION.md** - Reports API reference
4. **REPORT_SERVICE_GUIDE.md** - Report service developer guide
5. **POS_FRONTEND_DOCUMENTATION.md** - POS React components guide
6. **POS_QUICK_START_GUIDE.md** - POS user guide

---

## ✅ Checklist

- [x] Migrations run successfully
- [x] Database seeded with test data
- [x] Server running
- [x] Login endpoint tested
- [x] Token generation working
- [x] Protected routes working
- [x] Role-based access tested
- [x] Branch scoping verified
- [ ] Frontend authentication integrated
- [ ] Production deployment configured

---

## 🎓 Learning Resources

### Laravel Sanctum

- [Official Documentation](https://laravel.com/docs/sanctum)
- [SPA Authentication](https://laravel.com/docs/sanctum#spa-authentication)
- [Mobile App Authentication](https://laravel.com/docs/sanctum#mobile-application-authentication)

### Role-Based Access Control

- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Middleware Documentation](https://laravel.com/docs/middleware)
- [Policy Documentation](https://laravel.com/docs/authorization#creating-policies)

### Security Best Practices

- [OWASP API Security Top 10](https://owasp.org/www-project-api-security/)
- [Laravel Security](https://laravel.com/docs/security)

---

## 💬 Support

If you encounter issues:

1. Check error messages carefully
2. Review logs: `storage/logs/laravel.log`
3. Check database records
4. Verify middleware configuration
5. Test with cURL first, then frontend

---

**Setup Complete! 🎉**

Your authentication system is ready. Test thoroughly before deploying to production.
