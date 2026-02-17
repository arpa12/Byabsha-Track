<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\BranchStock;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request): JsonResponse
    {
        $query = Sale::with(['branch', 'user', 'items.product']);

        // Filter by branch
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by user (salesman)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $sales = $query->latest('sale_date')->paginate($request->per_page ?? 15);

        return response()->json($sales);
    }

    /**
     * Store a newly created sale (POS)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'sale_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_banking,bank_transfer',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Validate stock availability with pessimistic locking
            foreach ($request->items as $item) {
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $request->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                $product = Product::find($item['product_id']);

                if (!$stock) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Product '{$product->name}' is not available in this branch",
                        'product' => $product->name,
                        'available_quantity' => 0,
                        'requested_quantity' => $item['quantity'],
                    ], 422);
                }

                if ($stock->quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Insufficient stock for product: {$product->name}",
                        'product' => $product->name,
                        'available_quantity' => (float) $stock->quantity,
                        'requested_quantity' => (float) $item['quantity'],
                        'shortage' => (float) ($item['quantity'] - $stock->quantity),
                    ], 422);
                }
            }

            // Generate invoice number
            $lastSale = Sale::latest('id')->first();
            $invoiceNo = 'SAL-' . date('Ymd') . '-' . str_pad(($lastSale ? $lastSale->id + 1 : 1), 4, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $total = $subtotal - $discount + $tax;
            $paidAmount = $request->paid_amount ?? 0;
            $dueAmount = $total - $paidAmount;

            // Determine payment status
            $paymentStatus = 'unpaid';
            if ($paidAmount >= $total) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            // Create sale
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'branch_id' => $request->branch_id,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'sale_date' => $request->sale_date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ]);

            // Create sale items, calculate profit, and update stock
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $unitCost = $product->purchase_price;
                $profit = ($item['unit_price'] - $unitCost) * $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $unitCost,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'profit' => $profit,
                ]);

                // Deduct from branch stock (already locked in validation)
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $request->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($stock) {
                    $newQuantity = $stock->quantity - $item['quantity'];

                    // Additional safety check
                    if ($newQuantity < 0) {
                        throw new \Exception("Stock quantity cannot be negative for product: {$product->name}");
                    }

                    $stock->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Sale created successfully',
                'sale' => $sale->load(['branch', 'user', 'items.product']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale): JsonResponse
    {
        return response()->json([
            'sale' => $sale->load(['branch', 'user', 'items.product']),
        ]);
    }

    /**
     * Update the specified sale (limited fields)
     */
    public function update(Request $request, Sale $sale): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'note' => 'nullable|string',
            'payment_status' => 'sometimes|in:paid,partial,unpaid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $sale->update($validator->validated());

        return response()->json([
            'message' => 'Sale updated successfully',
            'sale' => $sale->load(['branch', 'user', 'items.product']),
        ]);
    }

    /**
     * Remove the specified sale
     */
    public function destroy(Sale $sale): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Reverse stock changes with pessimistic locking
            foreach ($sale->items as $item) {
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $sale->branch_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($stock) {
                    // Add back to stock
                    $stock->increment('quantity', $item->quantity);
                } else {
                    // Create stock record if it doesn't exist
                    BranchStock::create([
                        'branch_id' => $sale->branch_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            $sale->delete();

            DB::commit();

            return response()->json([
                'message' => 'Sale deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process POS sale with detailed invoice response
     */
    public function processPOS(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|in:cash,card,bkash,mobile_banking,bank_transfer',
            'paid_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|numeric|min:0.01',
            'cart_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 1: Validate stock availability with pessimistic locking
            $stockValidation = [];
            foreach ($request->cart_items as $item) {
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $request->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                $product = Product::with('category')->find($item['product_id']);

                if (!$stock) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Product not available in this branch",
                        'error' => [
                            'product_id' => $item['product_id'],
                            'product_name' => $product->name,
                            'available_quantity' => 0,
                            'requested_quantity' => $item['quantity'],
                        ]
                    ], 422);
                }

                if ($stock->quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock",
                        'error' => [
                            'product_id' => $item['product_id'],
                            'product_name' => $product->name,
                            'available_quantity' => (float) $stock->quantity,
                            'requested_quantity' => (float) $item['quantity'],
                            'shortage' => (float) ($item['quantity'] - $stock->quantity),
                        ]
                    ], 422);
                }

                $stockValidation[] = [
                    'product' => $product,
                    'stock' => $stock,
                ];
            }

            // Step 2: Calculate cart totals
            $cartSubtotal = 0;
            $cartItems = [];

            foreach ($request->cart_items as $index => $item) {
                $product = $stockValidation[$index]['product'];
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $unitCost = $product->purchase_price;
                $itemProfit = ($item['unit_price'] - $unitCost) * $item['quantity'];

                $cartSubtotal += $itemSubtotal;

                $cartItems[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'category' => $product->category->name ?? 'Uncategorized',
                    'quantity' => (float) $item['quantity'],
                    'unit_price' => (float) $item['unit_price'],
                    'unit_cost' => (float) $unitCost,
                    'subtotal' => (float) $itemSubtotal,
                    'profit' => (float) $itemProfit,
                ];
            }

            // Step 3: Apply discount
            $discountAmount = 0;
            if ($request->discount_value) {
                if ($request->discount_type === 'percentage') {
                    $discountAmount = ($cartSubtotal * $request->discount_value) / 100;
                } else {
                    $discountAmount = $request->discount_value;
                }
            }

            // Step 4: Calculate tax
            $taxAmount = 0;
            if ($request->tax_rate) {
                $taxableAmount = $cartSubtotal - $discountAmount;
                $taxAmount = ($taxableAmount * $request->tax_rate) / 100;
            }

            // Step 5: Calculate final total
            $grandTotal = $cartSubtotal - $discountAmount + $taxAmount;
            $paidAmount = $request->paid_amount ?? $grandTotal;
            $dueAmount = $grandTotal - $paidAmount;
            $changeAmount = $paidAmount > $grandTotal ? $paidAmount - $grandTotal : 0;

            // Determine payment status
            $paymentStatus = 'paid';
            if ($dueAmount > 0) {
                $paymentStatus = $paidAmount > 0 ? 'partial' : 'unpaid';
            }

            // Step 6: Generate invoice number
            $lastSale = Sale::latest('id')->first();
            $invoiceNo = 'INV-' . date('Ymd') . '-' . str_pad(($lastSale ? $lastSale->id + 1 : 1), 5, '0', STR_PAD_LEFT);

            // Step 7: Create sale record
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'branch_id' => $request->branch_id,
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'sale_date' => now()->format('Y-m-d'),
                'subtotal' => $cartSubtotal,
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ]);

            // Step 8: Create sale items and update stock
            $totalProfit = 0;
            foreach ($request->cart_items as $index => $item) {
                $product = $stockValidation[$index]['product'];
                $unitCost = $product->purchase_price;
                $itemProfit = ($item['unit_price'] - $unitCost) * $item['quantity'];
                $totalProfit += $itemProfit;

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $unitCost,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'profit' => $itemProfit,
                ]);

                // Update stock
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $request->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($stock) {
                    $newQuantity = $stock->quantity - $item['quantity'];
                    if ($newQuantity < 0) {
                        throw new \Exception("Stock validation failed for product: {$product->name}");
                    }
                    $stock->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();

            // Step 9: Return detailed invoice response
            $branch = $sale->branch;
            $user = $sale->user;

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'invoice' => [
                    'invoice_no' => $invoiceNo,
                    'sale_id' => $sale->id,
                    'date' => $sale->sale_date,
                    'time' => $sale->created_at->format('h:i A'),

                    // Business info
                    'business' => [
                        'name' => 'ByabshaTrack',
                        'branch' => $branch->name,
                        'address' => $branch->address,
                        'phone' => $branch->phone,
                        'email' => $branch->email,
                    ],

                    // Customer info
                    'customer' => [
                        'name' => $sale->customer_name ?? 'Walk-in Customer',
                        'phone' => $sale->customer_phone,
                    ],

                    // Salesman info
                    'salesman' => [
                        'name' => $user->name,
                        'id' => $user->id,
                    ],

                    // Cart items
                    'items' => $cartItems,
                    'total_items' => count($cartItems),
                    'total_quantity' => array_sum(array_column($cartItems, 'quantity')),

                    // Payment breakdown
                    'payment' => [
                        'subtotal' => (float) $cartSubtotal,
                        'discount' => [
                            'type' => $request->discount_type ?? null,
                            'value' => $request->discount_value ? (float) $request->discount_value : 0,
                            'amount' => (float) $discountAmount,
                        ],
                        'tax' => [
                            'rate' => $request->tax_rate ? (float) $request->tax_rate : 0,
                            'amount' => (float) $taxAmount,
                        ],
                        'grand_total' => (float) $grandTotal,
                        'paid_amount' => (float) $paidAmount,
                        'due_amount' => (float) $dueAmount,
                        'change_amount' => (float) $changeAmount,
                        'payment_method' => $request->payment_method,
                        'payment_status' => $paymentStatus,
                    ],

                    // Profit info (for internal use)
                    'profit' => [
                        'total_profit' => (float) $totalProfit,
                        'profit_margin' => $cartSubtotal > 0 ? (float) round(($totalProfit / $cartSubtotal) * 100, 2) : 0,
                    ],
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
