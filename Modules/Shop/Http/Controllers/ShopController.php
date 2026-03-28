<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shop\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->withCount(['products', 'sales'])->latest()->get();
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

        $validated['user_id'] = auth()->id();

        Shop::create($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop created successfully!');
    }

    public function show($id)
    {
        $user = auth()->user();
        $shop = Shop::forUser($user)->withCount(['products', 'sales'])
            ->with(['products' => function($query) {
                $query->latest()->take(10);
            }])
            ->findOrFail($id);

        return view('shop::show', compact('shop'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $shop = Shop::forUser($user)->findOrFail($id);
        return view('shop::edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $shop = Shop::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shop->update($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $shop = Shop::forUser($user)->findOrFail($id);
        $shop->delete();

        return redirect()->route('shop.index')
            ->with('success', 'Shop deleted successfully!');
    }
}
