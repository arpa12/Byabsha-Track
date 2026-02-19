import api from "./api";

/**
 * Sales Service
 * Handles all sales-related API operations
 */

/**
 * Get all sales with filters and pagination
 * @param {Object} params - Filter parameters
 * @returns {Promise} Sales data with pagination
 */
export const getAllSales = async (params = {}) => {
  try {
    const response = await api.get("/sales", { params });
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Get single sale by ID
 * @param {number} id - Sale ID
 * @returns {Promise} Sale details
 */
export const getSaleById = async (id) => {
  try {
    const response = await api.get(`/sales/${id}`);
    return response.data.sale || response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Delete a sale (owner only, within time limit)
 * @param {number} id - Sale ID
 * @returns {Promise} Success message
 */
export const deleteSale = async (id) => {
  try {
    const response = await api.delete(`/sales/${id}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Update sale (limited fields: notes, customer info)
 * @param {number} id - Sale ID
 * @param {Object} data - Updated fields
 * @returns {Promise} Updated sale
 */
export const updateSale = async (id, data) => {
  try {
    const response = await api.put(`/sales/${id}`, data);
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Get sales statistics
 * @param {Object} params - Filter parameters (branch_id, date range, etc.)
 * @returns {Promise} Statistics data
 */
export const getSalesStats = async (params = {}) => {
  try {
    const response = await api.get("/sales/stats", { params });
    return response.data;
  } catch (error) {
    // If endpoint doesn't exist, calculate stats from sales list
    console.warn("Stats endpoint not available, using fallback calculation");
    return null;
  }
};

/**
 * Export sales to CSV
 * @param {Object} params - Filter parameters
 * @returns {Promise} CSV file
 */
export const exportSalesToCSV = async (params = {}) => {
  try {
    const response = await api.get("/sales/export/csv", {
      params,
      responseType: "blob",
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Export sales to PDF
 * @param {Object} params - Filter parameters
 * @returns {Promise} PDF file
 */
export const exportSalesToPDF = async (params = {}) => {
  try {
    const response = await api.get("/sales/export/pdf", {
      params,
      responseType: "blob",
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Search sales by invoice number, customer name, or phone
 * @param {string} query - Search query
 * @param {Object} additionalParams - Additional filter parameters
 * @returns {Promise} Search results
 */
export const searchSales = async (query, additionalParams = {}) => {
  try {
    const params = {
      search: query,
      ...additionalParams,
    };
    const response = await api.get("/sales", { params });
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Get customer purchase history
 * @param {string} customerPhone - Customer phone number
 * @returns {Promise} Customer's sales history
 */
export const getCustomerHistory = async (customerPhone) => {
  try {
    const response = await api.get("/sales", {
      params: { customer_phone: customerPhone },
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};

/**
 * Process sale return (future feature)
 * @param {number} saleId - Sale ID
 * @param {Object} returnData - Return details (items, reason, etc.)
 * @returns {Promise} Return record
 */
export const processSaleReturn = async (saleId, returnData) => {
  try {
    const response = await api.post(`/sales/${saleId}/return`, returnData);
    return response.data;
  } catch (error) {
    throw error;
  }
};

export default {
  getAllSales,
  getSaleById,
  deleteSale,
  updateSale,
  getSalesStats,
  exportSalesToCSV,
  exportSalesToPDF,
  searchSales,
  getCustomerHistory,
  processSaleReturn,
};
