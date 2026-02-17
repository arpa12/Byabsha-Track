import api from "./api";

/**
 * POS API Service
 * Handles all POS-related API calls
 */

// Search products
export const searchProducts = async (query, perPage = 10) => {
  try {
    const response = await api.get("/products", {
      params: {
        search: query,
        per_page: perPage,
      },
    });
    return response.data.data || response.data;
  } catch (error) {
    throw error;
  }
};

// Get product stock for a branch
export const getProductStock = async (productId, branchId) => {
  try {
    const response = await api.get(`/products/${productId}/stock`, {
      params: {
        branch_id: branchId,
      },
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get all branches
export const getBranches = async () => {
  try {
    const response = await api.get("/branches");
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Complete POS sale
export const completeSale = async (saleData) => {
  try {
    const response = await api.post("/sales/pos", saleData);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get sale by ID
export const getSale = async (saleId) => {
  try {
    const response = await api.get(`/sales/${saleId}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get today's sales
export const getTodaySales = async (branchId = null) => {
  try {
    const params = {};
    if (branchId) {
      params.branch_id = branchId;
    }
    const response = await api.get("/sales", { params });
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get product by ID
export const getProduct = async (productId) => {
  try {
    const response = await api.get(`/products/${productId}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get product by SKU
export const getProductBySKU = async (sku) => {
  try {
    const response = await api.get("/products", {
      params: {
        sku: sku,
      },
    });
    return response.data.data?.[0] || null;
  } catch (error) {
    throw error;
  }
};

export default {
  searchProducts,
  getProductStock,
  getBranches,
  completeSale,
  getSale,
  getTodaySales,
  getProduct,
  getProductBySKU,
};
