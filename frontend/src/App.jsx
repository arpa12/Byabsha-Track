import React from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import { LanguageProvider } from "./context/LanguageContext";
import ProtectedRoute from "./components/ProtectedRoute";
import Layout from "./components/Layout";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import POS from "./pages/POS";
import Sales from "./pages/Sales";
import Products from "./pages/Products";
import Categories from "./pages/Categories";
import Reports from "./pages/Reports";
import Unauthorized from "./pages/Unauthorized";
import "./App.css";

function App() {
  return (
    <LanguageProvider>
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
                  <Dashboard />
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
                  <Sales />
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
                  <Products />
                </ProtectedRoute>
              }
            />

            <Route
              path="/categories"
              element={
                <ProtectedRoute roles={["owner", "manager"]}>
                  <Categories />
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
                  <Reports />
                </ProtectedRoute>
              }
            />

            {/* Default redirect */}
            <Route path="/" element={<Navigate to="/dashboard" replace />} />
            <Route path="*" element={<Navigate to="/dashboard" replace />} />
          </Routes>
        </Router>
      </AuthProvider>
    </LanguageProvider>
  );
}

export default App;
