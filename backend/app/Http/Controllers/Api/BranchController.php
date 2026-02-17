<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of branches
     */
    public function index(Request $request): JsonResponse
    {
        $query = Branch::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $branches = $query->withCount(['users', 'stocks', 'sales', 'purchases'])
            ->latest()
            ->get();

        return response()->json(['branches' => $branches]);
    }

    /**
     * Store a newly created branch
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches,code',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $branch = Branch::create($validator->validated());

        return response()->json([
            'message' => 'Branch created successfully',
            'branch' => $branch,
        ], 201);
    }

    /**
     * Display the specified branch
     */
    public function show(Branch $branch): JsonResponse
    {
        return response()->json([
            'branch' => $branch->load(['users', 'stocks.product']),
        ]);
    }

    /**
     * Update the specified branch
     */
    public function update(Request $request, Branch $branch): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:branches,code,' . $branch->id,
            'address' => 'sometimes|required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $branch->update($validator->validated());

        return response()->json([
            'message' => 'Branch updated successfully',
            'branch' => $branch,
        ]);
    }

    /**
     * Remove the specified branch
     */
    public function destroy(Branch $branch): JsonResponse
    {
        $branch->delete();

        return response()->json([
            'message' => 'Branch deleted successfully',
        ]);
    }
}
