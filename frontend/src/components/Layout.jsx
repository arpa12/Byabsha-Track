import React from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const Layout = ({ children }) => {
  const { user, logout, hasRole } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const menuItems = [
    { path: "/dashboard", label: "Dashboard", roles: ["owner", "manager"] },
    { path: "/pos", label: "POS", roles: ["owner", "manager", "salesman"] },
    { path: "/sales", label: "Sales", roles: ["owner", "manager", "salesman"] },
    { path: "/purchases", label: "Purchases", roles: ["owner", "manager"] },
    { path: "/products", label: "Products", roles: ["owner", "manager"] },
    { path: "/categories", label: "Categories", roles: ["owner", "manager"] },
    { path: "/suppliers", label: "Suppliers", roles: ["owner", "manager"] },
    { path: "/expenses", label: "Expenses", roles: ["owner", "manager"] },
    { path: "/branches", label: "Branches", roles: ["owner"] },
    { path: "/reports", label: "Reports", roles: ["owner", "manager"] },
  ];

  const visibleMenuItems = menuItems.filter((item) => hasRole(item.roles));

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Header */}
      <header className="bg-white shadow">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center py-4">
            <div className="flex items-center">
              <h1 className="text-2xl font-bold text-gray-900">
                ByabshaTrack (ব্যবসা ট্র্যাক)
              </h1>
            </div>
            <div className="flex items-center space-x-4">
              <span className="text-sm text-gray-700">
                {user?.name} ({user?.role})
              </span>
              {user?.branch && (
                <span className="text-sm text-gray-500">
                  Branch: {user.branch.name}
                </span>
              )}
              <button
                onClick={handleLogout}
                className="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
      </header>

      {/* Navigation */}
      <nav className="bg-gray-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex space-x-4 py-3 overflow-x-auto">
            {visibleMenuItems.map((item) => (
              <Link
                key={item.path}
                to={item.path}
                className={`px-3 py-2 rounded-md text-sm font-medium whitespace-nowrap ${
                  location.pathname === item.path
                    ? "bg-gray-900 text-white"
                    : "text-gray-300 hover:bg-gray-700 hover:text-white"
                }`}
              >
                {item.label}
              </Link>
            ))}
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {children}
      </main>

      {/* Footer */}
      <footer className="bg-white border-t border-gray-200 mt-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <p className="text-center text-sm text-gray-500">
            © 2024 ByabshaTrack. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
};

export default Layout;
