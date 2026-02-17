const PaymentSection = ({
  totals,
  paymentMethod,
  setPaymentMethod,
  discountType,
  setDiscountType,
  discountValue,
  setDiscountValue,
  taxRate,
  setTaxRate,
  paidAmount,
  setPaidAmount,
  customerName,
  setCustomerName,
  customerPhone,
  setCustomerPhone,
  onCompleteSale,
  loading,
  cartEmpty,
}) => {
  const total = parseFloat(totals.total);
  const paid = parseFloat(paidAmount || 0);
  const change = paid > total ? (paid - total).toFixed(2) : "0.00";
  const due = paid < total ? (total - paid).toFixed(2) : "0.00";

  const paymentMethods = [
    { value: "cash", label: "Cash", icon: "💵" },
    { value: "card", label: "Card", icon: "💳" },
    { value: "bkash", label: "bKash", icon: "📱" },
    { value: "mobile_banking", label: "Mobile Banking", icon: "📲" },
    { value: "bank_transfer", label: "Bank Transfer", icon: "🏦" },
  ];

  return (
    <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
      <h2 className="text-xl font-semibold mb-4 text-gray-800">
        Payment Details
      </h2>

      {/* Customer Info */}
      <div className="space-y-3 mb-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">
            Customer Name (Optional)
          </label>
          <input
            type="text"
            value={customerName}
            onChange={(e) => setCustomerName(e.target.value)}
            placeholder="Walk-in Customer"
            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">
            Phone Number (Optional)
          </label>
          <input
            type="tel"
            value={customerPhone}
            onChange={(e) => setCustomerPhone(e.target.value)}
            placeholder="01XXXXXXXXX"
            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

      {/* Discount Section */}
      <div className="mb-4 p-3 bg-gray-50 rounded-lg">
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Discount
        </label>
        <div className="flex gap-2 mb-2">
          <select
            value={discountType}
            onChange={(e) => setDiscountType(e.target.value)}
            className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="percentage">Percentage (%)</option>
            <option value="fixed">Fixed Amount (৳)</option>
          </select>
          <input
            type="number"
            min="0"
            step="0.01"
            value={discountValue}
            onChange={(e) => setDiscountValue(e.target.value)}
            placeholder="0"
            className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

      {/* Tax Section */}
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-1">
          Tax Rate (%)
        </label>
        <input
          type="number"
          min="0"
          step="0.01"
          value={taxRate}
          onChange={(e) => setTaxRate(e.target.value)}
          placeholder="0"
          className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      {/* Payment Method */}
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Payment Method
        </label>
        <div className="grid grid-cols-2 gap-2">
          {paymentMethods.map((method) => (
            <button
              key={method.value}
              onClick={() => setPaymentMethod(method.value)}
              className={`px-3 py-2 border-2 rounded-lg text-sm font-medium transition ${
                paymentMethod === method.value
                  ? "border-blue-500 bg-blue-50 text-blue-700"
                  : "border-gray-300 text-gray-700 hover:border-gray-400"
              }`}
            >
              <span className="mr-1">{method.icon}</span>
              {method.label}
            </button>
          ))}
        </div>
      </div>

      {/* Paid Amount */}
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-1">
          Paid Amount (৳)
        </label>
        <input
          type="number"
          min="0"
          step="0.01"
          value={paidAmount}
          onChange={(e) => setPaidAmount(e.target.value)}
          placeholder="0.00"
          className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
        />
        <div className="mt-2 flex justify-between text-sm">
          <button
            onClick={() => setPaidAmount(totals.total)}
            className="text-blue-600 hover:underline"
          >
            Exact Amount
          </button>
          {paymentMethod === "cash" && (
            <div className="flex gap-2">
              {["100", "500", "1000"].map((amount) => (
                <button
                  key={amount}
                  onClick={() =>
                    setPaidAmount(
                      (
                        parseFloat(paidAmount || 0) + parseFloat(amount)
                      ).toString(),
                    )
                  }
                  className="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                >
                  +৳{amount}
                </button>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Change/Due */}
      {paid > 0 && (
        <div className="mb-4 p-3 bg-gray-50 rounded-lg">
          {paid >= total ? (
            <div className="flex justify-between items-center">
              <span className="text-sm font-medium text-gray-700">Change:</span>
              <span className="text-lg font-bold text-green-600">
                ৳{change}
              </span>
            </div>
          ) : (
            <div className="flex justify-between items-center">
              <span className="text-sm font-medium text-gray-700">Due:</span>
              <span className="text-lg font-bold text-red-600">৳{due}</span>
            </div>
          )}
        </div>
      )}

      {/* Total Summary */}
      <div className="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <div className="flex justify-between items-center">
          <span className="text-lg font-semibold text-gray-700">
            Total Amount:
          </span>
          <span className="text-2xl font-bold text-blue-600">
            ৳{totals.total}
          </span>
        </div>
      </div>

      {/* Complete Sale Button */}
      <button
        onClick={onCompleteSale}
        disabled={loading || cartEmpty}
        className={`w-full py-4 rounded-lg font-bold text-lg transition ${
          loading || cartEmpty
            ? "bg-gray-300 text-gray-500 cursor-not-allowed"
            : "bg-green-500 text-white hover:bg-green-600 shadow-lg hover:shadow-xl"
        }`}
      >
        {loading ? (
          <span className="flex items-center justify-center">
            <svg
              className="animate-spin h-5 w-5 mr-3"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                className="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                strokeWidth="4"
              />
              <path
                className="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              />
            </svg>
            Processing...
          </span>
        ) : (
          "Complete Sale"
        )}
      </button>

      {cartEmpty && (
        <p className="text-sm text-gray-500 text-center mt-2">
          Add items to cart to complete sale
        </p>
      )}
    </div>
  );
};

export default PaymentSection;
