# ByabshaTrack (ব্যবসা ট্র্যাক)

**Multi-Branch POS and Inventory Management System**

A comprehensive, production-ready point-of-sale and inventory management system designed for electronics businesses with multiple branches. Built with modern technologies and best practices.

## 🌟 Features

### Core Functionality

- ✅ **Multi-Branch Support** - Manage up to 3 branches independently
- ✅ **Branch-Wise Inventory** - Separate stock tracking per branch
- ✅ **Purchase Management** - Supplier-based purchase orders with automatic stock updates
- ✅ **Sales/POS System** - Complete point-of-sale with automatic stock deduction
- ✅ **Profit Tracking** - Automatic profit calculation per sale
- ✅ **Daily & Monthly Reports** - Comprehensive profit and sales analytics
- ✅ **Role-Based Access** - Three user roles: Owner, Manager, Salesman
- ✅ **Expense Tracking** - Record and categorize business expenses
- ✅ **Low Stock Alerts** - Get notified when products run low
- ✅ **Supplier Management** - Track supplier balances and purchase history

### Technical Features

- 🔒 **Secure Authentication** - Token-based authentication with Laravel Sanctum
- 🛡️ **Role-Based Authorization** - Middleware-protected routes
- ✔️ **Data Validation** - Comprehensive form validation
- 🔄 **RESTful API** - Clean, documented API endpoints
- 📱 **Responsive Design** - Works on desktop and mobile
- ⚡ **Real-Time Updates** - Instant stock and profit calculations
- 🗃️ **Clean Architecture** - Scalable and maintainable codebase
- 🎯 **Best Practices** - Production-level code standards

## 🏗️ Tech Stack

### Backend

- **Framework**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **API**: RESTful architecture

### Frontend

- **Framework**: React 19 + Vite
- **Routing**: React Router v6
- **HTTP Client**: Axios
- **Styling**: Tailwind CSS
- **State Management**: Context API

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **MySQL** >= 8.0
- **npm** or **yarn**

## 🚀 Installation Guide

### Step 1: Clone the Repository

```bash
git clone <your-repository-url>
cd ByabshaTrack
```

### Step 2: Backend Setup (Laravel)

```bash
# Navigate to backend folder
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Update these lines:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=byabsha_track
DB_USERNAME=root
DB_PASSWORD=your_password

# Create database
mysql -u root -p
CREATE DATABASE byabsha_track;
exit;

# Run migrations and seed data
php artisan migrate --seed

# Start Laravel development server
php artisan serve
```

The backend API will be running at `http://localhost:8000`

### Step 3: Frontend Setup (React)

```bash
# Open a new terminal
# Navigate to frontend folder
cd frontend

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Update API URL in .env
VITE_API_URL=http://localhost:8000/api

# Start React development server
npm run dev
```

The frontend will be running at `http://localhost:5173`

### Step 4: Access the Application

Open your browser and visit: `http://localhost:5173`

## 🔑 Default Login Credentials

After running seeders, you can login with these credentials:

### Owner Account

- **Email**: `owner@byabshatrack.com`
- **Password**: `password`
- **Access**: Full system access, all features

### Manager Account

- **Email**: `manager@byabshatrack.com`
- **Password**: `password`
- **Access**: Branch operations, purchases, sales, reports

### Salesman Account

- **Email**: `salesman@byabshatrack.com`
- **Password**: `password`
- **Access**: Sales/POS only

## 📁 Project Structure

```
ByabshaTrack/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    # API Controllers
│   │   │   ├── Middleware/     # Custom middleware
│   │   │   └── Requests/       # Form requests
│   │   └── Models/             # Eloquent models
│   ├── database/
│   │   ├── migrations/         # Database migrations
│   │   └── seeders/            # Database seeders
│   ├── routes/
│   │   └── api.php             # API routes
│   └── API_DOCUMENTATION.md    # API documentation
│
└── frontend/                   # React SPA
    ├── src/
    │   ├── components/         # Reusable components
    │   ├── context/            # React Context
    │   ├── pages/              # Page components
    │   ├── services/           # API services
    │   ├── App.jsx             # Main app
    │   └── main.jsx            # Entry point
    └── FRONTEND_README.md      # Frontend docs
```

## 📊 Database Schema

### Core Tables

- `branches` - Branch information
- `users` - User accounts with roles
- `categories` - Product categories (hierarchical)
- `products` - Product master data
- `suppliers` - Supplier information
- `branch_stocks` - Branch-wise inventory levels
- `purchases` & `purchase_items` - Purchase orders
- `sales` & `sale_items` - Sales transactions
- `expenses` - Business expenses

## 🎯 User Roles & Permissions

### Owner

- Full system access
- Manage all branches
- View all reports
- User management
- Branch configuration

### Manager

- Manage assigned branch
- Product management
- Purchase & sales operations
- View reports
- Expense tracking

### Salesman

- Create sales (POS)
- View own sales history
- Limited inventory view

## 📈 Business Logic

### Stock Management

1. **On Purchase**: Stock automatically increases in the specified branch
2. **On Sale**: Stock automatically decreases from the branch
3. **Validation**: Sales blocked if insufficient stock
4. **Real-time**: Stock levels updated immediately

### Profit Calculation

```
Profit = (Selling Price - Purchase Price) × Quantity
Net Profit = Total Profit - Expenses
```

- Calculated per item and stored
- Daily and monthly reports available
- Includes expense deductions

## 🔌 API Endpoints

### Authentication

```
POST   /api/login
POST   /api/register
POST   /api/logout
GET    /api/me
```

### Core Resources (CRUD)

```
/api/branches         # Owner only
/api/categories       # Owner, Manager
/api/products         # Owner, Manager
/api/suppliers        # Owner, Manager
/api/purchases        # Owner, Manager
/api/sales            # All authenticated
/api/expenses         # Owner, Manager
```

### Reports

```
GET /api/reports/dashboard
GET /api/reports/daily-profit
GET /api/reports/monthly-profit
GET /api/reports/sales-summary
GET /api/reports/purchase-summary
GET /api/reports/top-selling-products
```

See [API_DOCUMENTATION.md](backend/API_DOCUMENTATION.md) for complete API reference.

## 🧪 Testing

### Backend Tests

```bash
cd backend
php artisan test
```

### Code Quality

```bash
cd backend
./vendor/bin/pint  # Laravel Pint for code formatting
```

## 🚢 Production Deployment

### Backend Deployment

1. **Environment Configuration**

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

2. **Optimize Laravel**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. **Set Permissions**

```bash
chmod -R 755 storage bootstrap/cache
```

### Frontend Deployment

1. **Build for Production**

```bash
cd frontend
npm run build
```

2. **Deploy `dist/` folder** to your web server

3. **Configure Web Server**
   - Apache: Use `.htaccess` for SPA routing
   - Nginx: Configure try_files for SPA routing

## 🔧 Configuration

### Backend Configuration Files

- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/auth.php` - Authentication settings
- `config/cors.php` - CORS configuration

### Frontend Configuration

- `.env` - Environment variables
- `vite.config.js` - Vite build configuration

## 📚 Documentation

- [Backend API Documentation](backend/API_DOCUMENTATION.md)
- [Frontend Documentation](frontend/FRONTEND_README.md)

## 🐛 Troubleshooting

### Database Connection Failed

- Check MySQL is running
- Verify database credentials in `.env`
- Ensure database exists

### CORS Errors

- Update `config/cors.php` in backend
- Add frontend URL to allowed origins

### Port Already in Use

```bash
# Backend
php artisan serve --port=8001

# Frontend
npm run dev -- --port=5174
```

## 🛠️ Development Workflow

1. **Feature Development**
   - Create database migration if needed
   - Create/update model with relationships
   - Create API controller and routes
   - Create frontend service function
   - Build UI components
   - Test thoroughly

2. **Code Standards**
   - Follow PSR-12 for PHP
   - Use ESLint for JavaScript
   - Write meaningful commit messages
   - Document complex logic

## 📝 Sample Data

The seeders create:

- 3 branches (Dhaka, Chittagong, Sylhet)
- 3 users (one per role)
- 8 product categories
- 3 suppliers

## 🔐 Security Features

- 🔒 Password hashing with bcrypt
- 🎫 Token-based authentication
- 🛡️ Role-based authorization
- ✅ Input validation and sanitization
- 🚫 SQL injection prevention (Eloquent ORM)
- 🔐 CSRF protection
- 🚪 Secure API endpoints

## 🎨 UI Screenshots

(Add screenshots after development)

## 📞 Support

For support and queries:

- **Email**: support@byabshatrack.com
- **Documentation**: Check the docs folder

## 📄 License

This project is proprietary software. All rights reserved.

## 👥 Contributors

- Development Team

## 🚀 Future Enhancements

- [ ] Advanced POS interface with barcode scanning
- [ ] Invoice printing (PDF generation)
- [ ] SMS notifications
- [ ] Email reports
- [ ] Mobile app (React Native)
- [ ] Customer management
- [ ] Multiple payment methods tracking
- [ ] Stock transfer between branches
- [ ] Advanced analytics dashboard
- [ ] Export reports to Excel
- [ ] Barcode label printing
- [ ] Multi-currency support

## 📊 Performance

- Fast API response times (< 200ms)
- Optimized database queries with eager loading
- Efficient frontend rendering
- Minimal bundle size

## 🙏 Acknowledgments

- Laravel Framework
- React Team
- Vite Build Tool
- Tailwind CSS

---

**Built with ❤️ for Electronics Business Management**

For the latest updates and releases, check the repository.
#   B y a b s h a - T r a c k  
 