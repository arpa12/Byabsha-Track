import { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import "./Dashboard.css";
import "./POS.css";

const POS = () => {
  const { user, logout } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  // State management
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [barcodeInput, setBarcodeInput] = useState("");
  const [loading, setLoading] = useState(false);
  const [branches, setBranches] = useState([]);
  const [selectedBranch, setSelectedBranch] = useState("");
  const [paymentMethod, setPaymentMethod] = useState("cash");
  const [discountType, setDiscountType] = useState("percentage");
  const [discountValue, setDiscountValue] = useState(0);
  const [taxRate, setTaxRate] = useState(0);
  const [paidAmount, setPaidAmount] = useState(0);
  const [customerName, setCustomerName] = useState("");
  const [customerPhone, setCustomerPhone] = useState("");
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [invoice, setInvoice] = useState(null);
  const [showInvoice, setShowInvoice] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(window.innerWidth >= 768);

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const menuItems = [
    { icon: "📊", label: t("dashboard"), path: "/dashboard" },
    { icon: "🛒", label: t("pos"), path: "/pos", active: true },
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

  // Fetch branches on mount
  useEffect(() => {
    fetchBranches();
  }, []);

  // Fetch user's branch
  const fetchBranches = async () => {
    try {
      // Get user data to check if they have access to all branches
      const user = JSON.parse(localStorage.getItem("user"));

      // For owner role, try to fetch all branches
      if (user?.role === "owner") {
        const response = await api.get("/branches");
        const branchesData = response.data.branches || response.data;
        setBranches(Array.isArray(branchesData) ? branchesData : []);
        if (branchesData.length > 0) {
          setSelectedBranch(branchesData[0].id);
        }
      } else {
        // For non-owner users, use their assigned branch
        if (user?.branch_id) {
          setSelectedBranch(user.branch_id);
          // Optionally, you could fetch just this branch data if needed
          // const response = await api.get(`/branches/${user.branch_id}`);
          // setBranches([response.data]);
        }
      }
    } catch (err) {
      console.error("[POS] Error fetching branches:", err);
      // Fallback: use user's branch from localStorage
      const user = JSON.parse(localStorage.getItem("user"));
      if (user?.branch_id) {
        setSelectedBranch(user.branch_id);
      }
    }
  };

  // Search products
  const searchProducts = useCallback(async (query) => {
    if (!query.trim()) {
      setProducts([]);
      return;
    }

    try {
      setLoading(true);
      const response = await api.get("/products", {
        params: {
          search: query,
          per_page: 10,
        },
      });
      setProducts(response.data.data || response.data);
    } catch (err) {
      console.error("Error searching products:", err);
      setError("Failed to search products");
    } finally {
      setLoading(false);
    }
  }, []);

  // Debounced search
  useEffect(() => {
    const timer = setTimeout(() => {
      searchProducts(searchQuery);
    }, 300);

    return () => clearTimeout(timer);
  }, [searchQuery, searchProducts]);

  // Handle barcode scan
  const handleBarcodeSearch = async () => {
    if (!barcodeInput.trim()) {
      setError("Please enter a barcode");
      return;
    }

    try {
      setLoading(true);
      setError("");
      const response = await api.get("/products", {
        params: {
          barcode: barcodeInput.trim(),
        },
      });

      const productData = response.data.data || response.data;
      if (productData && productData.length > 0) {
        await addToCart(productData[0]);
        setBarcodeInput("");
      } else {
        setError("Product not found with this barcode");
      }
    } catch (err) {
      console.error("Error searching by barcode:", err);
      setError("Failed to search product by barcode");
    } finally {
      setLoading(false);
    }
  };

  // Handle barcode input keypress (Enter key)
  const handleBarcodeKeyPress = (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      handleBarcodeSearch();
    }
  };

  // Add product to cart
  const addToCart = async (product) => {
    try {
      // Check stock availability
      const stockResponse = await api.get(
        `/products/${product.id}/stock?branch_id=${selectedBranch}`,
      );
      const availableStock = stockResponse.data.quantity || 0;

      const existingItem = cart.find((item) => item.product_id === product.id);
      const currentQuantity = existingItem ? existingItem.quantity : 0;

      if (currentQuantity >= availableStock) {
        setError(`Insufficient stock. Only ${availableStock} units available.`);
        return;
      }

      if (existingItem) {
        updateQuantity(product.id, existingItem.quantity + 1);
      } else {
        const newItem = {
          product_id: product.id,
          product_name: product.name,
          product_sku: product.sku,
          unit_price: parseFloat(product.selling_price),
          cost_price: parseFloat(product.cost_price),
          quantity: 1,
          available_stock: availableStock,
        };
        setCart([...cart, newItem]);
      }

      setSearchQuery("");
      setProducts([]);
      setError("");
    } catch (err) {
      console.error("Error adding to cart:", err);
      setError("Failed to add product to cart");
    }
  };

  // Update cart item quantity
  const updateQuantity = (productId, newQuantity) => {
    if (newQuantity < 1) {
      removeFromCart(productId);
      return;
    }

    setCart(
      cart.map((item) => {
        if (item.product_id === productId) {
          if (newQuantity > item.available_stock) {
            setError(
              `Only ${item.available_stock} units available for ${item.product_name}`,
            );
            return item;
          }
          return { ...item, quantity: newQuantity };
        }
        return item;
      }),
    );
    setError("");
  };

  // Remove item from cart
  const removeFromCart = (productId) => {
    setCart(cart.filter((item) => item.product_id !== productId));
  };

  // Calculate totals
  const calculateTotals = () => {
    const subtotal = cart.reduce(
      (sum, item) => sum + item.unit_price * item.quantity,
      0,
    );

    let discount = 0;
    if (discountType === "percentage") {
      discount = (subtotal * parseFloat(discountValue || 0)) / 100;
    } else {
      discount = parseFloat(discountValue || 0);
    }

    const afterDiscount = subtotal - discount;
    const tax = (afterDiscount * parseFloat(taxRate || 0)) / 100;
    const total = afterDiscount + tax;

    return {
      subtotal: subtotal.toFixed(2),
      discount: discount.toFixed(2),
      tax: tax.toFixed(2),
      total: total.toFixed(2),
    };
  };

  // Complete sale
  const completeSale = async () => {
    if (!selectedBranch) {
      setError("Please select a branch");
      return;
    }

    if (cart.length === 0) {
      setError("Cart is empty");
      return;
    }

    const totals = calculateTotals();
    const paid = parseFloat(paidAmount || 0);

    if (paid < 0) {
      setError("Paid amount cannot be negative");
      return;
    }

    try {
      setLoading(true);
      setError("");

      const saleData = {
        branch_id: selectedBranch,
        customer_name: customerName || null,
        customer_phone: customerPhone || null,
        payment_method: paymentMethod,
        discount_type: discountValue > 0 ? discountType : null,
        discount_value: parseFloat(discountValue || 0),
        tax_rate: parseFloat(taxRate || 0),
        paid_amount: paid,
        cart_items: cart.map((item) => ({
          product_id: item.product_id,
          quantity: item.quantity,
          unit_price: item.unit_price,
          cost_price: item.cost_price,
        })),
      };

      const response = await api.post("/sales/pos", saleData);

      setSuccess("Sale completed successfully!");
      setInvoice(response.data.invoice);
      setShowInvoice(true);

      // Reset form
      setTimeout(() => {
        resetForm();
      }, 1000);
    } catch (err) {
      console.error("Error completing sale:", err);
      if (err.response?.data?.errors) {
        // Stock validation errors
        const stockErrors = err.response.data.errors;
        if (Array.isArray(stockErrors)) {
          const errorMsg = stockErrors
            .map(
              (e) =>
                `${e.product_name}: Need ${e.requested_quantity}, only ${e.available_quantity} available`,
            )
            .join("; ");
          setError(errorMsg);
        } else {
          setError(err.response.data.message || "Failed to complete sale");
        }
      } else {
        setError(err.response?.data?.message || "Failed to complete sale");
      }
    } finally {
      setLoading(false);
    }
  };

  // Reset form after successful sale
  const resetForm = () => {
    setCart([]);
    setDiscountValue(0);
    setTaxRate(0);
    setPaidAmount(0);
    setCustomerName("");
    setCustomerPhone("");
    setPaymentMethod("cash");
    setDiscountType("percentage");
    setError("");
    setSuccess("");
  };

  // Clear cart
  const clearCart = () => {
    if (window.confirm("Are you sure you want to clear the cart?")) {
      setCart([]);
      setError("");
    }
  };

  const totals = calculateTotals();

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
            <h1 className="dashboard-page-title">{t("pos")}</h1>
          </div>

          <div className="dashboard-header-right">
            <select
              value={selectedBranch}
              onChange={(e) => setSelectedBranch(e.target.value)}
              className="pos-branch-select"
            >
              {branches.map((branch) => (
                <option key={branch.id} value={branch.id}>
                  {branch.name}
                </option>
              ))}
            </select>

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
          {/* Alert Messages */}
          {error && (
            <div className="pos-alert pos-alert-error">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <span>{error}</span>
              <button onClick={() => setError("")}>×</button>
            </div>
          )}
          {success && (
            <div className="pos-alert pos-alert-success">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <span>{success}</span>
              <button onClick={() => setSuccess("")}>×</button>
            </div>
          )}

          {/* Main Grid */}
          <div className="pos-grid">
            {/* Left Column - Products & Cart */}
            <div className="pos-left-column">
              {/* Product Search */}
              <div className="pos-search-section">
                <div className="pos-search-wrapper">
                  <svg
                    className="pos-search-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2}
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                  </svg>
                  <input
                    type="text"
                    placeholder={
                      language === "en"
                        ? "Search products by name or SKU..."
                        : "নাম বা SKU দিয়ে পণ্য খুঁজুন..."
                    }
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="pos-search-input"
                  />
                </div>

                {/* Barcode Input */}
                <div className="pos-barcode-wrapper">
                  <svg
                    className="pos-barcode-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2}
                      d="M12 4v16m-8-8h16M6 4v16m12-16v16"
                    />
                  </svg>
                  <input
                    type="text"
                    placeholder={
                      language === "en"
                        ? "Scan or enter barcode..."
                        : "বারকোড স্ক্যান বা প্রবেশ করুন..."
                    }
                    value={barcodeInput}
                    onChange={(e) => setBarcodeInput(e.target.value)}
                    onKeyPress={handleBarcodeKeyPress}
                    className="pos-barcode-input"
                  />
                  <button
                    onClick={handleBarcodeSearch}
                    className="pos-barcode-btn"
                    disabled={!barcodeInput.trim() || loading}
                  >
                    {language === "en" ? "Add" : "যোগ করুন"}
                  </button>
                </div>

                {/* Product Results */}
                {searchQuery && products.length > 0 && (
                  <div className="pos-product-results">
                    {products.map((product) => (
                      <div
                        key={product.id}
                        className="pos-product-item"
                        onClick={() => addToCart(product)}
                      >
                        <div className="pos-product-info">
                          <h4>{product.name}</h4>
                          <p>{product.sku}</p>
                        </div>
                        <div className="pos-product-price">
                          ৳{parseFloat(product.selling_price).toFixed(2)}
                        </div>
                      </div>
                    ))}
                  </div>
                )}
                {searchQuery && !loading && products.length === 0 && (
                  <div className="pos-no-results">
                    {language === "en"
                      ? "No products found"
                      : "কোনো পণ্য পাওয়া যায়নি"}
                  </div>
                )}
              </div>

              {/* Cart */}
              <div className="pos-cart-section">
                <div className="pos-cart-header">
                  <h3>
                    {language === "en" ? "Cart" : "কার্ট"} ({cart.length})
                  </h3>
                  {cart.length > 0 && (
                    <button onClick={clearCart} className="pos-clear-btn">
                      {language === "en" ? "Clear" : "খালি করুন"}
                    </button>
                  )}
                </div>

                <div className="pos-cart-items">
                  {cart.length === 0 ? (
                    <div className="pos-empty-cart">
                      <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                      </svg>
                      <p>
                        {language === "en" ? "Cart is empty" : "কার্ট খালি"}
                      </p>
                    </div>
                  ) : (
                    cart.map((item) => (
                      <div key={item.product_id} className="pos-cart-item">
                        <div className="pos-cart-item-info">
                          <h4>{item.product_name}</h4>
                          <p>
                            ৳{item.unit_price.toFixed(2)} × {item.quantity}
                          </p>
                        </div>
                        <div className="pos-cart-item-actions">
                          <div className="pos-quantity-controls">
                            <button
                              onClick={() =>
                                updateQuantity(
                                  item.product_id,
                                  item.quantity - 1,
                                )
                              }
                            >
                              −
                            </button>
                            <span>{item.quantity}</span>
                            <button
                              onClick={() =>
                                updateQuantity(
                                  item.product_id,
                                  item.quantity + 1,
                                )
                              }
                            >
                              +
                            </button>
                          </div>
                          <div className="pos-cart-item-total">
                            ৳{(item.unit_price * item.quantity).toFixed(2)}
                          </div>
                          <button
                            onClick={() => removeFromCart(item.product_id)}
                            className="pos-remove-btn"
                          >
                            <svg
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                              />
                            </svg>
                          </button>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              </div>
            </div>

            {/* Right Column - Payment */}
            <div className="pos-right-column">
              <div className="pos-payment-section">
                <h3>
                  {language === "en" ? "Payment Details" : "পেমেন্ট বিবরণ"}
                </h3>

                {/* Customer Info */}
                <div className="pos-form-group">
                  <label>
                    {language === "en"
                      ? "Customer Name (Optional)"
                      : "গ্রাহকের নাম (ঐচ্ছিক)"}
                  </label>
                  <input
                    type="text"
                    value={customerName}
                    onChange={(e) => setCustomerName(e.target.value)}
                    placeholder={
                      language === "en"
                        ? "Enter customer name"
                        : "গ্রাহকের নাম লিখুন"
                    }
                  />
                </div>

                <div className="pos-form-group">
                  <label>
                    {language === "en" ? "Phone (Optional)" : "ফোন (ঐচ্ছিক)"}
                  </label>
                  <input
                    type="tel"
                    value={customerPhone}
                    onChange={(e) => setCustomerPhone(e.target.value)}
                    placeholder={
                      language === "en"
                        ? "Enter phone number"
                        : "ফোন নম্বর লিখুন"
                    }
                  />
                </div>

                {/* Payment Method */}
                <div className="pos-form-group">
                  <label>
                    {language === "en" ? "Payment Method" : "পেমেন্ট পদ্ধতি"}
                  </label>
                  <div className="pos-payment-methods">
                    <button
                      className={`pos-payment-method ${paymentMethod === "cash" ? "active" : ""}`}
                      onClick={() => setPaymentMethod("cash")}
                    >
                      💵 {t("cash")}
                    </button>
                    <button
                      className={`pos-payment-method ${paymentMethod === "card" ? "active" : ""}`}
                      onClick={() => setPaymentMethod("card")}
                    >
                      💳 {t("card")}
                    </button>
                    <button
                      className={`pos-payment-method ${paymentMethod === "bkash" ? "active" : ""}`}
                      onClick={() => setPaymentMethod("bkash")}
                    >
                      📱 bKash
                    </button>
                  </div>
                </div>

                {/* Discount */}
                <div className="pos-form-row">
                  <div className="pos-form-group">
                    <label>{t("discount")}</label>
                    <select
                      value={discountType}
                      onChange={(e) => setDiscountType(e.target.value)}
                    >
                      <option value="percentage">%</option>
                      <option value="fixed">৳</option>
                    </select>
                  </div>
                  <div className="pos-form-group">
                    <label>&nbsp;</label>
                    <input
                      type="number"
                      min="0"
                      value={discountValue}
                      onChange={(e) => setDiscountValue(e.target.value)}
                      placeholder="0"
                    />
                  </div>
                </div>

                {/* Tax */}
                <div className="pos-form-group">
                  <label>{t("tax")} (%)</label>
                  <input
                    type="number"
                    min="0"
                    max="100"
                    value={taxRate}
                    onChange={(e) => setTaxRate(e.target.value)}
                    placeholder="0"
                  />
                </div>

                {/* Totals */}
                <div className="pos-totals">
                  <div className="pos-total-row">
                    <span>{language === "en" ? "Subtotal" : "উপমোট"}</span>
                    <span>৳{totals.subtotal}</span>
                  </div>
                  {parseFloat(totals.discount) > 0 && (
                    <div className="pos-total-row pos-discount">
                      <span>{t("discount")}</span>
                      <span>-৳{totals.discount}</span>
                    </div>
                  )}
                  {parseFloat(totals.tax) > 0 && (
                    <div className="pos-total-row">
                      <span>{t("tax")}</span>
                      <span>+৳{totals.tax}</span>
                    </div>
                  )}
                  <div className="pos-total-row pos-grand-total">
                    <span>{t("grandTotal")}</span>
                    <span>৳{totals.total}</span>
                  </div>
                </div>

                {/* Paid Amount */}
                <div className="pos-form-group">
                  <label>{t("paid")}</label>
                  <input
                    type="number"
                    min="0"
                    value={paidAmount}
                    onChange={(e) => setPaidAmount(e.target.value)}
                    placeholder="0"
                    className="pos-paid-input"
                  />
                </div>

                {/* Change */}
                {parseFloat(paidAmount) > parseFloat(totals.total) && (
                  <div className="pos-change">
                    <span>{language === "en" ? "Change" : "ফেরত"}</span>
                    <span>
                      ৳
                      {(
                        parseFloat(paidAmount) - parseFloat(totals.total)
                      ).toFixed(2)}
                    </span>
                  </div>
                )}

                {/* Complete Sale Button */}
                <button
                  onClick={completeSale}
                  disabled={cart.length === 0 || loading}
                  className="pos-complete-btn"
                >
                  {loading ? (
                    <>
                      <div className="pos-spinner"></div>{" "}
                      {language === "en" ? "Processing..." : "প্রক্রিয়াকরণ..."}
                    </>
                  ) : (
                    <>
                      {language === "en"
                        ? "Complete Sale"
                        : "বিক্রয় সম্পন্ন করুন"}
                    </>
                  )}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Invoice Modal */}
      {showInvoice && invoice && (
        <div
          className="pos-modal-overlay"
          onClick={() => {
            setShowInvoice(false);
            setInvoice(null);
          }}
        >
          <div className="pos-modal" onClick={(e) => e.stopPropagation()}>
            <div className="pos-modal-header">
              <h2>{language === "en" ? "Invoice" : "চালান"}</h2>
              <button
                onClick={() => {
                  setShowInvoice(false);
                  setInvoice(null);
                }}
              >
                ×
              </button>
            </div>
            <div className="pos-modal-content">
              <div className="pos-invoice">
                <h3>{invoice.business?.name || t("appName")}</h3>
                <p className="pos-invoice-branch">
                  {invoice.business?.branch || ""}
                </p>
                <p className="pos-invoice-address">
                  {invoice.business?.address || ""}
                </p>
                <p className="pos-invoice-contact">
                  {invoice.business?.phone || ""} |{" "}
                  {invoice.business?.email || ""}
                </p>

                <div className="pos-invoice-separator"></div>

                <div className="pos-invoice-details">
                  <p>
                    <strong>
                      {language === "en" ? "Invoice #" : "চালান #"}:
                    </strong>{" "}
                    {invoice.invoice_no}
                  </p>
                  <p>
                    <strong>{language === "en" ? "Date" : "তারিখ"}:</strong>{" "}
                    {invoice.date} {invoice.time}
                  </p>
                  <p>
                    <strong>
                      {language === "en" ? "Customer" : "গ্রাহক"}:
                    </strong>{" "}
                    {invoice.customer?.name || "Walk-in Customer"}
                  </p>
                  {invoice.customer?.phone && (
                    <p>
                      <strong>{language === "en" ? "Phone" : "ফোন"}:</strong>{" "}
                      {invoice.customer.phone}
                    </p>
                  )}
                  <p>
                    <strong>
                      {language === "en" ? "Salesman" : "বিক্রয়কর্মী"}:
                    </strong>{" "}
                    {invoice.salesman?.name}
                  </p>
                </div>

                <div className="pos-invoice-separator"></div>

                {/* Invoice Items */}
                <div className="pos-invoice-items">
                  <table>
                    <thead>
                      <tr>
                        <th>{language === "en" ? "Item" : "পণ্য"}</th>
                        <th>{language === "en" ? "Qty" : "পরিমাণ"}</th>
                        <th>{language === "en" ? "Price" : "মূল্য"}</th>
                        <th>{language === "en" ? "Total" : "মোট"}</th>
                      </tr>
                    </thead>
                    <tbody>
                      {invoice.items?.map((item, index) => (
                        <tr key={index}>
                          <td>
                            <div>{item.product_name}</div>
                            <small>{item.product_sku}</small>
                          </td>
                          <td>{item.quantity}</td>
                          <td>৳{item.unit_price.toFixed(2)}</td>
                          <td>৳{item.subtotal.toFixed(2)}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>

                <div className="pos-invoice-separator"></div>

                {/* Invoice Totals */}
                <div className="pos-invoice-totals">
                  <p>
                    <strong>{language === "en" ? "Subtotal" : "উপমোট"}:</strong>
                    <span>৳{invoice.payment?.subtotal?.toFixed(2)}</span>
                  </p>
                  {invoice.payment?.discount?.amount > 0 && (
                    <p>
                      <strong>
                        {language === "en" ? "Discount" : "ছাড়"}:
                      </strong>
                      <span>
                        -৳{invoice.payment.discount.amount.toFixed(2)}
                      </span>
                    </p>
                  )}
                  {invoice.payment?.tax?.amount > 0 && (
                    <p>
                      <strong>{language === "en" ? "Tax" : "কর"}:</strong>
                      <span>+৳{invoice.payment.tax.amount.toFixed(2)}</span>
                    </p>
                  )}
                  <p className="pos-invoice-grand-total">
                    <strong>
                      {language === "en" ? "Grand Total" : "সর্বমোট"}:
                    </strong>
                    <span>৳{invoice.payment?.grand_total?.toFixed(2)}</span>
                  </p>
                  <p>
                    <strong>{language === "en" ? "Paid" : "পরিশোধিত"}:</strong>
                    <span>৳{invoice.payment?.paid_amount?.toFixed(2)}</span>
                  </p>
                  {invoice.payment?.change_amount > 0 && (
                    <p className="pos-invoice-change">
                      <strong>{language === "en" ? "Change" : "ফেরত"}:</strong>
                      <span>৳{invoice.payment.change_amount.toFixed(2)}</span>
                    </p>
                  )}
                  {invoice.payment?.due_amount > 0 && (
                    <p className="pos-invoice-due">
                      <strong>{language === "en" ? "Due" : "বাকি"}:</strong>
                      <span>৳{invoice.payment.due_amount.toFixed(2)}</span>
                    </p>
                  )}
                  <p>
                    <strong>
                      {language === "en" ? "Payment Method" : "পেমেন্ট পদ্ধতি"}:
                    </strong>
                    <span className="pos-invoice-payment-method">
                      {invoice.payment?.payment_method?.toUpperCase()}
                    </span>
                  </p>
                </div>

                <div className="pos-invoice-separator"></div>

                <div className="pos-invoice-footer">
                  <p>
                    {language === "en"
                      ? "Thank you for your business!"
                      : "আপনার ব্যবসার জন্য ধন্যবাদ!"}
                  </p>
                </div>
              </div>
            </div>
            <div className="pos-modal-footer">
              <button onClick={() => window.print()} className="pos-print-btn">
                {language === "en" ? "Print" : "প্রিন্ট"}
              </button>
              <button
                onClick={() => {
                  setShowInvoice(false);
                  setInvoice(null);
                }}
                className="pos-close-btn"
              >
                {language === "en" ? "Close" : "বন্ধ করুন"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default POS;
