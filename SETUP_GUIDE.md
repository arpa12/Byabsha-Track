# ByabshaTrack - Quick Setup Guide

Follow these steps to get the system up and running quickly.

## Prerequisites Check

Make sure you have:

- ✅ PHP 8.2 or higher
- ✅ Composer 2.x
- ✅ MySQL 8.0 or higher
- ✅ Node.js 18.x or higher
- ✅ npm or yarn

Check versions:

```bash
php --version
composer --version
mysql --version
node --version
npm --version
```

## Quick Start (5 Minutes)

### 1. Database Setup

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE byabsha_track;

# Exit MySQL
exit;
```

### 2. Backend Setup

```bash
# Navigate to backend
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure .env file - Update these lines:
DB_DATABASE=byabsha_track
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations and seed demo data
php artisan migrate --seed

# Start Laravel server
php artisan serve
```

✅ Backend running at: http://localhost:8000

### 3. Frontend Setup

```bash
# Open new terminal
cd frontend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Start React dev server
npm run dev
```

✅ Frontend running at: http://localhost:5173

### 4. Login to System

Open browser: http://localhost:5173

**Login with:**

- Email: `owner@byabshatrack.com`
- Password: `password`

## What's Included After Setup?

### Demo Data Created:

- ✅ 3 Branches (Dhaka, Chittagong, Sylhet)
- ✅ 3 Users (Owner, Manager, Salesman)
- ✅ 8 Product Categories
- ✅ 3 Suppliers

### Features Ready to Use:

- ✅ Authentication system
- ✅ Role-based access control
- ✅ Dashboard with statistics
- ✅ Product management
- ✅ Purchase management
- ✅ Sales/POS system
- ✅ Inventory tracking
- ✅ Report generation
- ✅ Expense tracking

## Test the System

### 1. View Dashboard

Login and see real-time statistics

### 2. Create a Product

Navigate to Products → Add New Product

### 3. Make a Purchase

Navigate to Purchases → Create Purchase Order

- This will increase stock

### 4. Make a Sale (POS)

Navigate to POS → Create Sale

- This will decrease stock

### 5. Check Reports

Navigate to Reports → View profit analysis

## Common Issues & Solutions

### Issue: Database Connection Failed

**Solution:**

- Verify MySQL is running: `sudo service mysql start`
- Check credentials in `.env` file
- Ensure database `byabsha_track` exists

### Issue: Port Already in Use

**Backend Solution:**

```bash
php artisan serve --port=8001
```

Update frontend `.env`: `VITE_API_URL=http://localhost:8001/api`

**Frontend Solution:**

```bash
npm run dev -- --port=5174
```

### Issue: CORS Error

**Solution:** Backend should auto-configure CORS. If issues persist:

Add to `backend/config/cors.php`:

```php
'allowed_origins' => ['http://localhost:5173'],
```

### Issue: Composer Install Fails

**Solution:**

```bash
composer clear-cache
composer install --ignore-platform-reqs
```

### Issue: NPM Install Fails

**Solution:**

```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

## Project Structure Overview

```
ByabshaTrack/
├── backend/              ← Laravel API (Port 8000)
│   ├── app/Models/       ← Database models
│   ├── app/Http/Controllers/Api/ ← API controllers
│   ├── database/migrations/ ← Database schema
│   └── routes/api.php    ← API routes
│
├── frontend/             ← React App (Port 5173)
│   ├── src/components/   ← UI components
│   ├── src/pages/        ← Page components
│   ├── src/services/     ← API calls
│   └── src/context/      ← State management
│
└── README.md            ← Main documentation
```

## Next Steps

After successful setup:

1. **Explore the Dashboard**
   - View statistics
   - Check quick actions

2. **Configure Branches**
   - Add/edit branch information
   - Assign users to branches

3. **Add Products**
   - Create categories
   - Add products with pricing
   - Set minimum stock levels

4. **Start Operations**
   - Create purchase orders
   - Make sales through POS
   - Track inventory
   - Generate reports

## Important Files

### Backend

- `.env` - Environment configuration
- `database/migrations/` - Database structure
- `database/seeders/` - Demo data
- `routes/api.php` - API endpoints

### Frontend

- `.env` - API URL configuration
- `src/App.jsx` - Route definitions
- `src/services/index.js` - API functions
- `src/context/AuthContext.jsx` - Authentication

## Stopping the Servers

**Backend:**
Press `Ctrl+C` in backend terminal

**Frontend:**
Press `Ctrl+C` in frontend terminal

## Restarting After Stop

Just run the servers again:

```bash
# Terminal 1 - Backend
cd backend
php artisan serve

# Terminal 2 - Frontend
cd frontend
npm run dev
```

## Production Deployment

When ready for production:

### Backend

```bash
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend

```bash
cd frontend
npm run build
# Deploy dist/ folder to web server
```

## Getting Help

1. Check [API_DOCUMENTATION.md](backend/API_DOCUMENTATION.md)
2. Check [FRONTEND_README.md](frontend/FRONTEND_README.md)
3. Review error logs:
   - Backend: `backend/storage/logs/laravel.log`
   - Frontend: Browser console (F12)

## Default Test Accounts

| Role     | Email                     | Password | Access Level      |
| -------- | ------------------------- | -------- | ----------------- |
| Owner    | owner@byabshatrack.com    | password | Full Access       |
| Manager  | manager@byabshatrack.com  | password | Branch Operations |
| Salesman | salesman@byabshatrack.com | password | Sales Only        |

## System Requirements

### Minimum

- 2 GB RAM
- 10 GB Disk Space
- 2 CPU Cores

### Recommended

- 4 GB RAM
- 20 GB Disk Space
- 4 CPU Cores

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Database Backup

Regular backups recommended:

```bash
mysqldump -u root -p byabsha_track > backup.sql
```

Restore from backup:

```bash
mysql -u root -p byabsha_track < backup.sql
```

## Success Checklist

- [ ] MySQL database created
- [ ] Backend dependencies installed
- [ ] Migrations run successfully
- [ ] Backend server running on port 8000
- [ ] Frontend dependencies installed
- [ ] Frontend server running on port 5173
- [ ] Can access login page
- [ ] Can login with demo credentials
- [ ] Dashboard loads with statistics
- [ ] Navigation menu visible
- [ ] Can view all accessible pages

## Congratulations! 🎉

Your ByabshaTrack system is now ready to use!

Start by:

1. Logging in as Owner
2. Exploring the dashboard
3. Adding your first product
4. Making your first sale

---

**Need Help?** Contact: support@byabshatrack.com
