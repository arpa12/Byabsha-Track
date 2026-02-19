import React, { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import api from "../services/api";
import "./Dashboard.css";
import "./Products.css";

const Products = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  // UI States
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);
  const [loading, setLoading] = useState(false);
  const [userMenuOpen, setUserMenuOpen] = useState(false);

  // Data States
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [pagination, setPagination] = useState({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
  });

  // Filter States
  const [filters, setFilters] = useState({
    search: "",
    category_id: "",
    is_active: "",
  });

  // Modal States
  const [showAddModal, setShowAddModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);

  // Form States
  const [formData, setFormData] = useState({
    name: "",
    sku: "",
    barcode: "",
    category_id: "",
    description: "",
    unit: "",
    purchase_price: "",
    selling_price: "",
  });

  const [formErrors, setFormErrors] = useState({});

  // Fetch products
  const fetchProducts = useCallback(
    async (page = 1) => {
      try {
        setLoading(true);
        const params = {
          page,
          per_page: pagination.per_page,
          ...filters,
        };

        Object.keys(params).forEach(
          (key) =>
            (params[key] === "" || params[key] == null) && delete params[key],
        );

        const response = await api.get("/products", { params });
        console.log("[Products] Products fetched:", response.data);

        setProducts(response.data.data || []);
        setPagination({
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total,
        });
      } catch (err) {
        console.error("[Products] Error fetching products:", err);
      } finally {
        setLoading(false);
      }
    },
    [filters, pagination.per_page],
  );

  // Fetch categories
  const fetchCategories = useCallback(async () => {
    try {
      const response = await api.get("/categories");
      const categoriesData =
        response.data.categories || response.data.data || response.data;
      setCategories(Array.isArray(categoriesData) ? categoriesData : []);
    } catch (err) {
      console.error("[Products] Error fetching categories:", err);
    }
  }, []);

  // Fetch products and categories on mount
  useEffect(() => {
    fetchProducts();
    fetchCategories();
  }, [filters.category_id, filters.is_active, fetchProducts, fetchCategories]);

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

  const handleAddProduct = () => {
    setFormData({
      name: "",
      sku: "",
      barcode: "",
      category_id: "",
      description: "",
      unit: "pcs",
      purchase_price: "",
      selling_price: "",
    });
    setFormErrors({});
    setShowAddModal(true);
  };

  const handleEditProduct = (product) => {
    setSelectedProduct(product);
    setFormData({
      name: product.name,
      sku: product.sku,
      barcode: product.barcode || "",
      category_id: product.category_id,
      description: product.description || "",
      unit: product.unit,
      purchase_price: product.purchase_price,
      selling_price: product.selling_price,
    });
    setFormErrors({});
    setShowEditModal(true);
  };

  const handleDeleteProduct = async (product) => {
    if (
      !window.confirm(
        language === "en"
          ? `Are you sure you want to delete "${product.name}"?`
          : `আপনি কি "${product.name}" মুছে ফেলতে চান?`,
      )
    ) {
      return;
    }

    try {
      await api.delete(`/products/${product.id}`);
      fetchProducts(pagination.current_page);
      alert(
        language === "en"
          ? "Product deleted successfully!"
          : "পণ্য সফলভাবে মুছে ফেলা হয়েছে!",
      );
    } catch (err) {
      console.error("[Products] Error deleting product:", err);
      alert(
        language === "en"
          ? "Failed to delete product"
          : "পণ্য মুছে ফেলতে ব্যর্থ",
      );
    }
  };

  const handleSubmitAdd = async (e) => {
    e.preventDefault();
    setFormErrors({});

    try {
      await api.post("/products", formData);
      setShowAddModal(false);
      fetchProducts(1);
      alert(
        language === "en"
          ? "Product added successfully!"
          : "পণ্য সফলভাবে যোগ করা হয়েছে!",
      );
    } catch (err) {
      console.error("[Products] Error adding product:", err);
      if (err.response?.data?.errors) {
        setFormErrors(err.response.data.errors);
      } else {
        alert(
          language === "en" ? "Failed to add product" : "পণ্য যোগ করতে ব্যর্থ",
        );
      }
    }
  };

  const handleSubmitEdit = async (e) => {
    e.preventDefault();
    setFormErrors({});

    try {
      await api.put(`/products/${selectedProduct.id}`, formData);
      setShowEditModal(false);
      fetchProducts(pagination.current_page);
      alert(
        language === "en"
          ? "Product updated successfully!"
          : "পণ্য সফলভাবে আপডেট করা হয়েছে!",
      );
    } catch (err) {
      console.error("[Products] Error updating product:", err);
      if (err.response?.data?.errors) {
        setFormErrors(err.response.data.errors);
      } else {
        alert(
          language === "en"
            ? "Failed to update product"
            : "পণ্য আপডেট করতে ব্যর্থ",
        );
      }
    }
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

  const menuItems = [
    { icon: "📊", label: t("dashboard"), path: "/dashboard" },
    { icon: "🛒", label: t("pos"), path: "/pos" },
    { icon: "💰", label: t("sales"), path: "/sales" },
    { icon: "📦", label: t("products"), path: "/products", active: true },
    { icon: "🏷️", label: t("categories"), path: "/categories" },
    { icon: "📥", label: t("purchases"), path: "/purchases" },
    { icon: "🏭", label: t("suppliers"), path: "/suppliers" },
    { icon: "💸", label: t("expenses"), path: "/expenses" },
    { icon: "🏢", label: t("branches"), path: "/branches" },
    { icon: "📈", label: t("reports"), path: "/reports" },
    { icon: "👥", label: t("users"), path: "/users" },
    { icon: "⚙️", label: t("settings"), path: "/settings" },
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
            <h1 className="dashboard-page-title">{t("products")}</h1>
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

        {/* Products Content */}
        <div className="dashboard-content">
          <div className="filter-group">
            <input
              type="text"
              className="filter-input search-input"
              placeholder={
                language === "en" ? "Search products..." : "পণ্য খুঁজুন..."
              }
              value={filters.search}
              onChange={(e) =>
                setFilters({ ...filters, search: e.target.value })
              }
            />
            <select
              className="filter-select"
              value={filters.category_id}
              onChange={(e) =>
                setFilters({ ...filters, category_id: e.target.value })
              }
            >
              <option value="">
                {language === "en" ? "All Categories" : "সব বিভাগ"}
              </option>
              {categories.map((cat) => (
                <option key={cat.id} value={cat.id}>
                  {cat.name}
                </option>
              ))}
            </select>
            <select
              className="filter-select"
              value={filters.is_active}
              onChange={(e) =>
                setFilters({ ...filters, is_active: e.target.value })
              }
            >
              <option value="">
                {language === "en" ? "All Status" : "সব স্ট্যাটাস"}
              </option>
              <option value="1">
                {language === "en" ? "Active" : "সক্রিয়"}
              </option>
              <option value="0">
                {language === "en" ? "Inactive" : "নিষ্ক্রিয়"}
              </option>
            </select>
            <button
              className="btn-refresh"
              onClick={() => fetchProducts(pagination.current_page)}
            >
              🔄 {language === "en" ? "Refresh" : "রিফ্রেশ"}
            </button>
            <button className="btn-add" onClick={handleAddProduct}>
              ➕ {t("addProduct")}
            </button>
          </div>
        </div>

        {/* Products Grid/Table */}
        <div className="products-content">
          {loading ? (
            <div className="loading-spinner">
              <div className="spinner"></div>
              <p>{language === "en" ? "Loading..." : "লোড হচ্ছে..."}</p>
            </div>
          ) : products.length === 0 ? (
            <div className="empty-state">
              <div className="empty-icon">📦</div>
              <h3>
                {language === "en"
                  ? "No Products Found"
                  : "কোনো পণ্য পাওয়া যায়নি"}
              </h3>
              <p>
                {language === "en"
                  ? "Start by adding your first product"
                  : "প্রথম পণ্য যোগ করে শুরু করুন"}
              </p>
              <button className="btn-add-empty" onClick={handleAddProduct}>
                ➕ {t("addProduct")}
              </button>
            </div>
          ) : (
            <>
              <div className="products-grid">
                {products.map((product) => (
                  <div key={product.id} className="product-card">
                    <div className="product-header">
                      <h3 className="product-name">{product.name}</h3>
                      <div className="product-actions">
                        <button
                          className="btn-icon btn-edit"
                          onClick={() => handleEditProduct(product)}
                          title={t("editProduct")}
                        >
                          ✏️
                        </button>
                        <button
                          className="btn-icon btn-delete"
                          onClick={() => handleDeleteProduct(product)}
                          title={t("deleteProduct")}
                        >
                          🗑️
                        </button>
                      </div>
                    </div>

                    <div className="product-body">
                      <div className="product-info">
                        <span className="info-label">{t("productCode")}:</span>
                        <span className="info-value">{product.sku}</span>
                      </div>
                      {product.barcode && (
                        <div className="product-info">
                          <span className="info-label">
                            {language === "en" ? "Barcode" : "বারকোড"}:
                          </span>
                          <span className="info-value">{product.barcode}</span>
                        </div>
                      )}
                      <div className="product-info">
                        <span className="info-label">{t("category")}:</span>
                        <span className="info-value">
                          {product.category?.name || "-"}
                        </span>
                      </div>
                      <div className="product-info">
                        <span className="info-label">{t("unit")}:</span>
                        <span className="info-value">{product.unit}</span>
                      </div>
                    </div>

                    <div className="product-footer">
                      <div className="price-info">
                        <div className="price-item">
                          <span className="price-label">
                            {language === "en" ? "Purchase" : "ক্রয়"}:
                          </span>
                          <span className="price-value">
                            {formatCurrency(product.purchase_price)}
                          </span>
                        </div>
                        <div className="price-item">
                          <span className="price-label">
                            {language === "en" ? "Selling" : "বিক্রয়"}:
                          </span>
                          <span className="price-value selling-price">
                            {formatCurrency(product.selling_price)}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {/* Pagination */}
              {pagination.last_page > 1 && (
                <div className="pagination">
                  <button
                    className="pagination-btn"
                    disabled={pagination.current_page === 1}
                    onClick={() => fetchProducts(pagination.current_page - 1)}
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
                    onClick={() => fetchProducts(pagination.current_page + 1)}
                  >
                    {language === "en" ? "Next" : "পরবর্তী"} ›
                  </button>
                </div>
              )}
            </>
          )}
        </div>
      </div>

      {/* Add Product Modal */}
      {showAddModal && (
        <div className="modal-overlay" onClick={() => setShowAddModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2>{t("addProduct")}</h2>
              <button
                className="modal-close"
                onClick={() => setShowAddModal(false)}
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleSubmitAdd} className="product-form">
              <div className="form-row">
                <div className="form-group">
                  <label>{t("productName")} *</label>
                  <input
                    type="text"
                    value={formData.name}
                    onChange={(e) =>
                      setFormData({ ...formData, name: e.target.value })
                    }
                    required
                  />
                  {formErrors.name && (
                    <span className="error">{formErrors.name[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>{t("productCode")} *</label>
                  <input
                    type="text"
                    value={formData.sku}
                    onChange={(e) =>
                      setFormData({ ...formData, sku: e.target.value })
                    }
                    required
                  />
                  {formErrors.sku && (
                    <span className="error">{formErrors.sku[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>{language === "en" ? "Barcode" : "বারকোড"}</label>
                  <input
                    type="text"
                    value={formData.barcode}
                    onChange={(e) =>
                      setFormData({ ...formData, barcode: e.target.value })
                    }
                  />
                  {formErrors.barcode && (
                    <span className="error">{formErrors.barcode[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>{t("category")} *</label>
                  <select
                    value={formData.category_id}
                    onChange={(e) =>
                      setFormData({ ...formData, category_id: e.target.value })
                    }
                    required
                  >
                    <option value="">
                      {language === "en"
                        ? "Select Category"
                        : "বিভাগ নির্বাচন করুন"}
                    </option>
                    {categories.map((cat) => (
                      <option key={cat.id} value={cat.id}>
                        {cat.name}
                      </option>
                    ))}
                  </select>
                  {formErrors.category_id && (
                    <span className="error">{formErrors.category_id[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>{t("unit")} *</label>
                  <select
                    value={formData.unit}
                    onChange={(e) =>
                      setFormData({ ...formData, unit: e.target.value })
                    }
                    required
                  >
                    <option value="pcs">
                      {language === "en" ? "Pieces" : "পিস"}
                    </option>
                    <option value="kg">
                      {language === "en" ? "Kilogram" : "কেজি"}
                    </option>
                    <option value="ltr">
                      {language === "en" ? "Liter" : "লিটার"}
                    </option>
                    <option value="box">
                      {language === "en" ? "Box" : "বক্স"}
                    </option>
                    <option value="dozen">
                      {language === "en" ? "Dozen" : "ডজন"}
                    </option>
                  </select>
                  {formErrors.unit && (
                    <span className="error">{formErrors.unit[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>
                    {language === "en" ? "Purchase Price" : "ক্রয় মূল্য"} *
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    value={formData.purchase_price}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        purchase_price: e.target.value,
                      })
                    }
                    required
                  />
                  {formErrors.purchase_price && (
                    <span className="error">
                      {formErrors.purchase_price[0]}
                    </span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>
                    {language === "en" ? "Selling Price" : "বিক্রয় মূল্য"} *
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    value={formData.selling_price}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        selling_price: e.target.value,
                      })
                    }
                    required
                  />
                  {formErrors.selling_price && (
                    <span className="error">{formErrors.selling_price[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-group full-width">
                <label>{language === "en" ? "Description" : "বিবরণ"}</label>
                <textarea
                  rows="3"
                  value={formData.description}
                  onChange={(e) =>
                    setFormData({ ...formData, description: e.target.value })
                  }
                />
                {formErrors.description && (
                  <span className="error">{formErrors.description[0]}</span>
                )}
              </div>

              <div className="modal-footer">
                <button
                  type="button"
                  className="btn-cancel"
                  onClick={() => setShowAddModal(false)}
                >
                  {language === "en" ? "Cancel" : "বাতিল"}
                </button>
                <button type="submit" className="btn-submit">
                  {language === "en" ? "Add Product" : "পণ্য যোগ করুন"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Edit Product Modal */}
      {showEditModal && selectedProduct && (
        <div className="modal-overlay" onClick={() => setShowEditModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2>{t("editProduct")}</h2>
              <button
                className="modal-close"
                onClick={() => setShowEditModal(false)}
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleSubmitEdit} className="product-form">
              <div className="form-row">
                <div className="form-group">
                  <label>{t("productName")} *</label>
                  <input
                    type="text"
                    value={formData.name}
                    onChange={(e) =>
                      setFormData({ ...formData, name: e.target.value })
                    }
                    required
                  />
                  {formErrors.name && (
                    <span className="error">{formErrors.name[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>{t("productCode")} *</label>
                  <input
                    type="text"
                    value={formData.sku}
                    onChange={(e) =>
                      setFormData({ ...formData, sku: e.target.value })
                    }
                    required
                  />
                  {formErrors.sku && (
                    <span className="error">{formErrors.sku[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>{language === "en" ? "Barcode" : "বারকোড"}</label>
                  <input
                    type="text"
                    value={formData.barcode}
                    onChange={(e) =>
                      setFormData({ ...formData, barcode: e.target.value })
                    }
                  />
                  {formErrors.barcode && (
                    <span className="error">{formErrors.barcode[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>{t("category")} *</label>
                  <select
                    value={formData.category_id}
                    onChange={(e) =>
                      setFormData({ ...formData, category_id: e.target.value })
                    }
                    required
                  >
                    <option value="">
                      {language === "en"
                        ? "Select Category"
                        : "বিভাগ নির্বাচন করুন"}
                    </option>
                    {categories.map((cat) => (
                      <option key={cat.id} value={cat.id}>
                        {cat.name}
                      </option>
                    ))}
                  </select>
                  {formErrors.category_id && (
                    <span className="error">{formErrors.category_id[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>{t("unit")} *</label>
                  <select
                    value={formData.unit}
                    onChange={(e) =>
                      setFormData({ ...formData, unit: e.target.value })
                    }
                    required
                  >
                    <option value="pcs">
                      {language === "en" ? "Pieces" : "পিস"}
                    </option>
                    <option value="kg">
                      {language === "en" ? "Kilogram" : "কেজি"}
                    </option>
                    <option value="ltr">
                      {language === "en" ? "Liter" : "লিটার"}
                    </option>
                    <option value="box">
                      {language === "en" ? "Box" : "বক্স"}
                    </option>
                    <option value="dozen">
                      {language === "en" ? "Dozen" : "ডজন"}
                    </option>
                  </select>
                  {formErrors.unit && (
                    <span className="error">{formErrors.unit[0]}</span>
                  )}
                </div>

                <div className="form-group">
                  <label>
                    {language === "en" ? "Purchase Price" : "ক্রয় মূল্য"} *
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    value={formData.purchase_price}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        purchase_price: e.target.value,
                      })
                    }
                    required
                  />
                  {formErrors.purchase_price && (
                    <span className="error">
                      {formErrors.purchase_price[0]}
                    </span>
                  )}
                </div>
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>
                    {language === "en" ? "Selling Price" : "বিক্রয় মূল্য"} *
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    value={formData.selling_price}
                    onChange={(e) =>
                      setFormData({
                        ...formData,
                        selling_price: e.target.value,
                      })
                    }
                    required
                  />
                  {formErrors.selling_price && (
                    <span className="error">{formErrors.selling_price[0]}</span>
                  )}
                </div>
              </div>

              <div className="form-group full-width">
                <label>{language === "en" ? "Description" : "বিবরণ"}</label>
                <textarea
                  rows="3"
                  value={formData.description}
                  onChange={(e) =>
                    setFormData({ ...formData, description: e.target.value })
                  }
                />
                {formErrors.description && (
                  <span className="error">{formErrors.description[0]}</span>
                )}
              </div>

              <div className="modal-footer">
                <button
                  type="button"
                  className="btn-cancel"
                  onClick={() => setShowEditModal(false)}
                >
                  {language === "en" ? "Cancel" : "বাতিল"}
                </button>
                <button type="submit" className="btn-submit">
                  {language === "en" ? "Update Product" : "পণ্য আপডেট করুন"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Products;
