<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::with(['parent', 'children']);

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Get only parent categories if requested
        if ($request->has('parent_only') && $request->parent_only) {
            $query->whereNull('parent_id');
        }

        $categories = $query->latest()->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($request->name);

        $category = Category::create($data);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category->load('parent'),
        ], 201);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category->load(['parent', 'children', 'products']),
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->load('parent'),
        ]);
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
