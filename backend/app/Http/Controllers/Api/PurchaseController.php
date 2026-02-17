<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\BranchStock;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases
     */
    public function index(Request $request): JsonResponse
    {
        $query = Purchase::with(['branch', 'supplier', 'user', 'items.product']);

        // Filter by branch
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $purchases = $query->latest('purchase_date')->paginate($request->per_page ?? 15);

        return response()->json($purchases);
    }

    /**
     * Store a newly created purchase
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
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

            // Generate invoice number
            $lastPurchase = Purchase::latest('id')->first();
            $invoiceNo = 'PUR-' . date('Ymd') . '-' . str_pad(($lastPurchase ? $lastPurchase->id + 1 : 1), 4, '0', STR_PAD_LEFT);

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

            // Create purchase
            $purchase = Purchase::create([
                'invoice_no' => $invoiceNo,
                'branch_id' => $request->branch_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_date' => $request->purchase_date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'note' => $request->note,
            ]);

            // Create purchase items and update stock
            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);

                // Update or create branch stock with pessimistic locking
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $request->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($stock) {
                    // Update existing stock
                    $stock->increment('quantity', $item['quantity']);
                } else {
                    // Create new stock record
                    BranchStock::create([
                        'branch_id' => $request->branch_id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            // Update supplier balance
            $supplier = Supplier::find($request->supplier_id);
            $supplier->increment('current_balance', $dueAmount);

            DB::commit();

            return response()->json([
                'message' => 'Purchase created successfully',
                'purchase' => $purchase->load(['branch', 'supplier', 'items.product']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified purchase
     */
    public function show(Purchase $purchase): JsonResponse
    {
        return response()->json([
            'purchase' => $purchase->load(['branch', 'supplier', 'user', 'items.product']),
        ]);
    }

    /**
     * Update the specified purchase (limited fields)
     */
    public function update(Request $request, Purchase $purchase): JsonResponse
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

        $purchase->update($validator->validated());

        return response()->json([
            'message' => 'Purchase updated successfully',
            'purchase' => $purchase->load(['branch', 'supplier', 'items.product']),
        ]);
    }

    /**
     * Remove the specified purchase
     */
    public function destroy(Purchase $purchase): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Reverse stock changes with pessimistic locking
            foreach ($purchase->items as $item) {
                $stock = BranchStock::lockForUpdate()
                    ->where('branch_id', $purchase->branch_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($stock) {
                    // Prevent negative stock
                    if ($stock->quantity < $item->quantity) {
                        throw new \Exception("Cannot delete purchase: would result in negative stock for product ID {$item->product_id}");
                    }
                    $stock->decrement('quantity', $item->quantity);
                }
            }

            // Update supplier balance
            $supplier = Supplier::find($purchase->supplier_id);
            $supplier->decrement('current_balance', $purchase->due_amount);

            $purchase->delete();

            DB::commit();

            return response()->json([
                'message' => 'Purchase deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
