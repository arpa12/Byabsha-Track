import React, { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import api from "../services/api";
import "./Dashboard.css";

const Reports = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  // State Management
  const [branches, setBranches] = useState([]);
  const [loading, setLoading] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);

  // Active Report Type
  const [activeReport, setActiveReport] = useState("dashboard");

  // Filter State
  const [filters, setFilters] = useState({
    branch_id: "",
    date: new Date().toISOString().split("T")[0],
    start_date: new Date(new Date().setDate(1)).toISOString().split("T")[0], // First day of month
    end_date: new Date().toISOString().split("T")[0],
    year: new Date().getFullYear(),
    month: new Date().getMonth() + 1,
  });

  // Report Data
  const [reportData, setReportData] = useState(null);

  // Fetch branches
  const fetchBranches = useCallback(async () => {
    try {
      const response = await api.get("/branches");
      const branchesData = response.data.branches || response.data;
      setBranches(Array.isArray(branchesData) ? branchesData : []);
    } catch (err) {
      console.error("[Reports] Error fetching branches:", err);
      setBranches([]);
    }
  }, []);

  // Fetch report data based on active report type
  const fetchReportData = useCallback(async () => {
    try {
      setLoading(true);
      let endpoint = "";
      let params = {};

      switch (activeReport) {
        case "dashboard":
          endpoint = "/reports/dashboard";
          params = { branch_id: filters.branch_id || undefined };
          break;
        case "daily-profit":
          endpoint = "/reports/daily-profit";
          params = {
            date: filters.date,
            branch_id: filters.branch_id || undefined,
          };
          break;
        case "monthly-profit":
          endpoint = "/reports/monthly-profit";
          params = {
            year: filters.year,
            month: filters.month,
            branch_id: filters.branch_id || undefined,
          };
          break;
        case "sales-summary":
          endpoint = "/reports/sales-summary";
          params = {
            start_date: filters.start_date,
            end_date: filters.end_date,
            branch_id: filters.branch_id || undefined,
          };
          break;
        case "purchase-summary":
          endpoint = "/reports/purchase-summary";
          params = {
            start_date: filters.start_date,
            end_date: filters.end_date,
            branch_id: filters.branch_id || undefined,
          };
          break;
        case "top-selling":
          endpoint = "/reports/top-selling-products";
          params = {
            start_date: filters.start_date,
            end_date: filters.end_date,
            branch_id: filters.branch_id || undefined,
            limit: 10,
          };
          break;
        case "daily-sales":
          endpoint = "/reports/daily-sales";
          params = {
            date: filters.date,
            branch_id: filters.branch_id || undefined,
          };
          break;
        case "monthly-sales":
          endpoint = "/reports/monthly-sales";
          params = {
            year: filters.year,
            month: filters.month,
            branch_id: filters.branch_id || undefined,
          };
          break;
        default:
          return;
      }

      const response = await api.get(endpoint, { params });
      setReportData(response.data.data || response.data);
    } catch (err) {
      console.error("[Reports] Error fetching report:", err);
      setReportData(null);
    } finally {
      setLoading(false);
    }
  }, [activeReport, filters]);

  // Initial data fetch
  useEffect(() => {
    fetchBranches();
  }, [fetchBranches]);

  // Fetch report when filters or report type change
  useEffect(() => {
    fetchReportData();
  }, [fetchReportData]);

  // Format currency
  const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-BD", {
      style: "currency",
      currency: "BDT",
    }).format(amount || 0);
  };

  // Render Dashboard Report
  const renderDashboardReport = () => {
    if (!reportData) return null;

    return (
      <div className="report-content">
        <h2 className="report-title">
          {t.language === "en"
            ? "Dashboard Overview"
            : "à¦¡à§à¦¯à¦¾à¦¶à¦¬à§‹à¦°à§à¦¡ à¦¸à¦¾à¦°à¦¸à¦‚à¦•à§à¦·à§‡à¦ª"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-icon">ðŸ’°</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en"
                  ? "Total Sales"
                  : "à¦®à§‹à¦Ÿ à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_sales || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">ðŸ›’</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en"
                  ? "Total Purchases"
                  : "à¦®à§‹à¦Ÿ à¦•à§à¦°à¦¯à¦¼"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_purchases || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-icon">ðŸ“ˆ</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Gross Profit" : "à¦®à§‹à¦Ÿ à¦²à¦¾à¦­"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.gross_profit || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">ðŸ’¸</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Total Expenses" : "à¦®à§‹à¦Ÿ à¦–à¦°à¦š"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_expenses || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card net-profit">
            <div className="stat-icon">âœ¨</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Net Profit" : "à¦¨à¦¿à¦Ÿ à¦²à¦¾à¦­"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.net_profit || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">ðŸ“¦</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en"
                  ? "Total Products"
                  : "à¦®à§‹à¦Ÿ à¦ªà¦£à§à¦¯"}
              </div>
              <div className="stat-value">{reportData.total_products || 0}</div>
            </div>
          </div>
        </div>
      </div>
    );
  };

  // Render Daily Profit Report
  const renderDailyProfitReport = () => {
    if (!reportData) return null;

    return (
      <div className="report-content">
        <h2 className="report-title">
          {t.language === "en"
            ? "Daily Profit Report"
            : "à¦¦à§ˆà¦¨à¦¿à¦• à¦²à¦¾à¦­à§‡à¦° à¦ªà§à¦°à¦¤à¦¿à¦¬à§‡à¦¦à¦¨"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Total Sales"
                : "à¦®à§‹à¦Ÿ à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.sales?.total_amount || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Cost" : "à¦®à§‹à¦Ÿ à¦–à¦°à¦š"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.purchases?.total_amount || 0)}
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-label">
              {t.language === "en" ? "Gross Profit" : "à¦®à§‹à¦Ÿ à¦²à¦¾à¦­"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.gross_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Profit Margin"
                : "à¦²à¦¾à¦­à§‡à¦° à¦®à¦¾à¦°à§à¦œà¦¿à¦¨"}
            </div>
            <div className="stat-value">
              {reportData.profit_margin
                ? `${reportData.profit_margin.toFixed(2)}%`
                : "0%"}
            </div>
          </div>
        </div>
      </div>
    );
  };

  // Render Sales Summary Report
  const renderSalesSummaryReport = () => {
    if (!reportData) return null;

    return (
      <div className="report-content">
        <h2 className="report-title">
          {t.language === "en"
            ? "Sales Summary Report"
            : "à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼ à¦¸à¦¾à¦°à¦¸à¦‚à¦•à§à¦·à§‡à¦ª à¦ªà§à¦°à¦¤à¦¿à¦¬à§‡à¦¦à¦¨"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Sales Count"
                : "à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼ à¦¸à¦‚à¦–à§à¦¯à¦¾"}
            </div>
            <div className="stat-value">{reportData.sales_count || 0}</div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Total Sales"
                : "à¦®à§‹à¦Ÿ à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_sales || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Total Paid"
                : "à¦®à§‹à¦Ÿ à¦ªà¦°à¦¿à¦¶à§‹à¦§à¦¿à¦¤"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_paid || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Due" : "à¦®à§‹à¦Ÿ à¦¬à¦¾à¦•à¦¿"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_due || 0)}
            </div>
          </div>
        </div>

        {reportData.payment_status && reportData.payment_status.length > 0 && (
          <div className="data-table-container">
            <h3 className="section-title">
              {t.language === "en"
                ? "Payment Status Breakdown"
                : "à¦ªà§‡à¦®à§‡à¦¨à§à¦Ÿ à¦¸à§à¦Ÿà§à¦¯à¦¾à¦Ÿà¦¾à¦¸ à¦¬à¦¿à¦¸à§à¦¤à¦¾à¦°à¦¿à¦¤"}
            </h3>
            <table className="data-table">
              <thead>
                <tr>
                  <th>
                    {t.language === "en" ? "Status" : "à¦…à¦¬à¦¸à§à¦¥à¦¾"}
                  </th>
                  <th>{t.language === "en" ? "Count" : "à¦¸à¦‚à¦–à§à¦¯à¦¾"}</th>
                </tr>
              </thead>
              <tbody>
                {reportData.payment_status.map((item, index) => (
                  <tr key={index}>
                    <td className="capitalize">{item.payment_status}</td>
                    <td>{item.count}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    );
  };

  // Render Top Selling Products Report
  const renderTopSellingReport = () => {
    if (!reportData || !reportData.products) return null;

    return (
      <div className="report-content">
        <h2 className="report-title">
          {t.language === "en"
            ? "Top Selling Products"
            : "à¦¸à¦°à§à¦¬à¦¾à¦§à¦¿à¦• à¦¬à¦¿à¦•à§à¦°à¦¿à¦¤ à¦ªà¦£à§à¦¯"}
        </h2>

        <div className="data-table-container">
          <table className="data-table">
            <thead>
              <tr>
                <th>
                  {t.language === "en" ? "Rank" : "à¦°â€à§à¦¯à¦¾à¦™à§à¦•"}
                </th>
                <th>{t.language === "en" ? "Product" : "à¦ªà¦£à§à¦¯"}</th>
                <th>
                  {t.language === "en"
                    ? "Quantity Sold"
                    : "à¦¬à¦¿à¦•à§à¦°à¦¿à¦¤ à¦ªà¦°à¦¿à¦®à¦¾à¦£"}
                </th>
                <th>
                  {t.language === "en"
                    ? "Total Revenue"
                    : "à¦®à§‹à¦Ÿ à¦°à¦¾à¦œà¦¸à§à¦¬"}
                </th>
              </tr>
            </thead>
            <tbody>
              {reportData.products.map((product, index) => (
                <tr key={index}>
                  <td className="rank">{index + 1}</td>
                  <td className="product-name">{product.product_name}</td>
                  <td>{product.total_quantity}</td>
                  <td>{formatCurrency(product.total_revenue)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    );
  };

  // Render Monthly Profit Report
  const renderMonthlyProfitReport = () => {
    if (!reportData) return null;

    return (
      <div className="report-content">
        <h2 className="report-title">
          {t.language === "en"
            ? "Monthly Profit Report"
            : "à¦®à¦¾à¦¸à¦¿à¦• à¦²à¦¾à¦­à§‡à¦° à¦ªà§à¦°à¦¤à¦¿à¦¬à§‡à¦¦à¦¨"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Total Sales"
                : "à¦®à§‹à¦Ÿ à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_sales || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Cost" : "à¦®à§‹à¦Ÿ à¦–à¦°à¦š"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_cost || 0)}
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-label">
              {t.language === "en" ? "Gross Profit" : "à¦®à§‹à¦Ÿ à¦²à¦¾à¦­"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.gross_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Expenses" : "à¦®à§‹à¦Ÿ à¦–à¦°à¦š"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_expenses || 0)}
            </div>
          </div>

          <div className="stat-card net-profit">
            <div className="stat-label">
              {t.language === "en" ? "Net Profit" : "à¦¨à¦¿à¦Ÿ à¦²à¦¾à¦­"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.net_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en"
                ? "Profit Margin"
                : "à¦²à¦¾à¦­à§‡à¦° à¦®à¦¾à¦°à§à¦œà¦¿à¦¨"}
            </div>
            <div className="stat-value">
              {reportData.profit_margin
                ? `${reportData.profit_margin.toFixed(2)}%`
                : "0%"}
            </div>
          </div>
        </div>
      </div>
    );
  };

  // Render report based on active report type
  const renderReport = () => {
    if (loading) {
      return (
        <div className="loading-state">
          <div className="spinner"></div>
          <p>
            {t.language === "en"
              ? "Loading report..."
              : "à¦ªà§à¦°à¦¤à¦¿à¦¬à§‡à¦¦à¦¨ à¦²à§‹à¦¡ à¦¹à¦šà§à¦›à§‡..."}
          </p>
        </div>
      );
    }

    if (!reportData) {
      return (
        <div className="empty-state">
          <div className="empty-icon">ðŸ“Š</div>
          <h3>
            {t.language === "en"
              ? "No Data Available"
              : "à¦•à§‹à¦¨ à¦¤à¦¥à§à¦¯ à¦ªà¦¾à¦“à¦¯à¦¼à¦¾ à¦¯à¦¾à¦¯à¦¼à¦¨à¦¿"}
          </h3>
        </div>
      );
    }

    switch (activeReport) {
      case "dashboard":
        return renderDashboardReport();
      case "daily-profit":
        return renderDailyProfitReport();
      case "monthly-profit":
        return renderMonthlyProfitReport();
      case "sales-summary":
        return renderSalesSummaryReport();
      case "top-selling":
        return renderTopSellingReport();
      default:
        return renderSalesSummaryReport();
    }
  };

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const menuItems = [
    { icon: "ðŸ“Š", label: t("dashboard"), path: "/dashboard" },
    { icon: "ðŸ›’", label: t("pos"), path: "/pos" },
    { icon: "ðŸ’°", label: t("sales"), path: "/sales" },
    { icon: "ðŸ“¦", label: t("products"), path: "/products" },
    { icon: "ðŸ·ï¸", label: t("categories"), path: "/categories" },
    { icon: "ðŸ“¥", label: t("purchases"), path: "/purchases" },
    { icon: "ðŸ­", label: t("suppliers"), path: "/suppliers" },
    { icon: "ðŸ’¸", label: t("expenses"), path: "/expenses" },
    { icon: "ðŸ¢", label: t("branches"), path: "/branches" },
    { icon: "ðŸ“ˆ", label: t("reports"), path: "/reports", active: true },
    { icon: "ðŸ‘¥", label: t("users"), path: "/users" },
    { icon: "âš™ï¸", label: t("settings"), path: "/settings" },
  ];

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
      <main className="dashboard-main">
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
            <h1 className="dashboard-page-title">{t("reports")}</h1>
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
              <span>{language === "en" ? "à¦¬à¦¾à¦‚à¦²à¦¾" : "English"}</span>
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

        <div className="dashboard-content">
          <div className="reports-header">
            <div className="header-left">
              <h1>{t.reports}</h1>
              <p className="subtitle">
                {t.language === "en"
                  ? "Business analytics and insights"
                  : "à¦¬à§à¦¯à¦¬à¦¸à¦¾à¦¯à¦¼à¦¿à¦• à¦¬à¦¿à¦¶à§à¦²à§‡à¦·à¦£ à¦à¦¬à¦‚ à¦…à¦¨à§à¦¤à¦°à§à¦¦à§ƒà¦·à§à¦Ÿà¦¿"}
              </p>
            </div>
          </div>

          {/* Report Type Selector */}
          <div className="report-selector">
            <button
              className={`report-tab ${activeReport === "dashboard" ? "active" : ""}`}
              onClick={() => setActiveReport("dashboard")}
            >
              {t.language === "en"
                ? "Dashboard"
                : "à¦¡à§à¦¯à¦¾à¦¶à¦¬à§‹à¦°à§à¦¡"}
            </button>
            <button
              className={`report-tab ${activeReport === "daily-profit" ? "active" : ""}`}
              onClick={() => setActiveReport("daily-profit")}
            >
              {t.language === "en"
                ? "Daily Profit"
                : "à¦¦à§ˆà¦¨à¦¿à¦• à¦²à¦¾à¦­"}
            </button>
            <button
              className={`report-tab ${activeReport === "monthly-profit" ? "active" : ""}`}
              onClick={() => setActiveReport("monthly-profit")}
            >
              {t.language === "en"
                ? "Monthly Profit"
                : "à¦®à¦¾à¦¸à¦¿à¦• à¦²à¦¾à¦­"}
            </button>
            <button
              className={`report-tab ${activeReport === "sales-summary" ? "active" : ""}`}
              onClick={() => setActiveReport("sales-summary")}
            >
              {t.language === "en"
                ? "Sales Summary"
                : "à¦¬à¦¿à¦•à§à¦°à¦¯à¦¼ à¦¸à¦¾à¦°à¦¸à¦‚à¦•à§à¦·à§‡à¦ª"}
            </button>
            <button
              className={`report-tab ${activeReport === "top-selling" ? "active" : ""}`}
              onClick={() => setActiveReport("top-selling")}
            >
              {t.language === "en"
                ? "Top Selling"
                : "à¦¸à¦°à§à¦¬à¦¾à¦§à¦¿à¦• à¦¬à¦¿à¦•à§à¦°à¦¿à¦¤"}
            </button>
          </div>

          {/* Filters */}
          <div className="reports-filters">
            <div className="filter-group">
              <label>{t.language === "en" ? "Branch" : "à¦¶à¦¾à¦–à¦¾"}</label>
              <select
                value={filters.branch_id}
                onChange={(e) =>
                  setFilters((prev) => ({ ...prev, branch_id: e.target.value }))
                }
                className="filter-select"
              >
                <option value="">
                  {t.language === "en"
                    ? "All Branches"
                    : "à¦¸à¦•à¦² à¦¶à¦¾à¦–à¦¾"}
                </option>
                {branches.map((branch) => (
                  <option key={branch.id} value={branch.id}>
                    {branch.name}
                  </option>
                ))}
              </select>
            </div>

            {(activeReport === "daily-profit" ||
              activeReport === "daily-sales") && (
              <div className="filter-group">
                <label>
                  {t.language === "en" ? "Date" : "à¦¤à¦¾à¦°à¦¿à¦–"}
                </label>
                <input
                  type="date"
                  value={filters.date}
                  onChange={(e) =>
                    setFilters((prev) => ({ ...prev, date: e.target.value }))
                  }
                  className="filter-input"
                />
              </div>
            )}

            {(activeReport === "monthly-profit" ||
              activeReport === "monthly-sales") && (
              <>
                <div className="filter-group">
                  <label>{t.language === "en" ? "Year" : "à¦¬à¦›à¦°"}</label>
                  <input
                    type="number"
                    value={filters.year}
                    onChange={(e) =>
                      setFilters((prev) => ({
                        ...prev,
                        year: parseInt(e.target.value),
                      }))
                    }
                    min="2000"
                    max="2100"
                    className="filter-input"
                  />
                </div>
                <div className="filter-group">
                  <label>{t.language === "en" ? "Month" : "à¦®à¦¾à¦¸"}</label>
                  <select
                    value={filters.month}
                    onChange={(e) =>
                      setFilters((prev) => ({
                        ...prev,
                        month: parseInt(e.target.value),
                      }))
                    }
                    className="filter-select"
                  >
                    <option value="1">
                      {t.language === "en"
                        ? "January"
                        : "à¦œà¦¾à¦¨à§à¦¯à¦¼à¦¾à¦°à§€"}
                    </option>
                    <option value="2">
                      {t.language === "en"
                        ? "February"
                        : "à¦«à§‡à¦¬à§à¦°à§à¦¯à¦¼à¦¾à¦°à§€"}
                    </option>
                    <option value="3">
                      {t.language === "en" ? "March" : "à¦®à¦¾à¦°à§à¦š"}
                    </option>
                    <option value="4">
                      {t.language === "en" ? "April" : "à¦à¦ªà§à¦°à¦¿à¦²"}
                    </option>
                    <option value="5">
                      {t.language === "en" ? "May" : "à¦®à§‡"}
                    </option>
                    <option value="6">
                      {t.language === "en" ? "June" : "à¦œà§à¦¨"}
                    </option>
                    <option value="7">
                      {t.language === "en" ? "July" : "à¦œà§à¦²à¦¾à¦‡"}
                    </option>
                    <option value="8">
                      {t.language === "en" ? "August" : "à¦†à¦—à¦¸à§à¦Ÿ"}
                    </option>
                    <option value="9">
                      {t.language === "en"
                        ? "September"
                        : "à¦¸à§‡à¦ªà§à¦Ÿà§‡à¦®à§à¦¬à¦°"}
                    </option>
                    <option value="10">
                      {t.language === "en" ? "October" : "à¦…à¦•à§à¦Ÿà§‹à¦¬à¦°"}
                    </option>
                    <option value="11">
                      {t.language === "en"
                        ? "November"
                        : "à¦¨à¦­à§‡à¦®à§à¦¬à¦°"}
                    </option>
                    <option value="12">
                      {t.language === "en"
                        ? "December"
                        : "à¦¡à¦¿à¦¸à§‡à¦®à§à¦¬à¦°"}
                    </option>
                  </select>
                </div>
              </>
            )}

            {(activeReport === "sales-summary" ||
              activeReport === "purchase-summary" ||
              activeReport === "top-selling") && (
              <>
                <div className="filter-group">
                  <label>
                    {t.language === "en"
                      ? "Start Date"
                      : "à¦¶à§à¦°à§à¦° à¦¤à¦¾à¦°à¦¿à¦–"}
                  </label>
                  <input
                    type="date"
                    value={filters.start_date}
                    onChange={(e) =>
                      setFilters((prev) => ({
                        ...prev,
                        start_date: e.target.value,
                      }))
                    }
                    className="filter-input"
                  />
                </div>
                <div className="filter-group">
                  <label>
                    {t.language === "en"
                      ? "End Date"
                      : "à¦¶à§‡à¦· à¦¤à¦¾à¦°à¦¿à¦–"}
                  </label>
                  <input
                    type="date"
                    value={filters.end_date}
                    onChange={(e) =>
                      setFilters((prev) => ({
                        ...prev,
                        end_date: e.target.value,
                      }))
                    }
                    className="filter-input"
                  />
                </div>
              </>
            )}

            <button className="btn-refresh" onClick={fetchReportData}>
              ðŸ”„ {t.language === "en" ? "Refresh" : "à¦°à¦¿à¦«à§à¦°à§‡à¦¶"}
            </button>
          </div>

          {/* Report Display */}
          <div className="report-display">{renderReport()}</div>
        </div>
      </main>
    </div>
  );
};

export default Reports;
