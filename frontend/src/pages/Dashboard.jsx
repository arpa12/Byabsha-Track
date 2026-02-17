import React, { useEffect, useState } from "react";
import { reportService } from "../services";
import { useAuth } from "../context/AuthContext";

const Dashboard = () => {
  const { user } = useAuth();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchDashboard = async () => {
      try {
        const data = await reportService.getDashboard(user?.branch_id);
        setStats(data);
      } catch (error) {
        console.error("Failed to fetch dashboard:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchDashboard();
  }, [user]);

  if (loading) {
    return <div className="text-center">Loading...</div>;
  }

  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {/* Today's Sales */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            Today's Sales
          </h3>
          <p className="mt-2 text-3xl font-bold text-gray-900">
            ৳{stats?.today_sales?.toFixed(2) || "0.00"}
          </p>
        </div>

        {/* Monthly Sales */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            This Month's Sales
          </h3>
          <p className="mt-2 text-3xl font-bold text-gray-900">
            ৳{stats?.month_sales?.toFixed(2) || "0.00"}
          </p>
        </div>

        {/* Today's Profit */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            Today's Profit
          </h3>
          <p className="mt-2 text-3xl font-bold text-green-600">
            ৳{stats?.today_profit?.toFixed(2) || "0.00"}
          </p>
        </div>

        {/* Total Products */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            Total Products
          </h3>
          <p className="mt-2 text-3xl font-bold text-gray-900">
            {stats?.total_products || 0}
          </p>
        </div>

        {/* Low Stock Alert */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            Low Stock Products
          </h3>
          <p className="mt-2 text-3xl font-bold text-red-600">
            {stats?.low_stock_count || 0}
          </p>
        </div>

        {/* Purchase Dues */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="text-sm font-medium text-gray-500 uppercase">
            Purchase Dues
          </h3>
          <p className="mt-2 text-3xl font-bold text-orange-600">
            ৳{stats?.purchase_dues?.toFixed(2) || "0.00"}
          </p>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="mt-8">
        <h2 className="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          <a
            href="/pos"
            className="bg-blue-600 text-white rounded-lg p-4 text-center hover:bg-blue-700 transition"
          >
            New Sale
          </a>
          <a
            href="/purchases/new"
            className="bg-green-600 text-white rounded-lg p-4 text-center hover:bg-green-700 transition"
          >
            New Purchase
          </a>
          <a
            href="/products/new"
            className="bg-purple-600 text-white rounded-lg p-4 text-center hover:bg-purple-700 transition"
          >
            Add Product
          </a>
          <a
            href="/reports"
            className="bg-indigo-600 text-white rounded-lg p-4 text-center hover:bg-indigo-700 transition"
          >
            View Reports
          </a>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
