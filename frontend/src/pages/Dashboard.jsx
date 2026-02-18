import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { reportService } from "../services";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import "./Dashboard.css";

const Dashboard = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);

  useEffect(() => {
    const fetchDashboard = async () => {
      try {
        const data = await reportService.getDashboard(user?.branch_id);
        setStats(data);
      } catch (error) {
        console.error("Failed to fetch dashboard:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchDashboard();
  }, [user]);

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 768) {
        setSidebarOpen(true);
      } else {
        setSidebarOpen(false);
      }
    };

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const menuItems = [
    { icon: "📊", label: t("dashboard"), path: "/dashboard", active: true },
    { icon: "🛒", label: t("pos"), path: "/pos" },
    { icon: "💰", label: t("sales"), path: "/sales" },
    { icon: "📦", label: t("products"), path: "/products" },
    { icon: "🏷️", label: t("categories"), path: "/categories" },
    { icon: "📥", label: t("purchases"), path: "/purchases" },
    { icon: "🏭", label: t("suppliers"), path: "/suppliers" },
    { icon: "💸", label: t("expenses"), path: "/expenses" },
    { icon: "🏢", label: t("branches"), path: "/branches" },
    { icon: "📈", label: t("reports"), path: "/reports" },
    { icon: "👥", label: t("users"), path: "/users" },
    { icon: "⚙️", label: t("settings"), path: "/settings" },
  ];

  if (loading) {
    return (
      <div className="dashboard-loading">
        <div className="dashboard-spinner"></div>
        <p>{t("loading")}</p>
      </div>
    );
  }

  return (
    <div className="dashboard-container">
      {/* Mobile Overlay */}
      <div
        className={`dashboard-overlay ${sidebarOpen ? "active" : ""}`}
        onClick={() => setSidebarOpen(false)}
      ></div>

      {/* Sidebar */}
      <aside className={`dashboard-sidebar ${sidebarOpen ? "open" : "closed"}`}>
        <div className="dashboard-sidebar-header">
          <div className="dashboard-logo">
            <div className="dashboard-logo-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2.5}
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            {sidebarOpen && (
              <span className="dashboard-logo-text">{t("appName")}</span>
            )}
          </div>
        </div>

        <nav className="dashboard-nav">
          {menuItems.map((item, index) => (
            <a
              key={index}
              href={item.path}
              className={`dashboard-nav-item ${item.active ? "active" : ""}`}
            >
              <span className="dashboard-nav-icon">{item.icon}</span>
              {sidebarOpen && (
                <span className="dashboard-nav-label">{item.label}</span>
              )}
            </a>
          ))}
        </nav>
      </aside>

      {/* Main Content */}
      <div className="dashboard-main">
        {/* Header */}
        <header className="dashboard-header">
          <div className="dashboard-header-left">
            <button
              className="dashboard-sidebar-toggle"
              onClick={() => setSidebarOpen(!sidebarOpen)}
            >
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
            <h1 className="dashboard-page-title">{t("dashboard")}</h1>
          </div>

          <div className="dashboard-header-right">
            <button onClick={toggleLanguage} className="dashboard-lang-btn">
              <svg
                className="dashboard-icon"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                />
              </svg>
              <span>{language === "en" ? "বাংলা" : "English"}</span>
            </button>

            <div className="dashboard-user-menu">
              <div className="dashboard-user-info">
                <div className="dashboard-user-avatar">
                  {user?.name?.charAt(0).toUpperCase() || "U"}
                </div>
                <div className="dashboard-user-details">
                  <span className="dashboard-user-name">
                    {user?.name || "User"}
                  </span>
                  <span className="dashboard-user-role">
                    {user?.role || "Staff"}
                  </span>
                </div>
              </div>
              <button onClick={handleLogout} className="dashboard-logout-btn">
                <svg
                  className="dashboard-icon"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                  />
                </svg>
              </button>
            </div>
          </div>
        </header>

        {/* Content */}
        <div className="dashboard-content">
          {/* Stats Cards */}
          <div className="dashboard-stats-grid">
            <div className="dashboard-stat-card card-sales">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("todaySales")}</h3>
                <p className="dashboard-stat-value">
                  ৳{stats?.today_sales?.toFixed(2) || "0.00"}
                </p>
              </div>
            </div>

            <div className="dashboard-stat-card card-monthly">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("monthlySales")}</h3>
                <p className="dashboard-stat-value">
                  ৳{stats?.month_sales?.toFixed(2) || "0.00"}
                </p>
              </div>
            </div>

            <div className="dashboard-stat-card card-profit">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("netProfit")}</h3>
                <p className="dashboard-stat-value">
                  ৳{stats?.today_profit?.toFixed(2) || "0.00"}
                </p>
              </div>
            </div>

            <div className="dashboard-stat-card card-products">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("products")}</h3>
                <p className="dashboard-stat-value">
                  {stats?.total_products || 0}
                </p>
              </div>
            </div>

            <div className="dashboard-stat-card card-lowstock">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("lowStock")}</h3>
                <p className="dashboard-stat-value">
                  {stats?.low_stock_count || 0}
                </p>
              </div>
            </div>

            <div className="dashboard-stat-card card-dues">
              <div className="dashboard-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                  />
                </svg>
              </div>
              <div className="dashboard-stat-info">
                <h3 className="dashboard-stat-label">{t("due")}</h3>
                <p className="dashboard-stat-value">
                  ৳{stats?.purchase_dues?.toFixed(2) || "0.00"}
                </p>
              </div>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="dashboard-section">
            <h2 className="dashboard-section-title">
              {t("pos")} - Quick Actions
            </h2>
            <div className="dashboard-quick-actions">
              <a href="/pos" className="dashboard-action-card action-primary">
                <div className="dashboard-action-icon">🛒</div>
                <span className="dashboard-action-label">New Sale</span>
              </a>
              <a
                href="/purchases/new"
                className="dashboard-action-card action-success"
              >
                <div className="dashboard-action-icon">📥</div>
                <span className="dashboard-action-label">New Purchase</span>
              </a>
              <a
                href="/products/new"
                className="dashboard-action-card action-purple"
              >
                <div className="dashboard-action-icon">📦</div>
                <span className="dashboard-action-label">Add Product</span>
              </a>
              <a href="/reports" className="dashboard-action-card action-info">
                <div className="dashboard-action-icon">📊</div>
                <span className="dashboard-action-label">View Reports</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
