import React from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import ProtectedRoute from "./components/ProtectedRoute";
import Layout from "./components/Layout";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import POS from "./pages/POS";
import Unauthorized from "./pages/Unauthorized";
import "./App.css";

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Public routes */}
          <Route path="/login" element={<Login />} />
          <Route path="/unauthorized" element={<Unauthorized />} />

          {/* Protected routes */}
          <Route
            path="/dashboard"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <Dashboard />
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/pos"
            element={
              <ProtectedRoute>
                <POS />
              </ProtectedRoute>
            }
          />

          <Route
            path="/sales"
            element={
              <ProtectedRoute>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Sales List</h1>
                    <p className="text-gray-600 mt-2">
                      Sales list coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/purchases"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Purchases</h1>
                    <p className="text-gray-600 mt-2">
                      Purchase management coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/products"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Products</h1>
                    <p className="text-gray-600 mt-2">
                      Product management coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/categories"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Categories</h1>
                    <p className="text-gray-600 mt-2">
                      Category management coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/suppliers"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Suppliers</h1>
                    <p className="text-gray-600 mt-2">
                      Supplier management coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/expenses"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Expenses</h1>
                    <p className="text-gray-600 mt-2">
                      Expense tracking coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/branches"
            element={
              <ProtectedRoute roles={["owner"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Branches</h1>
                    <p className="text-gray-600 mt-2">
                      Branch management coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          <Route
            path="/reports"
            element={
              <ProtectedRoute roles={["owner", "manager"]}>
                <Layout>
                  <div className="text-center">
                    <h1 className="text-2xl font-bold">Reports</h1>
                    <p className="text-gray-600 mt-2">
                      Report generation coming soon...
                    </p>
                  </div>
                </Layout>
              </ProtectedRoute>
            }
          />

          {/* Default redirect */}
          <Route path="/" element={<Navigate to="/dashboard" replace />} />
          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
