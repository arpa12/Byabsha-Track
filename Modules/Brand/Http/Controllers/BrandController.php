<?php

namespace Modules\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Brand\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::forUser(auth()->user())->withCount('products')->latest()->paginate(15);

        return view('brand::index', compact('brands'));
    }

    public function create()
    {
        return view('brand::create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $validated['user_id'] = $user->id;

        Brand::create($validated);

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.created'));
    }

    public function show($id)
    {
        $brand = Brand::forUser(auth()->user())->withCount('products')->findOrFail($id);

        return view('brand::show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::forUser(auth()->user())->findOrFail($id);

        return view('brand::edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $brand = Brand::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brand->id)->where('user_id', $user->isSuperAdmin() ? null : $user->id),
            ],
        ]);

        $brand->update($validated);

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.updated'));
    }

    public function destroy($id)
    {
        $brand = Brand::forUser(auth()->user())->findOrFail($id);

        if ($brand->products()->exists()) {
            return redirect()->route('brand.index')
                ->withErrors(['error' => __('brand::brand.cannot_delete_in_use')]);
        }

        $brand->delete();

        return redirect()->route('brand.index')
            ->with('success', __('brand::brand.deleted'));
    }
}
