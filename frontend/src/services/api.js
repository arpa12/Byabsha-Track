import axios from "axios";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000/api";

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.log("[API Interceptor] Error response:", {
      status: error.response?.status,
      url: error.config?.url,
      message: error.message,
    });

    // Only redirect to login on 401 for specific endpoints
    // Don't redirect for other protected endpoints that might return 401/403
    if (
      error.response?.status === 401 &&
      (error.config?.url?.includes("/me") ||
        error.config?.url?.includes("/login"))
    ) {
      console.warn(
        "[API Interceptor] Auth endpoint 401 - redirecting to login",
      );
      // Only clear tokens if it's an authentication endpoint failure
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      window.location.href = "/login";
    } else if (error.response?.status === 401) {
      console.warn(
        "[API Interceptor] 401 on non-auth endpoint - NOT redirecting:",
        error.config?.url,
      );
    }
    return Promise.reject(error);
  },
);

export default api;
