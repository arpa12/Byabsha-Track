<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shop\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount(['products', 'sales'])->latest()->get();
        return view('shop::index', compact('shops'));
    }

    public function create()
    {
        return view('shop::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Shop::create($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop created successfully!');
    }

    public function show($id)
    {
        $shop = Shop::withCount(['products', 'sales'])
            ->with(['products' => function($query) {
                $query->latest()->take(10);
            }])
            ->findOrFail($id);

        return view('shop::show', compact('shop'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop::edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shop->update($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();

        return redirect()->route('shop.index')
            ->with('success', 'Shop deleted successfully!');
    }
}
