const ProductSearch = ({
  searchQuery,
  setSearchQuery,
  products,
  loading,
  onAddToCart,
}) => {
  return (
    <div className="bg-white rounded-lg shadow-md p-6">
      <h2 className="text-xl font-semibold mb-4 text-gray-800">
        Search Products
      </h2>

      {/* Search Input */}
      <div className="relative">
        <input
          type="text"
          placeholder="Search by product name or SKU..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          autoFocus
        />
        <svg
          className="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
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
      </div>

      {/* Loading State */}
      {loading && (
        <div className="mt-4 text-center text-gray-500">
          <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <p className="mt-2">Searching...</p>
        </div>
      )}

      {/* Search Results */}
      {!loading && products.length > 0 && (
        <div className="mt-4 max-h-96 overflow-y-auto">
          <div className="space-y-2">
            {products.map((product) => (
              <div
                key={product.id}
                className="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer"
                onClick={() => onAddToCart(product)}
              >
                <div className="flex-1">
                  <h3 className="font-semibold text-gray-800">
                    {product.name}
                  </h3>
                  <p className="text-sm text-gray-600">SKU: {product.sku}</p>
                  <p className="text-sm text-gray-500">
                    Category: {product.category?.name || "N/A"}
                  </p>
                </div>
                <div className="text-right ml-4">
                  <p className="text-lg font-bold text-green-600">
                    ৳{parseFloat(product.selling_price).toFixed(2)}
                  </p>
                  <button className="mt-1 px-4 py-1 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition">
                    Add to Cart
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* No Results */}
      {!loading && searchQuery && products.length === 0 && (
        <div className="mt-4 text-center text-gray-500">
          <p>No products found for "{searchQuery}"</p>
        </div>
      )}

      {/* Help Text */}
      {!loading && !searchQuery && (
        <div className="mt-4 text-center text-gray-400">
          <p>Start typing to search for products...</p>
        </div>
      )}
    </div>
  );
};

export default ProductSearch;
