import { useState, useEffect, useCallback } from "react";
import api from "../services/api";
import ProductSearch from "../components/POS/ProductSearch";
import Cart from "../components/POS/Cart";
import PaymentSection from "../components/POS/PaymentSection";
import InvoiceModal from "../components/POS/InvoiceModal";

const POS = () => {
  // State management
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState([]);
  const [searchQuery, setSearchQuery] = useState("");
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

  // Fetch branches on mount
  useEffect(() => {
    fetchBranches();
  }, []);

  // Fetch user's branch
  const fetchBranches = async () => {
    try {
      const response = await api.get("/branches");
      setBranches(response.data);
      if (response.data.length > 0) {
        setSelectedBranch(response.data[0].id);
      }
    } catch (err) {
      console.error("Error fetching branches:", err);
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
      setInvoice(response.data.data);
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
    <div className="min-h-screen bg-gray-50 p-4">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="bg-white rounded-lg shadow-md p-4 mb-4">
          <div className="flex justify-between items-center">
            <h1 className="text-2xl font-bold text-gray-800">Point of Sale</h1>
            <div className="flex items-center gap-4">
              <select
                value={selectedBranch}
                onChange={(e) => setSelectedBranch(e.target.value)}
                className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                {branches.map((branch) => (
                  <option key={branch.id} value={branch.id}>
                    {branch.name}
                  </option>
                ))}
              </select>
            </div>
          </div>
        </div>

        {/* Alert Messages */}
        {error && (
          <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {error}
          </div>
        )}
        {success && (
          <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {success}
          </div>
        )}

        {/* Main Content */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
          {/* Left Column - Product Search & Cart */}
          <div className="lg:col-span-2 space-y-4">
            {/* Product Search */}
            <ProductSearch
              searchQuery={searchQuery}
              setSearchQuery={setSearchQuery}
              products={products}
              loading={loading}
              onAddToCart={addToCart}
            />

            {/* Cart */}
            <Cart
              cart={cart}
              onUpdateQuantity={updateQuantity}
              onRemoveItem={removeFromCart}
              onClearCart={clearCart}
              totals={totals}
            />
          </div>

          {/* Right Column - Payment Section */}
          <div className="lg:col-span-1">
            <PaymentSection
              totals={totals}
              paymentMethod={paymentMethod}
              setPaymentMethod={setPaymentMethod}
              discountType={discountType}
              setDiscountType={setDiscountType}
              discountValue={discountValue}
              setDiscountValue={setDiscountValue}
              taxRate={taxRate}
              setTaxRate={setTaxRate}
              paidAmount={paidAmount}
              setPaidAmount={setPaidAmount}
              customerName={customerName}
              setCustomerName={setCustomerName}
              customerPhone={customerPhone}
              setCustomerPhone={setCustomerPhone}
              onCompleteSale={completeSale}
              loading={loading}
              cartEmpty={cart.length === 0}
            />
          </div>
        </div>
      </div>

      {/* Invoice Modal */}
      {showInvoice && invoice && (
        <InvoiceModal
          invoice={invoice}
          onClose={() => {
            setShowInvoice(false);
            setInvoice(null);
          }}
        />
      )}
    </div>
  );
};

export default POS;
