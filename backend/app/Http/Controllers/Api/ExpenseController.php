<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses
     */
    public function index(Request $request): JsonResponse
    {
        $query = Expense::with(['branch', 'user']);

        // Filter by branch
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->latest('expense_date')->paginate($request->per_page ?? 15);

        return response()->json($expenses);
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->id();

        $expense = Expense::create($data);

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense->load(['branch', 'user']),
        ], 201);
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense): JsonResponse
    {
        return response()->json([
            'expense' => $expense->load(['branch', 'user']),
        ]);
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|exists:branches,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'sometimes|required|numeric|min:0',
            'expense_date' => 'sometimes|required|date',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $expense->update($validator->validated());

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense->load(['branch', 'user']),
        ]);
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }

    /**
     * Get expense categories
     */
    public function categories(): JsonResponse
    {
        $categories = Expense::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return response()->json(['categories' => $categories]);
    }
}
