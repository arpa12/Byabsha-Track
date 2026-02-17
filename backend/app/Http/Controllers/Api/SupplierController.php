<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request): JsonResponse
    {
        $query = Supplier::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $suppliers = $query->withCount('purchases')->latest()->paginate($request->per_page ?? 15);

        return response()->json($suppliers);
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['current_balance'] = $data['opening_balance'] ?? 0;

        $supplier = Supplier::create($data);

        return response()->json([
            'message' => 'Supplier created successfully',
            'supplier' => $supplier,
        ], 201);
    }

    /**
     * Display the specified supplier
     */
    public function show(Supplier $supplier): JsonResponse
    {
        return response()->json([
            'supplier' => $supplier->load('purchases'),
        ]);
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->update($validator->validated());

        return response()->json([
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier,
        ]);
    }

    /**
     * Remove the specified supplier
     */
    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully',
        ]);
    }
}
