import React, { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import api from "../services/api";
import "./Sales.css";

const Sales = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  // UI States
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);
  const [loading, setLoading] = useState(false);
  const [userMenuOpen, setUserMenuOpen] = useState(false);

  // Data States
  const [sales, setSales] = useState([]);
  const [pagination, setPagination] = useState({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
  });

  // Filter States
  const [filters, setFilters] = useState({
    search: "",
    branch_id: "",
    payment_status: "",
    start_date: "",
    end_date: "",
  });

  // Modal States
  const [selectedSale, setSelectedSale] = useState(null);
  const [showInvoiceModal, setShowInvoiceModal] = useState(false);

  // Fetch sales data
  const fetchSales = useCallback(
    async (page = 1) => {
      try {
        setLoading(true);
        const params = {
          page,
          per_page: pagination.per_page,
          ...filters,
        };

        // Remove empty filters
        Object.keys(params).forEach(
          (key) =>
            (params[key] === "" || params[key] == null) && delete params[key],
        );

        const response = await api.get("/sales", { params });
        console.log("[Sales] Sales fetched:", response.data);

        setSales(response.data.data || []);
        setPagination({
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total,
        });
      } catch (err) {
        console.error("[Sales] Error fetching sales:", err);
      } finally {
        setLoading(false);
      }
    },
    [filters, pagination.per_page],
  );

  // Fetch sales on mount and when filters change
  useEffect(() => {
    fetchSales();
  }, [
    filters.branch_id,
    filters.payment_status,
    filters.start_date,
    filters.end_date,
  ]);

  // Handle window resize
  useEffect(() => {
    const handleResize = () => {
      setSidebarOpen(window.innerWidth >= 768);
    };
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const handleViewInvoice = async (sale) => {
    try {
      const response = await api.get(`/sales/${sale.id}`);
      setSelectedSale(response.data.data || response.data);
      setShowInvoiceModal(true);
    } catch (err) {
      console.error("Error fetching sale details:", err);
    }
  };

  const handlePrintInvoice = () => {
    window.print();
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "BDT",
      minimumFractionDigits: 2,
    })
      .format(amount)
      .replace("BDT", "৳");
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
    });
  };

  const getPaymentStatusBadge = (status) => {
    const badges = {
      paid: {
        class: "status-paid",
        text: language === "en" ? "Paid" : "পরিশোধিত",
      },
      partial: {
        class: "status-partial",
        text: language === "en" ? "Partial" : "আংশিক",
      },
      unpaid: {
        class: "status-unpaid",
        text: language === "en" ? "Unpaid" : "অপরিশোধিত",
      },
    };
    return badges[status] || badges.unpaid;
  };

  const menuItems = [
    { icon: "📊", label: t("dashboard"), path: "/dashboard" },
    { icon: "🛒", label: t("pos"), path: "/pos" },
    { icon: "💰", label: t("sales"), path: "/sales", active: true },
    { icon: "📦", label: t("products"), path: "/products" },
    { icon: "📁", label: t("categories"), path: "/categories" },
    { icon: "📈", label: t("reports"), path: "/reports" },
  ];

  return (
    <div className="sales-container">
      {/* Sidebar */}
      <aside className={`sales-sidebar ${sidebarOpen ? "open" : "closed"}`}>
        <div className="sidebar-header">
          <h2 className="sidebar-title">{sidebarOpen && t("appName")}</h2>
        </div>

        <nav className="sidebar-nav">
          {menuItems.map((item, index) => (
            <button
              key={index}
              className={`nav-item ${item.active ? "active" : ""}`}
              onClick={() => navigate(item.path)}
              title={item.label}
            >
              <span className="nav-icon">{item.icon}</span>
              {sidebarOpen && <span className="nav-label">{item.label}</span>}
            </button>
          ))}
        </nav>
      </aside>

      {/* Main Content */}
      <div className="sales-main">
        {/* Header */}
        <header className="sales-header">
          <div className="header-left">
            <button
              className="toggle-sidebar-btn"
              onClick={() => setSidebarOpen(!sidebarOpen)}
            >
              ☰
            </button>
            <h1 className="page-title">{t("sales")}</h1>
          </div>

          <div className="header-right">
            <button className="lang-toggle" onClick={toggleLanguage}>
              {language === "en" ? "বাংলা" : "English"}
            </button>

            <div className="user-menu">
              <button
                className="user-menu-btn"
                onClick={() => setUserMenuOpen(!userMenuOpen)}
              >
                <span className="user-avatar">
                  {user?.name?.charAt(0).toUpperCase()}
                </span>
                <span className="user-name">{user?.name}</span>
              </button>

              {userMenuOpen && (
                <div className="user-dropdown">
                  <div className="user-info">
                    <div className="user-info-name">{user?.name}</div>
                    <div className="user-info-email">{user?.email}</div>
                    <div className="user-info-role">{user?.role}</div>
                  </div>
                  <button className="dropdown-item" onClick={handleLogout}>
                    🚪 {t("logout")}
                  </button>
                </div>
              )}
            </div>
          </div>
        </header>

        {/* Filters */}
        <div className="sales-filters">
          <div className="filter-group">
            <input
              type="date"
              className="filter-input"
              placeholder={t("dateFrom")}
              value={filters.start_date}
              onChange={(e) =>
                setFilters({ ...filters, start_date: e.target.value })
              }
            />
            <input
              type="date"
              className="filter-input"
              placeholder={t("dateTo")}
              value={filters.end_date}
              onChange={(e) =>
                setFilters({ ...filters, end_date: e.target.value })
              }
            />
            <select
              className="filter-select"
              value={filters.payment_status}
              onChange={(e) =>
                setFilters({ ...filters, payment_status: e.target.value })
              }
            >
              <option value="">
                {language === "en" ? "All Status" : "সব স্ট্যাটাস"}
              </option>
              <option value="paid">
                {language === "en" ? "Paid" : "পরিশোধিত"}
              </option>
              <option value="partial">
                {language === "en" ? "Partial" : "আংশিক"}
              </option>
              <option value="unpaid">
                {language === "en" ? "Unpaid" : "অপরিশোধিত"}
              </option>
            </select>
            <button
              className="btn-refresh"
              onClick={() => fetchSales(pagination.current_page)}
            >
              🔄 {language === "en" ? "Refresh" : "রিফ্রেশ"}
            </button>
          </div>
        </div>

        {/* Sales Table */}
        <div className="sales-content">
          {loading ? (
            <div className="loading-spinner">
              <div className="spinner"></div>
              <p>{language === "en" ? "Loading..." : "লোড হচ্ছে..."}</p>
            </div>
          ) : sales.length === 0 ? (
            <div className="empty-state">
              <div className="empty-icon">📋</div>
              <h3>
                {language === "en"
                  ? "No Sales Found"
                  : "কোনো বিক্রয় পাওয়া যায়নি"}
              </h3>
              <p>
                {language === "en"
                  ? "Start selling from the POS page"
                  : "POS পেজ থেকে বিক্রয় শুরু করুন"}
              </p>
            </div>
          ) : (
            <>
              <div className="table-container">
                <table className="sales-table">
                  <thead>
                    <tr>
                      <th>{language === "en" ? "Invoice" : "ইনভয়েস"}</th>
                      <th>{t("saleDate")}</th>
                      <th>{t("customer")}</th>
                      <th>{language === "en" ? "Branch" : "শাখা"}</th>
                      <th>{language === "en" ? "Subtotal" : "উপমোট"}</th>
                      <th>{t("discount")}</th>
                      <th>{t("tax")}</th>
                      <th>{t("total")}</th>
                      <th>{language === "en" ? "Status" : "স্ট্যাটাস"}</th>
                      <th>{language === "en" ? "Actions" : "অ্যাকশন"}</th>
                    </tr>
                  </thead>
                  <tbody>
                    {sales.map((sale) => {
                      const statusBadge = getPaymentStatusBadge(
                        sale.payment_status,
                      );
                      return (
                        <tr key={sale.id}>
                          <td className="invoice-no">{sale.invoice_no}</td>
                          <td>{formatDate(sale.sale_date)}</td>
                          <td>
                            <div className="customer-info">
                              <div className="customer-name">
                                {sale.customer_name ||
                                  (language === "en" ? "Walk-in" : "ওয়াক-ইন")}
                              </div>
                              {sale.customer_phone && (
                                <div className="customer-phone">
                                  {sale.customer_phone}
                                </div>
                              )}
                            </div>
                          </td>
                          <td>{sale.branch?.name || "-"}</td>
                          <td>{formatCurrency(sale.subtotal)}</td>
                          <td>{formatCurrency(sale.discount)}</td>
                          <td>{formatCurrency(sale.tax)}</td>
                          <td className="total-amount">
                            {formatCurrency(sale.total)}
                          </td>
                          <td>
                            <span
                              className={`status-badge ${statusBadge.class}`}
                            >
                              {statusBadge.text}
                            </span>
                          </td>
                          <td>
                            <button
                              className="btn-action btn-view"
                              onClick={() => handleViewInvoice(sale)}
                              title={
                                language === "en"
                                  ? "View Invoice"
                                  : "ইনভয়েস দেখুন"
                              }
                            >
                              👁️
                            </button>
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>

              {/* Pagination */}
              {pagination.last_page > 1 && (
                <div className="pagination">
                  <button
                    className="pagination-btn"
                    disabled={pagination.current_page === 1}
                    onClick={() => fetchSales(pagination.current_page - 1)}
                  >
                    ‹ {language === "en" ? "Previous" : "আগের"}
                  </button>
                  <span className="pagination-info">
                    {language === "en" ? "Page" : "পেজ"}{" "}
                    {pagination.current_page} {language === "en" ? "of" : "এর"}{" "}
                    {pagination.last_page}
                  </span>
                  <button
                    className="pagination-btn"
                    disabled={pagination.current_page === pagination.last_page}
                    onClick={() => fetchSales(pagination.current_page + 1)}
                  >
                    {language === "en" ? "Next" : "পরবর্তী"} ›
                  </button>
                </div>
              )}
            </>
          )}
        </div>
      </div>

      {/* Invoice Modal */}
      {showInvoiceModal && selectedSale && (
        <div
          className="modal-overlay"
          onClick={() => setShowInvoiceModal(false)}
        >
          <div
            className="modal-content invoice-modal"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="modal-header">
              <h2>
                {language === "en" ? "Invoice Details" : "ইনভয়েস বিস্তারিত"}
              </h2>
              <button
                className="modal-close"
                onClick={() => setShowInvoiceModal(false)}
              >
                ✕
              </button>
            </div>

            <div className="invoice-content">
              {/* Invoice Header */}
              <div className="invoice-header">
                <div className="invoice-company">
                  <h1>{t("appName")}</h1>
                  <p>{selectedSale.branch?.name}</p>
                </div>
                <div className="invoice-number">
                  <h3>{language === "en" ? "INVOICE" : "ইনভয়েস"}</h3>
                  <p className="invoice-no-large">{selectedSale.invoice_no}</p>
                  <p className="invoice-date">
                    {formatDate(selectedSale.sale_date)}
                  </p>
                </div>
              </div>

              {/* Customer Info */}
              <div className="invoice-customer">
                <h4>
                  {language === "en" ? "Customer Information" : "ক্রেতার তথ্য"}
                </h4>
                <p>
                  <strong>{language === "en" ? "Name:" : "নাম:"}</strong>{" "}
                  {selectedSale.customer_name ||
                    (language === "en"
                      ? "Walk-in Customer"
                      : "ওয়াক-ইন কাস্টমার")}
                </p>
                {selectedSale.customer_phone && (
                  <p>
                    <strong>{language === "en" ? "Phone:" : "ফোন:"}</strong>{" "}
                    {selectedSale.customer_phone}
                  </p>
                )}
              </div>

              {/* Items Table */}
              <table className="invoice-items-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>{language === "en" ? "Product" : "পণ্য"}</th>
                    <th>{language === "en" ? "Quantity" : "পরিমাণ"}</th>
                    <th>{language === "en" ? "Unit Price" : "একক মূল্য"}</th>
                    <th>{language === "en" ? "Total" : "মোট"}</th>
                  </tr>
                </thead>
                <tbody>
                  {selectedSale.items?.map((item, index) => (
                    <tr key={item.id}>
                      <td>{index + 1}</td>
                      <td>{item.product?.name || item.product_id}</td>
                      <td>
                        {item.quantity} {item.product?.unit || ""}
                      </td>
                      <td>{formatCurrency(item.unit_price)}</td>
                      <td>{formatCurrency(item.total_price)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>

              {/* Invoice Totals */}
              <div className="invoice-totals">
                <div className="totals-row">
                  <span>{language === "en" ? "Subtotal:" : "উপমোট:"}</span>
                  <span>{formatCurrency(selectedSale.subtotal)}</span>
                </div>
                <div className="totals-row">
                  <span>{language === "en" ? "Discount:" : "ছাড়:"}</span>
                  <span>- {formatCurrency(selectedSale.discount)}</span>
                </div>
                <div className="totals-row">
                  <span>{language === "en" ? "Tax:" : "কর:"}</span>
                  <span>+ {formatCurrency(selectedSale.tax)}</span>
                </div>
                <div className="totals-row grand-total">
                  <span>{language === "en" ? "Grand Total:" : "সর্বমোট:"}</span>
                  <span>{formatCurrency(selectedSale.total)}</span>
                </div>
                <div className="totals-row">
                  <span>{language === "en" ? "Paid:" : "পরিশোধিত:"}</span>
                  <span>{formatCurrency(selectedSale.paid_amount)}</span>
                </div>
                {selectedSale.due_amount > 0 && (
                  <div className="totals-row due-amount">
                    <span>{language === "en" ? "Due:" : "বাকি:"}</span>
                    <span>{formatCurrency(selectedSale.due_amount)}</span>
                  </div>
                )}
              </div>

              {/* Payment Info */}
              <div className="invoice-payment">
                <p>
                  <strong>
                    {language === "en" ? "Payment Method:" : "পেমেন্ট পদ্ধতি:"}
                  </strong>{" "}
                  {selectedSale.payment_method}
                </p>
                <p>
                  <strong>
                    {language === "en" ? "Status:" : "স্ট্যাটাস:"}
                  </strong>{" "}
                  <span
                    className={`status-badge ${getPaymentStatusBadge(selectedSale.payment_status).class}`}
                  >
                    {getPaymentStatusBadge(selectedSale.payment_status).text}
                  </span>
                </p>
              </div>

              {selectedSale.note && (
                <div className="invoice-note">
                  <strong>{language === "en" ? "Note:" : "নোট:"}</strong>{" "}
                  {selectedSale.note}
                </div>
              )}
            </div>

            <div className="modal-footer">
              <button
                className="btn-cancel"
                onClick={() => setShowInvoiceModal(false)}
              >
                {language === "en" ? "Close" : "বন্ধ করুন"}
              </button>
              <button className="btn-print" onClick={handlePrintInvoice}>
                🖨️ {language === "en" ? "Print Invoice" : "প্রিন্ট করুন"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Sales;
