import React, { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import api from "../services/api";

const Categories = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  // State Management
  const [categories, setCategories] = useState([]);
  const [parentCategories, setParentCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);

  // Filter State
  const [filters, setFilters] = useState({
    search: "",
    is_active: "",
  });

  // Modal State
  const [showAddModal, setShowAddModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [selectedCategory, setSelectedCategory] = useState(null);

  // Form State
  const [formData, setFormData] = useState({
    name: "",
    description: "",
    parent_id: "",
    is_active: true,
  });

  // Fetch categories
  const fetchCategories = useCallback(async () => {
    try {
      setLoading(true);
      const params = {};
      if (filters.is_active !== "") {
        params.is_active = filters.is_active;
      }

      const response = await api.get("/categories", { params });
      const categoriesData =
        response.data.categories || response.data.data || response.data;
      setCategories(Array.isArray(categoriesData) ? categoriesData : []);
    } catch (err) {
      console.error("[Categories] Error fetching categories:", err);
      setCategories([]);
    } finally {
      setLoading(false);
    }
  }, [filters.is_active]);

  // Fetch parent categories for dropdown
  const fetchParentCategories = useCallback(async () => {
    try {
      const response = await api.get("/categories", {
        params: { parent_only: true, is_active: 1 },
      });
      const parentData =
        response.data.categories || response.data.data || response.data;
      setParentCategories(Array.isArray(parentData) ? parentData : []);
    } catch (err) {
      console.error("[Categories] Error fetching parent categories:", err);
      setParentCategories([]);
    }
  }, []);

  // Initial data fetch
  useEffect(() => {
    fetchCategories();
    fetchParentCategories();
  }, [fetchCategories, fetchParentCategories]);

  // Handle form input change
  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? checked : value,
    }));
  };

  // Handle Add Category
  const handleAddCategory = async (e) => {
    e.preventDefault();
    try {
      const payload = {
        name: formData.name,
        description: formData.description || null,
        parent_id: formData.parent_id || null,
        is_active: formData.is_active,
      };

      await api.post("/categories", payload);
      setShowAddModal(false);
      setFormData({
        name: "",
        description: "",
        parent_id: "",
        is_active: true,
      });
      fetchCategories();
      fetchParentCategories();
    } catch (err) {
      console.error("[Categories] Error adding category:", err);
      alert(err.response?.data?.message || "Failed to add category");
    }
  };

  // Handle Edit Category
  const handleEditCategory = async (e) => {
    e.preventDefault();
    if (!selectedCategory) return;

    try {
      const payload = {
        name: formData.name,
        description: formData.description || null,
        parent_id: formData.parent_id || null,
        is_active: formData.is_active,
      };

      await api.put(`/categories/${selectedCategory.id}`, payload);
      setShowEditModal(false);
      setSelectedCategory(null);
      setFormData({
        name: "",
        description: "",
        parent_id: "",
        is_active: true,
      });
      fetchCategories();
      fetchParentCategories();
    } catch (err) {
      console.error("[Categories] Error updating category:", err);
      alert(err.response?.data?.message || "Failed to update category");
    }
  };

  // Handle Delete Category
  const handleDeleteCategory = async () => {
    if (!selectedCategory) return;

    try {
      await api.delete(`/categories/${selectedCategory.id}`);
      setShowDeleteModal(false);
      setSelectedCategory(null);
      fetchCategories();
      fetchParentCategories();
    } catch (err) {
      console.error("[Categories] Error deleting category:", err);
      alert(err.response?.data?.message || "Failed to delete category");
    }
  };

  // Open Edit Modal
  const openEditModal = (category) => {
    setSelectedCategory(category);
    setFormData({
      name: category.name,
      description: category.description || "",
      parent_id: category.parent_id || "",
      is_active: category.is_active,
    });
    setShowEditModal(true);
  };

  // Open Delete Modal
  const openDeleteModal = (category) => {
    setSelectedCategory(category);
    setShowDeleteModal(true);
  };

  // Filter categories based on search
  const filteredCategories = categories.filter((cat) =>
    cat.name.toLowerCase().includes(filters.search.toLowerCase()),
  );

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const menuItems = [
    { icon: "📊", label: t("dashboard"), path: "/dashboard" },
    { icon: "🛒", label: t("pos"), path: "/pos" },
    { icon: "💰", label: t("sales"), path: "/sales" },
    { icon: "📦", label: t("products"), path: "/products" },
    { icon: "🏷️", label: t("categories"), path: "/categories", active: true },
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
            <h1 className="dashboard-page-title">{t("categories")}</h1>
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

        <div className="dashboard-content">
          <div className="categories-header">
            <div className="header-left">
              <button className="btn-add" onClick={() => setShowAddModal(true)}>
                + {language === "en" ? "Add Category" : "বিভাগ যোগ করুন"}
              </button>
            </div>
          </div>

          {/* Filters */}
          <div className="categories-filters">
            <div className="filter-group">
              <input
                type="text"
                placeholder={t.search + "..."}
                value={filters.search}
                onChange={(e) =>
                  setFilters((prev) => ({ ...prev, search: e.target.value }))
                }
                className="filter-input"
              />
            </div>

            <div className="filter-group">
              <select
                value={filters.is_active}
                onChange={(e) =>
                  setFilters((prev) => ({ ...prev, is_active: e.target.value }))
                }
                className="filter-select"
              >
                <option value="">
                  {t.language === "en" ? "All Status" : "সকল অবস্থা"}
                </option>
                <option value="1">
                  {t.language === "en" ? "Active" : "সক্রিয়"}
                </option>
                <option value="0">
                  {t.language === "en" ? "Inactive" : "নিষ্ক্রিয়"}
                </option>
              </select>
            </div>
          </div>

          {/* Categories Grid */}
          {loading ? (
            <div className="loading-state">
              <div className="spinner"></div>
              <p>{t.language === "en" ? "Loading..." : "লোড হচ্ছে..."}</p>
            </div>
          ) : filteredCategories.length === 0 ? (
            <div className="empty-state">
              <div className="empty-icon">📂</div>
              <h3>
                {t.language === "en"
                  ? "No Categories Found"
                  : "কোন বিভাগ পাওয়া যায়নি"}
              </h3>
              <p>
                {t.language === "en"
                  ? "Start by adding your first category"
                  : "প্রথম বিভাগ যোগ করে শুরু করুন"}
              </p>
            </div>
          ) : (
            <div className="categories-grid">
              {filteredCategories.map((category) => (
                <div key={category.id} className="category-card">
                  <div className="category-card-header">
                    <h3>{category.name}</h3>
                    <span
                      className={`status-badge ${
                        category.is_active ? "active" : "inactive"
                      }`}
                    >
                      {category.is_active
                        ? t.language === "en"
                          ? "Active"
                          : "সক্রিয়"
                        : t.language === "en"
                          ? "Inactive"
                          : "নিষ্ক্রিয়"}
                    </span>
                  </div>

                  {category.description && (
                    <p className="category-description">
                      {category.description}
                    </p>
                  )}

                  {category.parent && (
                    <div className="category-parent">
                      <span className="parent-label">
                        {t.language === "en" ? "Parent:" : "মূল বিভাগ:"}
                      </span>
                      <span className="parent-name">
                        {category.parent.name}
                      </span>
                    </div>
                  )}

                  {category.children && category.children.length > 0 && (
                    <div className="category-children">
                      <span className="children-label">
                        {t.language === "en" ? "Subcategories:" : "উপ-বিভাগ:"}
                      </span>
                      <span className="children-count">
                        {category.children.length}
                      </span>
                    </div>
                  )}

                  <div className="category-actions">
                    <button
                      className="btn-edit"
                      onClick={() => openEditModal(category)}
                    >
                      {t.edit}
                    </button>
                    <button
                      className="btn-delete"
                      onClick={() => openDeleteModal(category)}
                    >
                      {t.delete}
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </main>

      {/* Add Category Modal */}
      {showAddModal && (
        <div className="modal-overlay" onClick={() => setShowAddModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2>{t.language === "en" ? "Add Category" : "বিভাগ যোগ করুন"}</h2>
              <button
                className="modal-close"
                onClick={() => setShowAddModal(false)}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleAddCategory} className="modal-form">
              <div className="form-group">
                <label>
                  {t.language === "en" ? "Category Name" : "বিভাগের নাম"} *
                </label>
                <input
                  type="text"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
                  required
                  className="form-input"
                />
              </div>

              <div className="form-group">
                <label>{t.language === "en" ? "Description" : "বিবরণ"}</label>
                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleInputChange}
                  rows="3"
                  className="form-textarea"
                />
              </div>

              <div className="form-group">
                <label>
                  {t.language === "en" ? "Parent Category" : "মূল বিভাগ"}
                </label>
                <select
                  name="parent_id"
                  value={formData.parent_id}
                  onChange={handleInputChange}
                  className="form-select"
                >
                  <option value="">
                    {t.language === "en"
                      ? "None (Top Level)"
                      : "কোনটি নয় (শীর্ষ স্তর)"}
                  </option>
                  {parentCategories.map((cat) => (
                    <option key={cat.id} value={cat.id}>
                      {cat.name}
                    </option>
                  ))}
                </select>
              </div>

              <div className="form-group checkbox-group">
                <label>
                  <input
                    type="checkbox"
                    name="is_active"
                    checked={formData.is_active}
                    onChange={handleInputChange}
                  />
                  <span>
                    {t.language === "en" ? "Active Status" : "সক্রিয় অবস্থা"}
                  </span>
                </label>
              </div>

              <div className="modal-actions">
                <button
                  type="button"
                  className="btn-cancel"
                  onClick={() => setShowAddModal(false)}
                >
                  {t.cancel}
                </button>
                <button type="submit" className="btn-submit">
                  {t.language === "en" ? "Add Category" : "বিভাগ যোগ করুন"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Edit Category Modal */}
      {showEditModal && (
        <div className="modal-overlay" onClick={() => setShowEditModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2>
                {t.language === "en" ? "Edit Category" : "বিভাগ সম্পাদনা করুন"}
              </h2>
              <button
                className="modal-close"
                onClick={() => setShowEditModal(false)}
              >
                ×
              </button>
            </div>

            <form onSubmit={handleEditCategory} className="modal-form">
              <div className="form-group">
                <label>
                  {t.language === "en" ? "Category Name" : "বিভাগের নাম"} *
                </label>
                <input
                  type="text"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
                  required
                  className="form-input"
                />
              </div>

              <div className="form-group">
                <label>{t.language === "en" ? "Description" : "বিবরণ"}</label>
                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleInputChange}
                  rows="3"
                  className="form-textarea"
                />
              </div>

              <div className="form-group">
                <label>
                  {t.language === "en" ? "Parent Category" : "মূল বিভাগ"}
                </label>
                <select
                  name="parent_id"
                  value={formData.parent_id}
                  onChange={handleInputChange}
                  className="form-select"
                >
                  <option value="">
                    {t.language === "en"
                      ? "None (Top Level)"
                      : "কোনটি নয় (শীর্ষ স্তর)"}
                  </option>
                  {parentCategories
                    .filter((cat) => cat.id !== selectedCategory?.id)
                    .map((cat) => (
                      <option key={cat.id} value={cat.id}>
                        {cat.name}
                      </option>
                    ))}
                </select>
              </div>

              <div className="form-group checkbox-group">
                <label>
                  <input
                    type="checkbox"
                    name="is_active"
                    checked={formData.is_active}
                    onChange={handleInputChange}
                  />
                  <span>
                    {t.language === "en" ? "Active Status" : "সক্রিয় অবস্থা"}
                  </span>
                </label>
              </div>

              <div className="modal-actions">
                <button
                  type="button"
                  className="btn-cancel"
                  onClick={() => setShowEditModal(false)}
                >
                  {t.cancel}
                </button>
                <button type="submit" className="btn-submit">
                  {t.language === "en" ? "Update Category" : "বিভাগ আপডেট করুন"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      {showDeleteModal && (
        <div
          className="modal-overlay"
          onClick={() => setShowDeleteModal(false)}
        >
          <div
            className="modal-content modal-small"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="modal-header">
              <h2>
                {t.language === "en" ? "Delete Category" : "বিভাগ মুছে ফেলুন"}
              </h2>
              <button
                className="modal-close"
                onClick={() => setShowDeleteModal(false)}
              >
                ×
              </button>
            </div>

            <div className="modal-body">
              <p>
                {t.language === "en"
                  ? `Are you sure you want to delete "${selectedCategory?.name}"? This action cannot be undone.`
                  : `আপনি কি নিশ্চিত "${selectedCategory?.name}" মুছে ফেলতে চান? এই কাজটি পূর্বাবস্থায় আনা যাবে না।`}
              </p>
            </div>

            <div className="modal-actions">
              <button
                type="button"
                className="btn-cancel"
                onClick={() => setShowDeleteModal(false)}
              >
                {t.cancel}
              </button>
              <button
                type="button"
                className="btn-delete"
                onClick={handleDeleteCategory}
              >
                {t.delete}
              </button>
            </div>
          </div>
        </div>
      )}

      <style jsx>{`
        .categories-page {
          display: flex;
          min-height: 100vh;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Sidebar */
        .categories-sidebar {
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

        .categories-sidebar.closed {
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

        .categories-sidebar.closed .sidebar-header h2 {
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

        .categories-sidebar.closed .nav-text {
          display: none;
        }

        /* Main Content */
        .categories-content {
          flex: 1;
          margin-left: 260px;
          padding: 30px;
          transition: margin-left 0.3s ease;
        }

        .categories-sidebar.closed ~ .categories-content {
          margin-left: 70px;
        }

        /* Header */
        .categories-header {
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          margin-bottom: 30px;
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

        .btn-add {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          border: none;
          padding: 14px 28px;
          border-radius: 10px;
          font-size: 16px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-add:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Filters */
        .categories-filters {
          display: flex;
          gap: 15px;
          margin-bottom: 25px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          padding: 20px;
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
          flex: 1;
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

        /* Categories Grid */
        .categories-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
          gap: 20px;
        }

        .category-card {
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 25px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
          transition: all 0.3s ease;
        }

        .category-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .category-card-header {
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          margin-bottom: 15px;
        }

        .category-card-header h3 {
          font-size: 20px;
          font-weight: 600;
          color: #333;
          margin: 0;
          flex: 1;
        }

        .status-badge {
          padding: 6px 14px;
          border-radius: 20px;
          font-size: 13px;
          font-weight: 600;
          white-space: nowrap;
          margin-left: 10px;
        }

        .status-badge.active {
          background: rgba(34, 197, 94, 0.15);
          color: #16a34a;
        }

        .status-badge.inactive {
          background: rgba(239, 68, 68, 0.15);
          color: #dc2626;
        }

        .category-description {
          font-size: 14px;
          color: #666;
          margin: 0 0 15px 0;
          line-height: 1.6;
        }

        .category-parent,
        .category-children {
          display: flex;
          align-items: center;
          gap: 8px;
          font-size: 14px;
          margin-bottom: 10px;
        }

        .parent-label,
        .children-label {
          color: #666;
          font-weight: 500;
        }

        .parent-name {
          color: #667eea;
          font-weight: 600;
        }

        .children-count {
          background: rgba(102, 126, 234, 0.15);
          color: #667eea;
          padding: 2px 8px;
          border-radius: 10px;
          font-weight: 600;
        }

        .category-actions {
          display: flex;
          gap: 10px;
          margin-top: 20px;
          padding-top: 20px;
          border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-edit,
        .btn-delete {
          flex: 1;
          padding: 10px;
          border: none;
          border-radius: 8px;
          font-size: 14px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .btn-edit {
          background: rgba(59, 130, 246, 0.15);
          color: #2563eb;
        }

        .btn-edit:hover {
          background: rgba(59, 130, 246, 0.25);
          transform: translateY(-2px);
        }

        .btn-delete {
          background: rgba(239, 68, 68, 0.15);
          color: #dc2626;
        }

        .btn-delete:hover {
          background: rgba(239, 68, 68, 0.25);
          transform: translateY(-2px);
        }

        /* Loading & Empty States */
        .loading-state,
        .empty-state {
          text-align: center;
          padding: 60px 20px;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
          margin: 0 0 10px 0;
        }

        .empty-state p {
          font-size: 16px;
          color: #666;
          margin: 0;
        }

        /* Modal */
        .modal-overlay {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0, 0, 0, 0.6);
          backdrop-filter: blur(5px);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 1000;
          padding: 20px;
        }

        .modal-content {
          background: white;
          border-radius: 20px;
          max-width: 600px;
          width: 100%;
          max-height: 90vh;
          overflow-y: auto;
          box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-content.modal-small {
          max-width: 450px;
        }

        .modal-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 25px 30px;
          border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .modal-header h2 {
          font-size: 24px;
          font-weight: 700;
          color: #333;
          margin: 0;
        }

        .modal-close {
          background: none;
          border: none;
          font-size: 32px;
          color: #999;
          cursor: pointer;
          line-height: 1;
          padding: 0;
          width: 32px;
          height: 32px;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: all 0.3s ease;
        }

        .modal-close:hover {
          color: #333;
          transform: rotate(90deg);
        }

        .modal-body {
          padding: 25px 30px;
        }

        .modal-body p {
          font-size: 16px;
          color: #666;
          line-height: 1.6;
          margin: 0;
        }

        .modal-form {
          padding: 25px 30px;
        }

        .form-group {
          margin-bottom: 20px;
        }

        .form-group label {
          display: block;
          font-size: 15px;
          font-weight: 600;
          color: #333;
          margin-bottom: 8px;
        }

        .form-input,
        .form-textarea,
        .form-select {
          width: 100%;
          padding: 12px 16px;
          border: 2px solid rgba(0, 0, 0, 0.1);
          border-radius: 10px;
          font-size: 15px;
          transition: all 0.3s ease;
          font-family: inherit;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
          outline: none;
          border-color: #667eea;
          box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
          resize: vertical;
          min-height: 80px;
        }

        .checkbox-group label {
          display: flex;
          align-items: center;
          gap: 10px;
          cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
          width: 20px;
          height: 20px;
          cursor: pointer;
        }

        .modal-actions {
          display: flex;
          gap: 12px;
          padding: 20px 30px;
          border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-cancel,
        .btn-submit {
          flex: 1;
          padding: 14px;
          border: none;
          border-radius: 10px;
          font-size: 16px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .btn-cancel {
          background: rgba(0, 0, 0, 0.05);
          color: #666;
        }

        .btn-cancel:hover {
          background: rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Responsive */
        @media (max-width: 768px) {
          .categories-sidebar {
            width: 70px;
          }

          .categories-content {
            margin-left: 70px;
            padding: 20px;
          }

          .categories-grid {
            grid-template-columns: 1fr;
          }

          .categories-header {
            flex-direction: column;
            gap: 20px;
          }

          .btn-add {
            width: 100%;
          }

          .categories-filters {
            flex-direction: column;
          }
        }
      `}</style>
    </div>
  );
};

export default Categories;
