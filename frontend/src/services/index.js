import api from "./api";
import posService from "./posService";

export { posService };

export const authService = {
  login: async (credentials) => {
    const response = await api.post("/login", credentials);
    if (response.data.success && response.data.data) {
      const { token, user } = response.data.data;
      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
      return { token, user };
    }
    return response.data;
  },

  register: async (userData) => {
    const response = await api.post("/register", userData);
    if (response.data.success && response.data.data) {
      const { token, user } = response.data.data;
      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
      return { token, user };
    }
    return response.data;
  },

  logout: async () => {
    try {
      await api.post("/logout");
    } finally {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    }
  },

  getCurrentUser: () => {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
  },

  isAuthenticated: () => {
    return !!localStorage.getItem("token");
  },

  getMe: async () => {
    const response = await api.get("/me");
    if (response.data.success && response.data.data) {
      const user = response.data.data.user;
      localStorage.setItem("user", JSON.stringify(user));
      return user;
    }
    return response.data.user;
  },
};

export const productService = {
  getAll: async (params = {}) => {
    const response = await api.get("/products", { params });
    return response.data;
  },

  getOne: async (id) => {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/products", data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/products/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/products/${id}`);
    return response.data;
  },

  getLowStock: async (branchId) => {
    const response = await api.get("/products/low-stock", {
      params: { branch_id: branchId },
    });
    return response.data;
  },
};

export const categoryService = {
  getAll: async (params = {}) => {
    const response = await api.get("/categories", { params });
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/categories", data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/categories/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/categories/${id}`);
    return response.data;
  },
};

export const supplierService = {
  getAll: async (params = {}) => {
    const response = await api.get("/suppliers", { params });
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/suppliers", data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/suppliers/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/suppliers/${id}`);
    return response.data;
  },
};

export const purchaseService = {
  getAll: async (params = {}) => {
    const response = await api.get("/purchases", { params });
    return response.data;
  },

  getOne: async (id) => {
    const response = await api.get(`/purchases/${id}`);
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/purchases", data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/purchases/${id}`);
    return response.data;
  },
};

export const saleService = {
  getAll: async (params = {}) => {
    const response = await api.get("/sales", { params });
    return response.data;
  },

  getOne: async (id) => {
    const response = await api.get(`/sales/${id}`);
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/sales", data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/sales/${id}`);
    return response.data;
  },
};

export const branchService = {
  getAll: async () => {
    const response = await api.get("/branches");
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/branches", data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/branches/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/branches/${id}`);
    return response.data;
  },
};

export const expenseService = {
  getAll: async (params = {}) => {
    const response = await api.get("/expenses", { params });
    return response.data;
  },

  create: async (data) => {
    const response = await api.post("/expenses", data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/expenses/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/expenses/${id}`);
    return response.data;
  },

  getCategories: async () => {
    const response = await api.get("/expenses/categories");
    return response.data;
  },
};

export const reportService = {
  getDashboard: async (branchId) => {
    const response = await api.get("/reports/dashboard", {
      params: { branch_id: branchId },
    });
    return response.data;
  },

  getDailyProfit: async (date, branchId) => {
    const response = await api.get("/reports/daily-profit", {
      params: { date, branch_id: branchId },
    });
    return response.data;
  },

  getMonthlyProfit: async (month, year, branchId) => {
    const response = await api.get("/reports/monthly-profit", {
      params: { month, year, branch_id: branchId },
    });
    return response.data;
  },

  getSalesSummary: async (startDate, endDate, branchId) => {
    const response = await api.get("/reports/sales-summary", {
      params: { start_date: startDate, end_date: endDate, branch_id: branchId },
    });
    return response.data;
  },

  getPurchaseSummary: async (startDate, endDate, branchId) => {
    const response = await api.get("/reports/purchase-summary", {
      params: { start_date: startDate, end_date: endDate, branch_id: branchId },
    });
    return response.data;
  },

  getTopSellingProducts: async (startDate, endDate, branchId, limit = 10) => {
    const response = await api.get("/reports/top-selling-products", {
      params: {
        start_date: startDate,
        end_date: endDate,
        branch_id: branchId,
        limit,
      },
    });
    return response.data;
  },
};
