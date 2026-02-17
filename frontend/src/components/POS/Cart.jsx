import CartItem from "./CartItem";

const Cart = ({
  cart,
  onUpdateQuantity,
  onRemoveItem,
  onClearCart,
  totals,
}) => {
  return (
    <div className="bg-white rounded-lg shadow-md p-6">
      <div className="flex justify-between items-center mb-4">
        <h2 className="text-xl font-semibold text-gray-800">
          Cart ({cart.length} items)
        </h2>
        {cart.length > 0 && (
          <button
            onClick={onClearCart}
            className="px-3 py-1 text-sm text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition"
          >
            Clear Cart
          </button>
        )}
      </div>

      {/* Cart Items */}
      {cart.length === 0 ? (
        <div className="text-center py-12 text-gray-400">
          <svg
            className="mx-auto h-16 w-16 mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={1.5}
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
            />
          </svg>
          <p className="text-lg">Your cart is empty</p>
          <p className="text-sm mt-1">Search and add products to get started</p>
        </div>
      ) : (
        <div className="space-y-3 max-h-96 overflow-y-auto">
          {cart.map((item) => (
            <CartItem
              key={item.product_id}
              item={item}
              onUpdateQuantity={onUpdateQuantity}
              onRemove={onRemoveItem}
            />
          ))}
        </div>
      )}

      {/* Cart Summary */}
      {cart.length > 0 && (
        <div className="mt-6 pt-4 border-t border-gray-200">
          <div className="space-y-2">
            <div className="flex justify-between text-gray-700">
              <span>Subtotal:</span>
              <span className="font-semibold">৳{totals.subtotal}</span>
            </div>
            {parseFloat(totals.discount) > 0 && (
              <div className="flex justify-between text-red-600">
                <span>Discount:</span>
                <span className="font-semibold">-৳{totals.discount}</span>
              </div>
            )}
            {parseFloat(totals.tax) > 0 && (
              <div className="flex justify-between text-gray-700">
                <span>Tax:</span>
                <span className="font-semibold">৳{totals.tax}</span>
              </div>
            )}
            <div className="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t border-gray-200">
              <span>Total:</span>
              <span className="text-green-600">৳{totals.total}</span>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Cart;
