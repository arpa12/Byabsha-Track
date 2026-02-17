# ByabshaTrack (ব্যবসা ট্র্যাক) - Frontend

Multi-branch POS and Inventory Management System - React Frontend

## Features

- **Authentication**: Login/Register with role-based access
- **Dashboard**: Real-time statistics and quick actions
- **POS System**: Point of sale interface for sales operations
- **Product Management**: CRUD operations for products
- **Purchase Management**: Create and track purchase orders
- **Sales Management**: View and manage sales transactions
- **Inventory Tracking**: Real-time stock levels
- **Reports**: Comprehensive profit and sales reports
- **Role-based UI**: Dynamic menus based on user role
- **Responsive Design**: Works on desktop and mobile

## Tech Stack

- **Framework**: React 19 + Vite
- **Routing**: React Router v6
- **HTTP Client**: Axios
- **Styling**: Tailwind CSS
- **State Management**: React Context API
- **Authentication**: Token-based with Laravel Sanctum

## Prerequisites

- Node.js >= 18.x
- npm or yarn
- Backend API running (Laravel)

## Installation

### 1. Clone and Navigate

```bash
cd frontend
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
```

Update `.env` with your backend API URL:

```env
VITE_API_URL=http://localhost:8000/api
```

### 4. Start Development Server

```bash
npm run dev
```

The app will be available at `http://localhost:5173`

## Project Structure

```
frontend/
├── src/
│   ├── components/         # Reusable components
│   │   ├── Layout.jsx      # Main layout wrapper
│   │   └── ProtectedRoute.jsx  # Auth guard
│   ├── context/            # React Context
│   │   └── AuthContext.jsx # Authentication context
│   ├── pages/              # Page components
│   │   ├── Login.jsx       # Login page
│   │   ├── Dashboard.jsx   # Dashboard
│   │   └── Unauthorized.jsx # 403 page
│   ├── services/           # API services
│   │   ├── api.js          # Axios instance
│   │   └── index.js        # Service functions
│   ├── App.jsx             # Main app component
│   ├── main.jsx            # Entry point
│   └── index.css           # Global styles
├── public/                 # Static assets
├── .env.example            # Environment template
├── package.json            # Dependencies
├── vite.config.js          # Vite configuration
└── README.md               # This file
```

## Available Scripts

### Development

```bash
npm run dev
```

Starts development server with hot reload at `http://localhost:5173`

### Build

```bash
npm run build
```

Creates optimized production build in `dist/` folder

### Preview

```bash
npm run preview
```

Preview production build locally

### Lint

```bash
npm run lint
```

Run ESLint to check code quality

## Default Login Credentials

### Owner Account

- **Email**: `owner@byabshatrack.com`
- **Password**: `password`
- **Access**: Full system access

### Manager Account

- **Email**: `manager@byabshatrack.com`
- **Password**: `password`
- **Access**: Branch operations, reports

### Salesman Account

- **Email**: `salesman@byabshatrack.com`
- **Password**: `password`
- **Access**: POS and sales only

## Features by Role

### Owner

- Full access to all features
- Branch management
- User management
- All reports
- System configuration

### Manager

- Dashboard access
- Product management
- Purchase management
- Sales management
- Inventory management
- Reports and analytics
- Expense tracking

### Salesman

- POS system
- Create sales
- View own sales
- Limited inventory view

## API Integration

All API calls are handled through service functions in `src/services/index.js`:

```javascript
import { authService, productService, saleService } from "./services";

// Authentication
await authService.login({ email, password });
await authService.logout();

// Products
const products = await productService.getAll({ search: "laptop" });
await productService.create(productData);

// Sales
const sales = await saleService.getAll({ branch_id: 1 });
await saleService.create(saleData);
```

## Authentication Flow

1. User logs in with credentials
2. Backend returns JWT token
3. Token stored in localStorage
4. Token sent with all API requests via interceptor
5. On 401 response, user redirected to login
6. On logout, token removed and user redirected

## Protected Routes

Routes are protected using the `ProtectedRoute` component:

```jsx
<ProtectedRoute roles={["owner", "manager"]}>
  <Dashboard />
</ProtectedRoute>
```

## Styling

Uses Tailwind CSS utility classes. Custom styles can be added to:

- `src/index.css` - Global styles
- `src/App.css` - App-specific styles

## Error Handling

- API errors displayed as toast notifications
- Form validation errors shown inline
- 401 responses redirect to login
- 403 responses redirect to unauthorized page

## Development Tips

### Add New Page

1. Create component in `src/pages/`
2. Add route in `App.jsx`
3. Add menu item in `Layout.jsx`
4. Add service function in `src/services/`

### Add New Service

1. Add function to appropriate service in `src/services/index.js`
2. Use the service in your component
3. Handle loading and error states

## Building for Production

1. **Build the application**

```bash
npm run build
```

2. **Test production build**

```bash
npm run preview
```

3. **Deploy `dist/` folder** to your web server

## Environment Variables

- `VITE_API_URL` - Backend API base URL

All environment variables must be prefixed with `VITE_` to be exposed.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Troubleshooting

### CORS Errors

Ensure backend has CORS properly configured for your frontend URL.

### API Connection Failed

Check if:

- Backend server is running
- `VITE_API_URL` in `.env` is correct
- Network/firewall not blocking requests

### Auth Token Issues

Clear localStorage and try logging in again:

```javascript
localStorage.clear();
```

## Future Enhancements

- [ ] Complete POS interface
- [ ] Product management UI
- [ ] Purchase order creation
- [ ] Advanced reporting with charts
- [ ] Real-time notifications
- [ ] Print invoice functionality
- [ ] Barcode scanning
- [ ] Export reports to PDF/Excel

## License

Proprietary software.

## Support

For support, contact: support@byabshatrack.com
