const CartItem = ({ item, onUpdateQuantity, onRemove }) => {
  const subtotal = (item.unit_price * item.quantity).toFixed(2);

  return (
    <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition">
      <div className="flex-1">
        <h4 className="font-semibold text-gray-800">{item.product_name}</h4>
        <p className="text-sm text-gray-600">SKU: {item.product_sku}</p>
        <p className="text-sm text-gray-500">
          ৳{item.unit_price.toFixed(2)} × {item.quantity}
        </p>
        <p className="text-xs text-gray-400 mt-1">
          Stock: {item.available_stock} units
        </p>
      </div>

      <div className="flex items-center gap-4 ml-4">
        {/* Quantity Controls */}
        <div className="flex items-center gap-2">
          <button
            onClick={() => onUpdateQuantity(item.product_id, item.quantity - 1)}
            className="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
          >
            <svg
              className="w-4 h-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M20 12H4"
              />
            </svg>
          </button>

          <input
            type="number"
            min="1"
            max={item.available_stock}
            value={item.quantity}
            onChange={(e) =>
              onUpdateQuantity(item.product_id, parseInt(e.target.value) || 1)
            }
            className="w-16 text-center px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <button
            onClick={() => onUpdateQuantity(item.product_id, item.quantity + 1)}
            disabled={item.quantity >= item.available_stock}
            className="w-8 h-8 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            <svg
              className="w-4 h-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M12 4v16m8-8H4"
              />
            </svg>
          </button>
        </div>

        {/* Subtotal */}
        <div className="text-right min-w-[80px]">
          <p className="font-bold text-gray-900">৳{subtotal}</p>
        </div>

        {/* Remove Button */}
        <button
          onClick={() => onRemove(item.product_id)}
          className="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition"
          title="Remove item"
        >
          <svg
            className="w-5 h-5"
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
  );
};

export default CartItem;
