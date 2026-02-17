import { useRef } from "react";

const InvoiceModal = ({ invoice, onClose }) => {
  const printRef = useRef();

  const handlePrint = () => {
    const printContent = printRef.current;
    const printWindow = window.open("", "", "width=800,height=600");
    printWindow.document.write(`
      <html>
        <head>
          <title>Invoice ${invoice.invoice_no}</title>
          <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .header { text-align: center; margin-bottom: 20px; }
            .info { margin-bottom: 15px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f5f5f5; }
            .total-row { font-weight: bold; background-color: #f9f9f9; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; }
          </style>
        </head>
        <body>
          ${printContent.innerHTML}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        {/* Modal Header */}
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <h2 className="text-2xl font-bold text-gray-800">
            Sale Completed! 🎉
          </h2>
          <button
            onClick={onClose}
            className="text-gray-500 hover:text-gray-700 transition"
          >
            <svg
              className="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        {/* Invoice Content */}
        <div ref={printRef} className="p-6">
          {/* Business Info */}
          <div className="header text-center mb-6">
            <h1 className="text-3xl font-bold text-gray-900">
              {invoice.business.name}
            </h1>
            <p className="text-gray-600">{invoice.business.address}</p>
            <p className="text-gray-600">
              Phone: {invoice.business.phone} | Email: {invoice.business.email}
            </p>
            <div className="mt-4 pt-4 border-t border-gray-300">
              <h2 className="text-xl font-semibold text-blue-600">
                INVOICE #{invoice.invoice_no}
              </h2>
              <p className="text-sm text-gray-600">
                Date: {new Date(invoice.sale_date).toLocaleString()}
              </p>
            </div>
          </div>

          {/* Customer & Salesman Info */}
          <div className="grid grid-cols-2 gap-6 mb-6">
            <div className="info bg-gray-50 p-4 rounded-lg">
              <h3 className="font-semibold text-gray-800 mb-2">Customer:</h3>
              <p className="text-gray-700">
                {invoice.customer.name || "Walk-in Customer"}
              </p>
              {invoice.customer.phone && (
                <p className="text-gray-600 text-sm">
                  Phone: {invoice.customer.phone}
                </p>
              )}
            </div>
            <div className="info bg-gray-50 p-4 rounded-lg">
              <h3 className="font-semibold text-gray-800 mb-2">Salesman:</h3>
              <p className="text-gray-700">{invoice.salesman.name}</p>
              <p className="text-gray-600 text-sm">{invoice.salesman.email}</p>
            </div>
          </div>

          {/* Items Table */}
          <table className="w-full">
            <thead>
              <tr className="bg-gray-100">
                <th className="px-4 py-2 text-left">#</th>
                <th className="px-4 py-2 text-left">Product</th>
                <th className="px-4 py-2 text-center">Qty</th>
                <th className="px-4 py-2 text-right">Price</th>
                <th className="px-4 py-2 text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              {invoice.items.map((item, index) => (
                <tr key={item.id} className="border-b border-gray-200">
                  <td className="px-4 py-3">{index + 1}</td>
                  <td className="px-4 py-3">
                    <div>
                      <p className="font-medium">{item.product_name}</p>
                      <p className="text-sm text-gray-600">
                        SKU: {item.product_sku}
                      </p>
                      <p className="text-xs text-gray-500">
                        Category: {item.category}
                      </p>
                    </div>
                  </td>
                  <td className="px-4 py-3 text-center">{item.quantity}</td>
                  <td className="px-4 py-3 text-right">
                    ৳{parseFloat(item.unit_price).toFixed(2)}
                  </td>
                  <td className="px-4 py-3 text-right font-semibold">
                    ৳{parseFloat(item.subtotal).toFixed(2)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {/* Payment Summary */}
          <div className="mt-6 border-t border-gray-300 pt-4">
            <div className="flex justify-end">
              <div className="w-80 space-y-2">
                <div className="flex justify-between text-gray-700">
                  <span>Subtotal:</span>
                  <span>
                    ৳{parseFloat(invoice.payment.subtotal).toFixed(2)}
                  </span>
                </div>
                {parseFloat(invoice.payment.discount_amount) > 0 && (
                  <div className="flex justify-between text-red-600">
                    <span>
                      Discount (
                      {invoice.payment.discount_type === "percentage"
                        ? `${invoice.payment.discount_value}%`
                        : "Fixed"}
                      ):
                    </span>
                    <span>
                      -৳{parseFloat(invoice.payment.discount_amount).toFixed(2)}
                    </span>
                  </div>
                )}
                {parseFloat(invoice.payment.tax_amount) > 0 && (
                  <div className="flex justify-between text-gray-700">
                    <span>Tax ({invoice.payment.tax_rate}%):</span>
                    <span>
                      ৳{parseFloat(invoice.payment.tax_amount).toFixed(2)}
                    </span>
                  </div>
                )}
                <div className="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t border-gray-300">
                  <span>Total:</span>
                  <span>
                    ৳{parseFloat(invoice.payment.total_amount).toFixed(2)}
                  </span>
                </div>
                <div className="flex justify-between text-gray-700">
                  <span>Paid Amount:</span>
                  <span>
                    ৳{parseFloat(invoice.payment.paid_amount).toFixed(2)}
                  </span>
                </div>
                {parseFloat(invoice.payment.change_amount) > 0 && (
                  <div className="flex justify-between text-green-600 font-semibold">
                    <span>Change:</span>
                    <span>
                      ৳{parseFloat(invoice.payment.change_amount).toFixed(2)}
                    </span>
                  </div>
                )}
                {parseFloat(invoice.payment.due_amount) > 0 && (
                  <div className="flex justify-between text-red-600 font-semibold">
                    <span>Due:</span>
                    <span>
                      ৳{parseFloat(invoice.payment.due_amount).toFixed(2)}
                    </span>
                  </div>
                )}
                <div className="flex justify-between text-gray-600 text-sm pt-2 border-t border-gray-200">
                  <span>Payment Method:</span>
                  <span className="capitalize">
                    {invoice.payment.payment_method.replace("_", " ")}
                  </span>
                </div>
                <div className="flex justify-between text-gray-600 text-sm">
                  <span>Payment Status:</span>
                  <span className="capitalize font-semibold">
                    {invoice.payment.payment_status}
                  </span>
                </div>
              </div>
            </div>
          </div>

          {/* Profit Info (for internal use - won't print) */}
          <div className="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 className="font-semibold text-gray-800 mb-2">
              Profit Information:
            </h3>
            <div className="flex justify-between text-sm">
              <span>Total Profit:</span>
              <span className="font-bold text-green-600">
                ৳{parseFloat(invoice.profit.total_profit).toFixed(2)}
              </span>
            </div>
            <div className="flex justify-between text-sm">
              <span>Profit Margin:</span>
              <span className="font-semibold">
                {invoice.profit.profit_margin}%
              </span>
            </div>
          </div>

          {/* Footer */}
          <div className="footer mt-8 pt-4 border-t border-gray-300 text-center text-gray-600">
            <p className="font-semibold">Thank you for your business!</p>
            <p className="text-sm mt-2">
              This is a computer generated invoice.
            </p>
          </div>
        </div>

        {/* Modal Actions */}
        <div className="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex gap-3">
          <button
            onClick={handlePrint}
            className="flex-1 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition"
          >
            🖨️ Print Invoice
          </button>
          <button
            onClick={onClose}
            className="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  );
};

export default InvoiceModal;
