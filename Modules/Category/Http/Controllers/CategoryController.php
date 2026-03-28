<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Category\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::forUser(auth()->user())->withCount('products')->latest()->paginate(15);

        return view('category::index', compact('categories'));
    }

    public function create()
    {
        return view('category::create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $validated['user_id'] = $user->id;

        Category::create($validated);

        return redirect()->route('category.index')
            ->with('success', __('category::category.created'));
    }

    public function show($id)
    {
        $category = Category::forUser(auth()->user())->withCount('products')->findOrFail($id);

        return view('category::show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::forUser(auth()->user())->findOrFail($id);

        return view('category::edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $category = Category::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id)->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $category->update($validated);

        return redirect()->route('category.index')
            ->with('success', __('category::category.updated'));
    }

    public function destroy($id)
    {
        $category = Category::forUser(auth()->user())->findOrFail($id);

        if ($category->products()->exists()) {
            return redirect()->route('category.index')
                ->withErrors(['error' => __('category::category.cannot_delete_in_use')]);
        }

        $category->delete();

        return redirect()->route('category.index')
            ->with('success', __('category::category.deleted'));
    }
}
