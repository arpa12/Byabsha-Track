import React, { useState, useEffect, useCallback } from "react";
import { useLanguage } from "../context/LanguageContext";
import api from "../services/api";

const Reports = () => {
  const { t } = useLanguage();

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
          {t.language === "en" ? "Dashboard Overview" : "ড্যাশবোর্ড সারসংক্ষেপ"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-icon">💰</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Total Sales" : "মোট বিক্রয়"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_sales || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">🛒</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Total Purchases" : "মোট ক্রয়"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_purchases || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-icon">📈</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Gross Profit" : "মোট লাভ"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.gross_profit || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">💸</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Total Expenses" : "মোট খরচ"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.total_expenses || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card net-profit">
            <div className="stat-icon">✨</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Net Profit" : "নিট লাভ"}
              </div>
              <div className="stat-value">
                {formatCurrency(reportData.net_profit || 0)}
              </div>
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-icon">📦</div>
            <div className="stat-details">
              <div className="stat-label">
                {t.language === "en" ? "Total Products" : "মোট পণ্য"}
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
            : "দৈনিক লাভের প্রতিবেদন"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Sales" : "মোট বিক্রয়"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.sales?.total_amount || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Cost" : "মোট খরচ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.purchases?.total_amount || 0)}
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-label">
              {t.language === "en" ? "Gross Profit" : "মোট লাভ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.gross_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Profit Margin" : "লাভের মার্জিন"}
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
            : "বিক্রয় সারসংক্ষেপ প্রতিবেদন"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Sales Count" : "বিক্রয় সংখ্যা"}
            </div>
            <div className="stat-value">{reportData.sales_count || 0}</div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Sales" : "মোট বিক্রয়"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_sales || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Paid" : "মোট পরিশোধিত"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_paid || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Due" : "মোট বাকি"}
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
                : "পেমেন্ট স্ট্যাটাস বিস্তারিত"}
            </h3>
            <table className="data-table">
              <thead>
                <tr>
                  <th>{t.language === "en" ? "Status" : "অবস্থা"}</th>
                  <th>{t.language === "en" ? "Count" : "সংখ্যা"}</th>
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
            : "সর্বাধিক বিক্রিত পণ্য"}
        </h2>

        <div className="data-table-container">
          <table className="data-table">
            <thead>
              <tr>
                <th>{t.language === "en" ? "Rank" : "র‍্যাঙ্ক"}</th>
                <th>{t.language === "en" ? "Product" : "পণ্য"}</th>
                <th>
                  {t.language === "en" ? "Quantity Sold" : "বিক্রিত পরিমাণ"}
                </th>
                <th>{t.language === "en" ? "Total Revenue" : "মোট রাজস্ব"}</th>
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
            : "মাসিক লাভের প্রতিবেদন"}
        </h2>

        <div className="stats-grid">
          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Sales" : "মোট বিক্রয়"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_sales || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Cost" : "মোট খরচ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_cost || 0)}
            </div>
          </div>

          <div className="stat-card profit">
            <div className="stat-label">
              {t.language === "en" ? "Gross Profit" : "মোট লাভ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.gross_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Total Expenses" : "মোট খরচ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.total_expenses || 0)}
            </div>
          </div>

          <div className="stat-card net-profit">
            <div className="stat-label">
              {t.language === "en" ? "Net Profit" : "নিট লাভ"}
            </div>
            <div className="stat-value">
              {formatCurrency(reportData.net_profit || 0)}
            </div>
          </div>

          <div className="stat-card">
            <div className="stat-label">
              {t.language === "en" ? "Profit Margin" : "লাভের মার্জিন"}
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
              : "প্রতিবেদন লোড হচ্ছে..."}
          </p>
        </div>
      );
    }

    if (!reportData) {
      return (
        <div className="empty-state">
          <div className="empty-icon">📊</div>
          <h3>
            {t.language === "en"
              ? "No Data Available"
              : "কোন তথ্য পাওয়া যায়নি"}
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

  return (
    <div className="reports-page">
      {/* Sidebar */}
      <aside className={`reports-sidebar ${sidebarOpen ? "open" : "closed"}`}>
        <div className="sidebar-header">
          <h2>{t.appName}</h2>
          <button
            className="sidebar-toggle"
            onClick={() => setSidebarOpen(!sidebarOpen)}
          >
            {sidebarOpen ? "×" : "☰"}
          </button>
        </div>
        <nav className="sidebar-nav">
          <a href="/dashboard" className="nav-item">
            <span className="nav-icon">📊</span>
            <span className="nav-text">{t.dashboard}</span>
          </a>
          <a href="/pos" className="nav-item">
            <span className="nav-icon">🛒</span>
            <span className="nav-text">{t.pos}</span>
          </a>
          <a href="/sales" className="nav-item">
            <span className="nav-icon">💰</span>
            <span className="nav-text">{t.sales}</span>
          </a>
          <a href="/products" className="nav-item">
            <span className="nav-icon">📦</span>
            <span className="nav-text">{t.products}</span>
          </a>
          <a href="/categories" className="nav-item">
            <span className="nav-icon">📂</span>
            <span className="nav-text">{t.categories}</span>
          </a>
          <a href="/reports" className="nav-item active">
            <span className="nav-icon">📈</span>
            <span className="nav-text">{t.reports}</span>
          </a>
        </nav>
      </aside>

      {/* Main Content */}
      <main className="reports-content">
        {/* Header */}
        <div className="reports-header">
          <div className="header-left">
            <h1>{t.reports}</h1>
            <p className="subtitle">
              {t.language === "en"
                ? "Business analytics and insights"
                : "ব্যবসায়িক বিশ্লেষণ এবং অন্তর্দৃষ্টি"}
            </p>
          </div>
        </div>

        {/* Report Type Selector */}
        <div className="report-selector">
          <button
            className={`report-tab ${activeReport === "dashboard" ? "active" : ""}`}
            onClick={() => setActiveReport("dashboard")}
          >
            {t.language === "en" ? "Dashboard" : "ড্যাশবোর্ড"}
          </button>
          <button
            className={`report-tab ${activeReport === "daily-profit" ? "active" : ""}`}
            onClick={() => setActiveReport("daily-profit")}
          >
            {t.language === "en" ? "Daily Profit" : "দৈনিক লাভ"}
          </button>
          <button
            className={`report-tab ${activeReport === "monthly-profit" ? "active" : ""}`}
            onClick={() => setActiveReport("monthly-profit")}
          >
            {t.language === "en" ? "Monthly Profit" : "মাসিক লাভ"}
          </button>
          <button
            className={`report-tab ${activeReport === "sales-summary" ? "active" : ""}`}
            onClick={() => setActiveReport("sales-summary")}
          >
            {t.language === "en" ? "Sales Summary" : "বিক্রয় সারসংক্ষেপ"}
          </button>
          <button
            className={`report-tab ${activeReport === "top-selling" ? "active" : ""}`}
            onClick={() => setActiveReport("top-selling")}
          >
            {t.language === "en" ? "Top Selling" : "সর্বাধিক বিক্রিত"}
          </button>
        </div>

        {/* Filters */}
        <div className="reports-filters">
          <div className="filter-group">
            <label>{t.language === "en" ? "Branch" : "শাখা"}</label>
            <select
              value={filters.branch_id}
              onChange={(e) =>
                setFilters((prev) => ({ ...prev, branch_id: e.target.value }))
              }
              className="filter-select"
            >
              <option value="">
                {t.language === "en" ? "All Branches" : "সকল শাখা"}
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
              <label>{t.language === "en" ? "Date" : "তারিখ"}</label>
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
                <label>{t.language === "en" ? "Year" : "বছর"}</label>
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
                <label>{t.language === "en" ? "Month" : "মাস"}</label>
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
                    {t.language === "en" ? "January" : "জানুয়ারী"}
                  </option>
                  <option value="2">
                    {t.language === "en" ? "February" : "ফেব্রুয়ারী"}
                  </option>
                  <option value="3">
                    {t.language === "en" ? "March" : "মার্চ"}
                  </option>
                  <option value="4">
                    {t.language === "en" ? "April" : "এপ্রিল"}
                  </option>
                  <option value="5">
                    {t.language === "en" ? "May" : "মে"}
                  </option>
                  <option value="6">
                    {t.language === "en" ? "June" : "জুন"}
                  </option>
                  <option value="7">
                    {t.language === "en" ? "July" : "জুলাই"}
                  </option>
                  <option value="8">
                    {t.language === "en" ? "August" : "আগস্ট"}
                  </option>
                  <option value="9">
                    {t.language === "en" ? "September" : "সেপ্টেম্বর"}
                  </option>
                  <option value="10">
                    {t.language === "en" ? "October" : "অক্টোবর"}
                  </option>
                  <option value="11">
                    {t.language === "en" ? "November" : "নভেম্বর"}
                  </option>
                  <option value="12">
                    {t.language === "en" ? "December" : "ডিসেম্বর"}
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
                  {t.language === "en" ? "Start Date" : "শুরুর তারিখ"}
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
                <label>{t.language === "en" ? "End Date" : "শেষ তারিখ"}</label>
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
            🔄 {t.language === "en" ? "Refresh" : "রিফ্রেশ"}
          </button>
        </div>

        {/* Report Display */}
        <div className="report-display">{renderReport()}</div>
      </main>

      <style jsx>{`
        .reports-page {
          display: flex;
          min-height: 100vh;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Sidebar */
        .reports-sidebar {
          width: 260px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
          transition: all 0.3s ease;
          position: fixed;
          left: 0;
          top: 0;
          height: 100vh;
          z-index: 100;
        }

        .reports-sidebar.closed {
          width: 70px;
        }

        .sidebar-header {
          padding: 25px 20px;
          border-bottom: 1px solid rgba(0, 0, 0, 0.1);
          display: flex;
          justify-content: space-between;
          align-items: center;
        }

        .sidebar-header h2 {
          font-size: 22px;
          font-weight: 700;
          color: #667eea;
          margin: 0;
          white-space: nowrap;
          overflow: hidden;
        }

        .reports-sidebar.closed .sidebar-header h2 {
          opacity: 0;
        }

        .sidebar-toggle {
          background: none;
          border: none;
          font-size: 26px;
          cursor: pointer;
          color: #667eea;
          padding: 5px;
          line-height: 1;
        }

        .sidebar-nav {
          padding: 20px 0;
        }

        .nav-item {
          display: flex;
          align-items: center;
          padding: 15px 20px;
          color: #333;
          text-decoration: none;
          transition: all 0.3s ease;
          border-left: 3px solid transparent;
        }

        .nav-item:hover {
          background: rgba(102, 126, 234, 0.1);
          border-left-color: #667eea;
        }

        .nav-item.active {
          background: rgba(102, 126, 234, 0.15);
          border-left-color: #667eea;
          color: #667eea;
          font-weight: 600;
        }

        .nav-icon {
          font-size: 22px;
          margin-right: 15px;
          width: 30px;
          text-align: center;
        }

        .reports-sidebar.closed .nav-text {
          display: none;
        }

        /* Main Content */
        .reports-content {
          flex: 1;
          margin-left: 260px;
          padding: 30px;
          transition: margin-left 0.3s ease;
        }

        .reports-sidebar.closed ~ .reports-content {
          margin-left: 70px;
        }

        /* Header */
        .reports-header {
          margin-bottom: 25px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          padding: 25px 30px;
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header-left h1 {
          font-size: 32px;
          font-weight: 700;
          color: #333;
          margin: 0 0 8px 0;
        }

        .subtitle {
          font-size: 16px;
          color: #666;
          margin: 0;
        }

        /* Report Selector */
        .report-selector {
          display: flex;
          gap: 10px;
          margin-bottom: 20px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          padding: 15px 20px;
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
          overflow-x: auto;
        }

        .report-tab {
          padding: 12px 24px;
          border: 2px solid rgba(102, 126, 234, 0.2);
          background: white;
          border-radius: 10px;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          white-space: nowrap;
        }

        .report-tab:hover {
          border-color: #667eea;
          background: rgba(102, 126, 234, 0.05);
        }

        .report-tab.active {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          border-color: transparent;
        }

        /* Filters */
        .reports-filters {
          display: flex;
          gap: 15px;
          margin-bottom: 25px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          padding: 20px;
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
          flex-wrap: wrap;
        }

        .filter-group {
          flex: 1;
          min-width: 180px;
        }

        .filter-group label {
          display: block;
          font-size: 14px;
          font-weight: 600;
          color: #333;
          margin-bottom: 6px;
        }

        .filter-input,
        .filter-select {
          width: 100%;
          padding: 12px 16px;
          border: 2px solid rgba(102, 126, 234, 0.2);
          border-radius: 10px;
          font-size: 15px;
          transition: all 0.3s ease;
          background: white;
        }

        .filter-input:focus,
        .filter-select:focus {
          outline: none;
          border-color: #667eea;
          box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-refresh {
          padding: 12px 24px;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          border: none;
          border-radius: 10px;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          align-self: flex-end;
        }

        .btn-refresh:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Report Display */
        .report-display {
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
          padding: 30px;
        }

        .report-content {
          width: 100%;
        }

        .report-title {
          font-size: 24px;
          font-weight: 700;
          color: #333;
          margin: 0 0 25px 0;
        }

        /* Stats Grid */
        .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .stat-card {
          background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
          border-radius: 12px;
          padding: 20px;
          display: flex;
          align-items: center;
          gap: 15px;
        }

        .stat-card.profit {
          background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-card.net-profit {
          background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .stat-icon {
          font-size: 40px;
        }

        .stat-details {
          flex: 1;
        }

        .stat-label {
          font-size: 14px;
          color: #666;
          margin-bottom: 5px;
        }

        .stat-value {
          font-size: 22px;
          font-weight: 700;
          color: #333;
        }

        /* Data Table */
        .data-table-container {
          margin-top: 30px;
        }

        .section-title {
          font-size: 18px;
          font-weight: 600;
          color: #333;
          margin-bottom: 15px;
        }

        .data-table {
          width: 100%;
          border-collapse: collapse;
        }

        .data-table thead {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
        }

        .data-table th {
          padding: 15px;
          text-align: left;
          font-weight: 600;
          font-size: 15px;
        }

        .data-table td {
          padding: 15px;
          border-bottom: 1px solid rgba(0, 0, 0, 0.1);
          font-size: 15px;
        }

        .data-table tbody tr:hover {
          background: rgba(102, 126, 234, 0.05);
        }

        .rank {
          font-weight: 700;
          color: #667eea;
        }

        .product-name {
          font-weight: 600;
        }

        .capitalize {
          text-transform: capitalize;
        }

        /* Loading & Empty States */
        .loading-state,
        .empty-state {
          text-align: center;
          padding: 60px 20px;
        }

        .spinner {
          width: 50px;
          height: 50px;
          border: 4px solid rgba(102, 126, 234, 0.2);
          border-top-color: #667eea;
          border-radius: 50%;
          animation: spin 1s linear infinite;
          margin: 0 auto 20px;
        }

        @keyframes spin {
          to {
            transform: rotate(360deg);
          }
        }

        .empty-icon {
          font-size: 80px;
          margin-bottom: 20px;
          opacity: 0.5;
        }

        .empty-state h3 {
          font-size: 22px;
          color: #333;
          margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
          .reports-sidebar {
            width: 70px;
          }

          .reports-content {
            margin-left: 70px;
            padding: 20px;
          }

          .report-selector {
            flex-direction: column;
          }

          .stats-grid {
            grid-template-columns: 1fr;
          }

          .reports-filters {
            flex-direction: column;
          }

          .filter-group {
            min-width: 100%;
          }

          .btn-refresh {
            width: 100%;
          }
        }
      `}</style>
    </div>
  );
};

export default Reports;
